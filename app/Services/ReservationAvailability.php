<?php

namespace App\Services;

use App\Models\Facility;
use App\Models\FacilityTimeSlot;
use App\Models\Reservation;
use App\Models\SlotReservation;
use App\Models\FacilityBlackoutDate;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ReservationAvailability
{
    /**
     * Check if a specific date and time range is available for a facility.
     */
    public static function checkAvailability(Facility $facility, $startDate, $endDate, $startTime, $endTime, $excludeReservationId = null): array
    {
        try {
            if ($startDate === $endDate && Carbon::parse($endTime)->lt(Carbon::parse($startTime))) {
                $endDate = Carbon::parse($startDate)->addDay()->toDateString();
            }

            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->startOfDay();
            $startDt = Carbon::parse("$startDate $startTime");
            $endDt = Carbon::parse("$endDate $endTime");

            // 0. Validate inputs before hitting the database
            if ($endDt->lte($startDt)) {
                return [
                    'available' => false,
                    'reason' => 'The end date and time must be after the start date and time.',
                    'status' => 'unavailable'
                ];
            }

            $requestedStart = Carbon::parse($startTime);
            $requestedEnd = Carbon::parse($endTime);
            $isOvernight = $end->gt($start) || $requestedEnd->lte($requestedStart);

            // 1. Check for blackout dates (facility-specific or global)
            $blackout = FacilityBlackoutDate::where(function ($q) use ($facility) {
                    $q->where('facility_id', $facility->id)
                      ->orWhereNull('facility_id');
                })
                ->where('start_date', '<=', $end->toDateString())
                ->where('end_date', '>=', $start->toDateString())
                ->first();

            if ($blackout) {
                return [
                    'available' => false,
                    'reason' => "The selected dates are unavailable due to a blackout or holiday: {$blackout->reason}",
                    'status' => 'unavailable'
                ];
            }

            // 2. Fetch active slots once, grouped by weekday.
            // Used by both the operating-hours check and the capacity check below.
            $slotsByDay = FacilityTimeSlot::where('facility_id', $facility->id)
                ->where('is_active', true)
                ->orderBy('start_time')
                ->get()
                ->groupBy('day_of_week');

            $checkedDays = [];
            foreach (CarbonPeriod::create($start, $end) as $date) {
                if (count($checkedDays) === 7) {
                    break; // Every weekday already verified
                }

                $dayOfWeek = $date->dayOfWeek;
                if (isset($checkedDays[$dayOfWeek])) {
                    continue;
                }
                $checkedDays[$dayOfWeek] = true;

                $slots = $slotsByDay->get($dayOfWeek);
                if ($slots === null || $slots->isEmpty()) {
                    return [
                        'available' => false,
                        'reason' => 'The facility is closed on ' . $date->format('l') . '.',
                        'status' => 'unavailable'
                    ];
                }

                // Standard day schedule within daytime hours must be covered by active blocks.
                // Overnight galas and after-hours events span beyond daytime slots and are subject to admin review.
                if (!$isOvernight && !self::rangeCoveredByBlocks(self::consolidateSlotBlocks($slots), $requestedStart, $requestedEnd)) {
                    return [
                        'available' => false,
                        'reason' => 'The facility is not operating during the selected hours on ' . $date->format('l') . '.',
                        'status' => 'unavailable'
                    ];
                }
            }

            // 4. Overlap conflict check: ensure no active reservation (approved or pending) collides
            $reqStartStr = $startDt->format('Y-m-d H:i:s');
            $reqEndStr   = $endDt->format('Y-m-d H:i:s');

            $concatStart = \Illuminate\Support\Facades\DB::connection()->getDriverName() === 'sqlite'
                ? "start_date || ' ' || start_time"
                : "CONCAT(start_date, ' ', start_time)";
            $concatEnd   = \Illuminate\Support\Facades\DB::connection()->getDriverName() === 'sqlite'
                ? "end_date || ' ' || end_time"
                : "CONCAT(end_date, ' ', end_time)";

            $conflict = Reservation::where('facility_id', $facility->id)
                ->whereIn('status', ['pending', 'approved'])
                ->when($excludeReservationId, fn ($q) => $q->where('id', '!=', $excludeReservationId))
                ->whereRaw("$concatStart < ?", [$reqEndStr])
                ->whereRaw("$concatEnd > ?", [$reqStartStr])
                ->orderBy('status') // 'approved' sorts before 'pending'
                ->first();

            if ($conflict) {
                return self::conflictResponse($conflict->status === 'pending', $startDt, $endDt, $start);
            }

            // 5. Capacity-aware conflict check: a slot with capacity N allows N
            // concurrent bookings. Booked quantities are aggregated per slot per
            // date in a single query, then compared in memory.
            $overlappingSlotsByDay = $slotsByDay->map(
                fn ($slots) => $slots->filter(
                    fn ($slot) => Carbon::parse($slot->start_time)->lt($requestedEnd)
                        && Carbon::parse($slot->end_time)->gt($requestedStart)
                )
            );

            $overlappingSlotIds = $overlappingSlotsByDay->flatten()->pluck('id')->all();

            if (!empty($overlappingSlotIds)) {
                $bookedBySlotDate = SlotReservation::query()
                    ->selectRaw(
                        'slot_reservations.facility_time_slot_id, slot_reservations.reserved_date, '
                        . 'SUM(slot_reservations.quantity_booked) as total_qty, '
                        . "SUM(CASE WHEN reservations.status = 'approved' THEN slot_reservations.quantity_booked ELSE 0 END) as approved_qty"
                    )
                    ->join('reservations', 'reservations.id', '=', 'slot_reservations.reservation_id')
                    ->whereIn('slot_reservations.facility_time_slot_id', $overlappingSlotIds)
                    // whereDate handles both DATE columns (MySQL) and datetime-string storage (SQLite)
                    ->whereDate('slot_reservations.reserved_date', '>=', $start->toDateString())
                    ->whereDate('slot_reservations.reserved_date', '<=', $end->toDateString())
                    ->whereIn('reservations.status', ['pending', 'approved'])
                    ->when($excludeReservationId, fn ($q) => $q->where('reservations.id', '!=', $excludeReservationId))
                    ->groupBy('slot_reservations.facility_time_slot_id', 'slot_reservations.reserved_date')
                    ->get()
                    ->keyBy(fn ($row) => $row->facility_time_slot_id . '|' . Carbon::parse($row->reserved_date)->toDateString());

                if ($bookedBySlotDate->isNotEmpty()) {
                    foreach (CarbonPeriod::create($start, $end) as $date) {
                        foreach ($overlappingSlotsByDay->get($date->dayOfWeek, collect()) as $slot) {
                            $row = $bookedBySlotDate->get($slot->id . '|' . $date->toDateString());
                            if ($row === null) {
                                continue;
                            }

                            $capacity = max(1, (int) $slot->capacity);

                            if ((int) $row->approved_qty >= $capacity) {
                                return self::conflictResponse(false, $requestedStart, $requestedEnd, $date);
                            }

                            if ((int) $row->total_qty >= $capacity) {
                                return self::conflictResponse(true, $requestedStart, $requestedEnd, $date);
                            }
                        }
                    }
                }
            }

            return [
                'available' => true,
                'reason' => 'The selected time slot is available!',
                'status' => 'available'
            ];

        } catch (\Throwable $e) {
            Log::error('Availability Check Error: ' . $e->getMessage(), [
                'facility_id' => $facility->id,
                'exception' => $e,
            ]);

            return [
                'available' => false,
                'reason' => 'An error occurred while checking availability.',
                'status' => 'error'
            ];
        }
    }

    /**
     * Build the standard "blocked" response for a conflicting booking.
     *
     * Slots filled only by pending reservations report status 'pending';
     * slots filled by approved reservations are definitively 'unavailable'.
     */
    private static function conflictResponse(bool $pendingOnly, Carbon $requestedStart, Carbon $requestedEnd, Carbon $date): array
    {
        if ($pendingOnly) {
            return [
                'available' => false,
                'reason' => 'There is a pending reservation for this time slot awaiting approval.',
                'status' => 'pending'
            ];
        }

        return [
            'available' => false,
            'reason' => sprintf(
                'Time slot %s-%s is full on %s',
                $requestedStart->format('H:i'),
                $requestedEnd->format('H:i'),
                $date->format('M d')
            ),
            'status' => 'unavailable'
        ];
    }

    /**
     * Merge overlapping/contiguous time slots into consolidated availability blocks.
     *
     * @param Collection<int, FacilityTimeSlot> $slots Slots ordered by start_time.
     * @return array<int, array{start: Carbon, end: Carbon}>
     */
    private static function consolidateSlotBlocks(Collection $slots): array
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
                // Overlapping or contiguous: extend the current block
                $currentEnd = $currentEnd->max($slotEnd);
            } else {
                // Gap found: close the current block and start a new one
                $blocks[] = ['start' => $currentStart, 'end' => $currentEnd];
                $currentStart = $slotStart;
                $currentEnd = $slotEnd;
            }
        }

        if ($currentStart !== null) {
            $blocks[] = ['start' => $currentStart, 'end' => $currentEnd];
        }

        return $blocks;
    }

    /**
     * Determine whether the requested time range is fully contained in any block.
     *
     * @param array<int, array{start: Carbon, end: Carbon}> $blocks
     */
    private static function rangeCoveredByBlocks(array $blocks, Carbon $requestedStart, Carbon $requestedEnd): bool
    {
        foreach ($blocks as $block) {
            if ($requestedStart->gte($block['start']) && $requestedEnd->lte($block['end'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * Calculate total price for a reservation.
     */
    public static function calculatePrice(Facility $facility, $startDate, $endDate, $startTime, $endTime): float
    {
        if ($startDate === $endDate && Carbon::parse($endTime)->lt(Carbon::parse($startTime))) {
            $endDate = Carbon::parse($startDate)->addDay()->toDateString();
        }

        $startDt = Carbon::parse("$startDate $startTime");
        $endDt   = Carbon::parse("$endDate $endTime");

        // Guard against inverted ranges instead of silently producing a wrong price
        if ($endDt->lte($startDt)) {
            return 0.0;
        }

        // Use minutes across full datetime window to handle partial hours and overnight correctly
        $totalHours = $startDt->diffInMinutes($endDt) / 60;
        $hourlyRate = (float) ($facility->price_per_hour ?? 0);

        return round($hourlyRate * $totalHours, 2);
    }

    /**
     * Build a full quotation breakdown for a reservation.
     *
     * Returns an array with venue cost, arrangement line items, totals,
     * security deposit, and fee-waiver info suitable for both display
     * and storage in the quotation_breakdown JSON column.
     *
     * @param  array  $arrangementSelections  e.g. [['item_id'=>1,'quantity'=>50], ...]
     * @param  bool   $isInternal             Whether the user is internal (for fee waiver)
     */
    public static function calculateQuotation(
        Facility $facility,
        $startDate,
        $endDate,
        $startTime,
        $endTime,
        array $arrangementSelections = [],
        bool $isInternal = false
    ): array {
        if ($startDate === $endDate && Carbon::parse($endTime)->lt(Carbon::parse($startTime))) {
            $endDate = Carbon::parse($startDate)->addDay()->toDateString();
        }

        $venueCost = self::calculatePrice($facility, $startDate, $endDate, $startTime, $endTime);

        // Build venue line item
        $startDt = Carbon::parse("$startDate $startTime");
        $endDt   = Carbon::parse("$endDate $endTime");
        $start   = Carbon::parse($startDate)->startOfDay();
        $end     = Carbon::parse($endDate)->startOfDay();
        $hours   = round(max(0.5, $startDt->diffInMinutes($endDt) / 60), 1);
        $days    = $start->diffInDays($end) + 1;

        $breakdown = [];
        $durationLabel = ($days > 1)
            ? "Venue Rental ({$hours} hrs total · {$days} days)"
            : "Venue Rental ({$hours} hrs)";
        $breakdown[] = [
            'label'  => $durationLabel,
            'amount' => $venueCost,
        ];

        // Build arrangement line items
        $arrangementsDetailed = [];
        $arrangementsTotal = 0;

        if (!empty($arrangementSelections)) {
            $itemIds = array_column($arrangementSelections, 'item_id');
            $items   = \App\Models\LogisticalItem::whereIn('id', $itemIds)->get()->keyBy('id');

            foreach ($arrangementSelections as $sel) {
                $item = $items->get($sel['item_id'] ?? null);
                if (!$item) continue;

                $qty      = min((int) ($sel['quantity'] ?? 1), $item->max_quantity);
                $unitPrice = (float) $item->price;
                $subtotal  = round($unitPrice * $qty, 2);

                $arrangementsDetailed[] = [
                    'item_id'    => $item->id,
                    'name'       => $item->name,
                    'category'   => $item->category,
                    'quantity'   => $qty,
                    'unit_label' => $item->unit_label,
                    'unit_price' => $unitPrice,
                    'subtotal'   => $subtotal,
                ];

                if ($subtotal > 0) {
                    $breakdown[] = [
                        'label'  => "{$item->name} × {$qty}",
                        'amount' => $subtotal,
                    ];
                }

                $arrangementsTotal += $subtotal;
            }
        }

        $subtotal = $venueCost + $arrangementsTotal;

        // Determine fee waiver
        $isWaived = $isInternal
            && (bool) \App\Models\SystemSetting::getValue('internal_fee_waived', '1');

        // Security deposit
        $depositAmount = $isWaived
            ? 0.0
            : (float) \App\Models\SystemSetting::getValue('security_deposit_amount', '10000');

        $grandTotal = $isWaived ? 0.0 : $subtotal;

        return [
            'venue_cost'           => $venueCost,
            'arrangements'         => $arrangementsDetailed,
            'arrangements_total'   => $arrangementsTotal,
            'breakdown'            => $breakdown,
            'subtotal'             => $subtotal,
            'security_deposit'     => $depositAmount,
            'grand_total'          => $grandTotal,
            'is_waived'            => $isWaived,
            'hours_per_day'        => $hours,
            'days'                 => $days,
        ];
    }

    /**
     * Generate a booking reference that is guaranteed unique in the database.
     */
    public static function generateBookingReference(): string
    {
        do {
            $reference = 'RES-' . strtoupper(bin2hex(random_bytes(3))) . '-' . now()->format('mdHi');
        } while (Reservation::where('booking_reference', $reference)->exists());

        return $reference;
    }
}
