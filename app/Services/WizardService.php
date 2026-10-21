<?php

namespace App\Services;

use App\Models\Facility;
use App\Models\FacilityTimeSlot;
use App\Models\FacilityBlackoutDate;
use App\Models\Reservation;
use App\Models\SlotReservation;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;

/**
 * WizardService — smart venue matching and timeline helpers for the
 * redesigned reservation wizard. All helpers are additive; the core
 * ReservationAvailability service and the store() flow remain untouched.
 */
class WizardService
{
    /**
     * Fetch the occupied time windows for a single facility over the
     * given date range (plus a buffer window around the dates) so the
     * UI can draw a "what's booked" timeline.
     */
    public static function occupiedWindows(Facility $facility, string $startDate, string $endDate): array
    {
        $bufferStart = Carbon::parse($startDate)->subDays(7)->toDateString();
        $bufferEnd   = Carbon::parse($endDate)->addDays(7)->toDateString();

        $windows = collect();

        // 1. Legacy reservations without slot records: treat the whole span as
        //    a single occupied window (conservative — matches checkAvailability).
        $legacy = Reservation::where('facility_id', $facility->id)
            ->whereIn('status', ['pending', 'approved'])
            ->whereDate('end_date', '>=', $bufferStart)
            ->whereDate('start_date', '<=', $bufferEnd)
            ->whereDoesntHave('slotReservations')
            ->select('start_date', 'end_date', 'start_time', 'end_time', 'event_title', 'status')
            ->get();

        foreach ($legacy as $r) {
            $windows->push([
                'date'        => Carbon::parse($r->start_date)->toDateString(),
                'start_time'  => Carbon::parse($r->start_time)->format('H:i'),
                'end_time'    => Carbon::parse($r->end_time)->format('H:i'),
                'end_date'    => Carbon::parse($r->end_date)->toDateString(),
                'title'       => $r->event_title,
                'pending'     => $r->status === 'pending',
                'type'        => 'reservation',
            ]);
        }

        // 2. Capacity-based slot bookings: aggregate booked quantity per slot
        //    per date. When a slot is full, that window is blocked.
        $slotBookings = SlotReservation::query()
            ->selectRaw(
                'slot_reservations.facility_time_slot_id, slot_reservations.reserved_date, '
                . 'SUM(slot_reservations.quantity_booked) as total_qty, '
                . "SUM(CASE WHEN reservations.status = 'approved' THEN slot_reservations.quantity_booked ELSE 0 END) as approved_qty"
            )
            ->join('reservations', 'reservations.id', '=', 'slot_reservations.reservation_id')
            ->whereIn('reservations.status', ['pending', 'approved'])
            ->whereDate('slot_reservations.reserved_date', '>=', $bufferStart)
            ->whereDate('slot_reservations.reserved_date', '<=', $bufferEnd)
            ->groupBy('slot_reservations.facility_time_slot_id', 'slot_reservations.reserved_date')
            ->get();

        $slotById = FacilityTimeSlot::where('facility_id', $facility->id)
            ->where('is_active', true)
            ->get()
            ->keyBy('id');

        foreach ($slotBookings as $row) {
            $slot = $slotById->get($row->facility_time_slot_id);
            if (!$slot) {
                continue;
            }

            $capacity = max(1, (int) $slot->capacity);
            if ((int) $row->total_qty < $capacity) {
                // Partially booked — report fill level instead of a hard block.
                $windows->push([
                    'date'       => Carbon::parse($row->reserved_date)->toDateString(),
                    'start_time' => Carbon::parse($slot->start_time)->format('H:i'),
                    'end_time'   => Carbon::parse($slot->end_time)->format('H:i'),
                    'fill'       => [
                        'booked'   => (int) $row->total_qty,
                        'approved' => (int) $row->approved_qty,
                        'capacity' => $capacity,
                    ],
                    'type'       => 'slot',
                ]);
            } else {
                $windows->push([
                    'date'       => Carbon::parse($row->reserved_date)->toDateString(),
                    'start_time' => Carbon::parse($slot->start_time)->format('H:i'),
                    'end_time'   => Carbon::parse($slot->end_time)->format('H:i'),
                    'fill'       => [
                        'booked'   => (int) $row->total_qty,
                        'approved' => (int) $row->approved_qty,
                        'capacity' => $capacity,
                    ],
                    'pending'    => (int) $row->approved_qty < $capacity,
                    'type'       => 'slot_blocked',
                ]);
            }
        }

        return $windows->values()->all();
    }

    /**
     * Compute the operating-hour blocks for a facility on a given weekday.
     */
    public static function operatingBlocks(Facility $facility, int $dayOfWeek): array
    {
        $slots = FacilityTimeSlot::where('facility_id', $facility->id)
            ->where('is_active', true)
            ->where('day_of_week', $dayOfWeek)
            ->orderBy('start_time')
            ->get();

        return ReservationAvailability::class ? self::consolidate($slots) : [];
    }

    public static function consolidatePublic(Collection $slots): array
    {
        return self::consolidate($slots);
    }

    private static function consolidate(Collection $slots): array
    {
        $blocks = [];
        $currentStart = null;
        $currentEnd = null;

        foreach ($slots as $slot) {
            $slotStart = Carbon::parse($slot->start_time);
            $slotEnd = Carbon::parse($slot->end_time);

            if ($currentStart === null) {
                $currentStart = $slotStart;
                $currentEnd = $slotEnd;
            } elseif ($slotStart->lte($currentEnd)) {
                $currentEnd = $currentEnd->max($slotEnd);
            } else {
                $blocks[] = ['start' => $currentStart->format('H:i'), 'end' => $currentEnd->format('H:i')];
                $currentStart = $slotStart;
                $currentEnd = $slotEnd;
            }
        }

        if ($currentStart !== null) {
            $blocks[] = ['start' => $currentStart->format('H:i'), 'end' => $currentEnd->format('H:i')];
        }

        return $blocks;
    }

    /**
     * Score how well a venue fits the request. Lower is better.
     *
     * Ranking factors: exact availability for the requested window,
     * capacity fit (prefer the smallest venue that fits), and price.
     */
    public static function matchScore(Facility $facility, array $availability, int $people, float $pricePerHour): array
    {
        $available = (bool) $availability['available'];

        // Capacity fit: 0 = perfect fit (>= people, smallest excess),
        // 1 = too small, 0.5..1 = overkill (more than 4x needed).
        $capacity = (int) $facility->capacity;
        if ($people > $capacity) {
            $fitScore = 1.0;
            $fitLabel = 'too_small';
        } elseif ($capacity > $people * 4) {
            $fitScore = 0.6;
            $fitLabel = 'oversized';
        } elseif ($capacity >= $people * 1.5) {
            $fitScore = 0.2;
            $fitLabel = 'comfortable';
        } else {
            $fitScore = 0.0;
            $fitLabel = 'perfect_fit';
        }

        // Price competitiveness (normalized per hour).
        $priceScore = min(0.2, $pricePerHour / 20000);

        $score = ($available ? 0.0 : 0.9) + $fitScore + $priceScore;

        return [
            'score'     => round($score, 3),
            'fit_label' => $fitLabel,
            'available' => $available,
        ];
    }
}
