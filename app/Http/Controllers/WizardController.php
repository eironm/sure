<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\FacilityBlackoutDate;
use App\Models\LogisticalItem;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Services\ReservationAvailability;
use App\Services\WizardService;
use App\Services\PaymentDeadlineService;

/**
 * WizardController — JSON endpoints for the redesigned reservation wizard.
 * All endpoints are additive; the classic POST /reserve store() flow keeps
 * full server-side authority for validation, conflict checks, and pricing.
 */
class WizardController extends Controller
{
    /**
     * Facility operating hours for a given date (used by the schedule picker
     * so guests can only pick times the venue is actually open).
     */
    public function facilityHours(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'facility_ids' => 'required|string',
            'date'         => 'required|date_format:Y-m-d',
        ]);

        $ids = array_values(array_filter(array_map('intval', explode(',', $validated['facility_ids']))));
        if (! $ids) {
            return response()->json(['facilities' => []]);
        }

        $dayOfWeek = (int) \Carbon\Carbon::parse($validated['date'])->dayOfWeek;

        $facilities = Facility::where('is_available', true)
            ->whereIn('id', $ids)
            ->with(['timeSlots' => fn ($q) => $q->where('is_active', true)])
            ->get()
            ->map(function ($f) use ($dayOfWeek) {
                $slots = $f->timeSlots->filter(fn ($s) => $s->day_of_week === $dayOfWeek);
                return [
                    'id'   => $f->id,
                    'name' => $f->name,
                    'blocks' => WizardService::class
                        ? \App\Services\WizardService::consolidatePublic($slots->values())
                        : [],
                ];
            });

        return response()->json([
            'date'       => $validated['date'],
            'day_of_week' => $dayOfWeek,
            'facilities' => $facilities,
        ]);
    }

    /**
     * Smart venue matching: given a schedule + attendee count, return every
     * available venue annotated with availability, occupied windows (for the
     * timeline), capacity-fit scoring, and a match rank.
     */
    public function match(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'start_date' => 'required|date_format:Y-m-d',
            'end_date'   => 'required|date_format:Y-m-d|after_or_equal:start_date',
            'start_time' => 'required|date_format:H:i',
            'end_time'   => 'required|date_format:H:i',
            'people'     => 'nullable|integer|min:1',
        ]);

        if ($validated['start_date'] === $validated['end_date'] && \Carbon\Carbon::parse($validated['end_time'])->lt(\Carbon\Carbon::parse($validated['start_time']))) {
            $validated['end_date'] = \Carbon\Carbon::parse($validated['start_date'])->addDay()->toDateString();
        }

        $people     = (int) ($validated['people'] ?? 0);
        $rangeStart = \Carbon\Carbon::parse($validated['start_date']);
        $rangeEnd   = \Carbon\Carbon::parse($validated['end_date']);

        // 1. Global blackout early-out (same semantics as searchFacilities).
        $globalBlackout = FacilityBlackoutDate::whereNull('facility_id')
            ->where('start_date', '<=', $validated['end_date'])
            ->where('end_date', '>=', $validated['start_date'])
            ->first();

        if ($globalBlackout) {
            return response()->json([
                'status'  => 'global_blackout',
                'message' => "The selected dates are unavailable due to a global blackout or holiday: {$globalBlackout->reason}",
            ], 422);
        }

        // 2. Facilities (all bookable — availability is computed per venue so
        //    the UI can show what IS bookable instead of silently hiding).
        $facilities = Facility::where('is_available', true)
            ->with(['images', 'category', 'timeSlots' => fn ($q) => $q->where('is_active', true)])
            ->get();

        $results = $facilities->map(function ($facility) use ($validated, $people, $rangeStart, $rangeEnd) {
            $availability = ReservationAvailability::checkAvailability(
                $facility,
                $validated['start_date'],
                $validated['end_date'],
                $validated['start_time'],
                $validated['end_time']
            );

            $pricePerHour = (float) ($facility->price_per_hour ?? 0);
            $startDt = \Carbon\Carbon::parse($validated['start_date'] . ' ' . $validated['start_time']);
            $endDt   = \Carbon\Carbon::parse($validated['end_date'] . ' ' . $validated['end_time']);
            $hours   = max(0.5, $startDt->diffInMinutes($endDt) / 60);
            $price   = round($pricePerHour * $hours, 2);

            $match = WizardService::matchScore($facility, $availability, $people, $pricePerHour);

            $featured = $facility->images->firstWhere('is_featured', true)
                       ?? $facility->images->first();

            $operatingBlocks = WizardService::operatingBlocks(
                $facility, (int) $rangeStart->dayOfWeek
            );

            return [
                'id'                => $facility->id,
                'name'              => $facility->name,
                'description'       => mb_strimwidth(strip_tags($facility->description ?? ''), 0, 160),
                'location'          => $facility->location,
                'capacity'          => $facility->capacity,
                'price_per_hour'    => $pricePerHour,
                'category'          => $facility->category ? [
                    'id'   => $facility->category->id,
                    'name' => $facility->category->name,
                    'icon' => $facility->category->icon ?? null,
                ] : null,
                'image_url'         => $featured?->url,
                'image_count'       => $facility->images->count(),
                'amenities'         => $facility->amenities ? array_slice(
                    array_filter(array_map('trim', explode(',', $facility->amenities))), 0, 5) : [],
                'available'         => $availability['available'],
                'availability'      => $availability,
                'occupied_windows'  => WizardService::occupiedWindows($facility, $validated['start_date'], $validated['end_date']),
                'operating_blocks'  => $operatingBlocks,
                'estimated_price'   => $price,
                'match_score'       => $match['score'],
                'fit_label'         => $match['fit_label'],
            ];
        })
        ->sortBy([
            // Fully available first, then best fit, then cheapest.
            fn ($a) => ! $a['available'],
            fn ($a) => $a['match_score'],
            fn ($a) => $a['estimated_price'],
        ])
        ->values();

        return response()->json([
            'start_date' => $validated['start_date'],
            'end_date'   => $validated['end_date'],
            'start_time' => $validated['start_time'],
            'end_time'   => $validated['end_time'],
            'people'     => $people,
            'available_count' => $results->where('available', true)->count(),
            'facilities'   => $results,
        ]);
    }

    /**
     * Full quotation breakdown for the review panel (same engine as store()).
     */
    public function quotation(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'facility_id'  => 'required|integer|exists:facilities,id',
            'start_date'   => 'required|date_format:Y-m-d',
            'end_date'     => 'required|date_format:Y-m-d|after_or_equal:start_date',
            'start_time'   => 'required|date_format:H:i',
            'end_time'     => 'required|date_format:H:i',
            'arrangements' => 'nullable',
        ]);

        if ($validated['start_date'] === $validated['end_date'] && \Carbon\Carbon::parse($validated['end_time'])->lt(\Carbon\Carbon::parse($validated['start_time']))) {
            $validated['end_date'] = \Carbon\Carbon::parse($validated['start_date'])->addDay()->toDateString();
        }

        $facility = Facility::findOrFail($validated['facility_id']);

        $selections = [];
        $rawArr = $request->input('arrangements');
        if (!empty($rawArr)) {
            $decoded = is_array($rawArr) ? $rawArr : json_decode($rawArr, true);
            if (is_array($decoded)) {
                // Whitelist: only existing logistical items, bounded quantities.
                $items = LogisticalItem::active()->get()->keyBy('id');
                foreach ($decoded as $sel) {
                    $id = (int) ($sel['item_id'] ?? 0);
                    $item = $items->get($id);
                    if (!$item) {
                        continue;
                    }
                    $selections[] = [
                        'item_id'  => $id,
                        'quantity' => min((int) ($sel['quantity'] ?? 1), (int) $item->max_quantity),
                    ];
                }
            }
        }

        $isInternal = (Auth::user()->client_type ?? 'external') === 'internal';
        $quotation  = ReservationAvailability::calculateQuotation(
            $facility,
            $validated['start_date'],
            $validated['end_date'],
            $validated['start_time'],
            $validated['end_time'],
            $selections,
            $isInternal
        );

        $user = Auth::user();
        $deadlineDays = (int) SystemSetting::getValue('payment_deadline_days', '3');
        $cashEnabled     = (bool) SystemSetting::getValue('payment_method_cash_enabled', '1');
        $paymongoEnabled = (bool) SystemSetting::getValue('payment_method_paymongo_enabled', '1');

        // Availability re-check so the review panel never shows a stale price.
        $availability = ReservationAvailability::checkAvailability(
            $facility,
            $validated['start_date'],
            $validated['end_date'],
            $validated['start_time'],
            $validated['end_time']
        );

        // Human-readable schedule labels for the review panel.
        $sd = \Carbon\Carbon::parse($validated['start_date']);
        $ed = \Carbon\Carbon::parse($validated['end_date']);
        $scheduleLabel = ($sd->isSameDay($ed) ? $sd->format('M. j, Y')
            : $sd->format('M. j') . ' – ' . $ed->format('M. j, Y'))
            . ' · ' . \Carbon\Carbon::parse($validated['start_time'])->format('g:i A')
            . ' – ' . \Carbon\Carbon::parse($validated['end_time'])->format('g:i A');

        return response()->json([
            'available'      => $availability['available'],
            'unavailable_reason' => $availability['available'] ? null : $availability['reason'],
            'schedule_label' => $scheduleLabel,
            'facility'       => [
                'id'   => $facility->id,
                'name' => $facility->name,
            ],
            'client_type'    => $user->client_type ?? 'external',
            'payment_methods' => [
                'cash_enabled'     => $cashEnabled,
                'paymongo_enabled' => $paymongoEnabled,
            ],
            'payment_deadline_days' => $deadlineDays,
            'payment_deadline_basis' => PaymentDeadlineService::basis(),
            'payment_deadline_value' => PaymentDeadlineService::value(),
            'payment_deadline_unit' => PaymentDeadlineService::unit(),
            'quotation'      => $quotation,
        ]);
    }
}
