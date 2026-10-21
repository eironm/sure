<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\ReservationAvailability;

class CalendarController extends Controller
{
    /**
     * Search facilities by date range with optional filters.
     */
    public function searchFacilities(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'category_id' => 'nullable|integer|exists:facility_categories,id',
            'min_capacity' => 'nullable|integer|min:1',
            'max_price' => 'nullable|numeric|min:0',
        ]);

        if ($validated['start_date'] === $validated['end_date'] && \Carbon\Carbon::parse($validated['end_time'])->lt(\Carbon\Carbon::parse($validated['start_time']))) {
            $validated['end_date'] = \Carbon\Carbon::parse($validated['start_date'])->addDay()->toDateString();
        }

        // 1. Check for Global Blackout (facility_id is null)
        $globalBlackout = \App\Models\FacilityBlackoutDate::whereNull('facility_id')
            ->where('start_date', '<=', $validated['end_date'])
            ->where('end_date', '>=', $validated['start_date'])
            ->first();

        if ($globalBlackout) {
            return response()->json([
                'status' => 'global_blackout',
                'message' => "The selected dates are unavailable due to a global blackout or holiday: {$globalBlackout->reason}",
            ], 422);
        }

        // 2. Query Facilities
        $query = Facility::query()->where('is_available', true);

        if ($request->filled('category_id')) {
            $query->where('category_id', $validated['category_id']);
        }

        if ($request->filled('min_capacity')) {
            $query->where('capacity', '>=', $validated['min_capacity']);
        }

        if ($request->filled('max_price')) {
            $query->where('price_per_hour', '<=', $validated['max_price']);
        }

        $facilities = $query->with(['images', 'category'])->get();

        // 3. Map availability for each facility
        $results = $facilities->map(function ($facility) use ($validated) {
            $availability = ReservationAvailability::checkAvailability(
                $facility,
                $validated['start_date'],
                $validated['end_date'],
                $validated['start_time'],
                $validated['end_time']
            );

            // Resolve the display image URL via the ->url accessor,
            // which handles both new uploads (image_path) and legacy seeded (image_url) records.
            $featuredImage = $facility->images->firstWhere('is_featured', true)
                          ?? $facility->images->first();
            $resolvedImageUrl = $featuredImage?->url;

            return [
                'id' => $facility->id,
                'name' => $facility->name,
                'location' => $facility->location,
                'capacity' => $facility->capacity,
                'price_per_hour' => $facility->price_per_hour,
                'category' => $facility->category ? $facility->category->name : null,
                'image_url' => $resolvedImageUrl,
                'featured_image_url' => $resolvedImageUrl,
                'is_available' => $availability['available'],
                'unavailable_reason' => $availability['available'] ? null : $availability['reason'],
                'status' => $availability['status']
            ];
        });

        return response()->json([
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'total_results' => $results->count(),
            'facilities' => $results,
        ]);
    }

    /**
     * Check availability for a specific facility and time range.
     */
    public function checkAvailability(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'facility_id' => 'required|exists:facilities,id',
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
        ]);

        if ($validated['start_date'] === $validated['end_date'] && \Carbon\Carbon::parse($validated['end_time'])->lt(\Carbon\Carbon::parse($validated['start_time']))) {
            $validated['end_date'] = \Carbon\Carbon::parse($validated['start_date'])->addDay()->toDateString();
        }

        $facility = Facility::findOrFail($validated['facility_id']);
        
        $availability = ReservationAvailability::checkAvailability(
            $facility,
            $validated['start_date'],
            $validated['end_date'],
            $validated['start_time'],
            $validated['end_time']
        );

        $price = ReservationAvailability::calculatePrice(
            $facility,
            $validated['start_date'],
            $validated['end_date'],
            $validated['start_time'],
            $validated['end_time']
        );

        return response()->json(array_merge($availability, [
            'total_price' => number_format($price, 2)
        ]));
    }

    /**
     * Check for global blackout dates and return details.
     */
    public function checkGlobalBlackout(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date',
        ]);

        // Find global blackout (facility_id is null) that overlaps with the requested dates
        $globalBlackout = \App\Models\FacilityBlackoutDate::whereNull('facility_id')
            ->where('start_date', '<=', $validated['end_date'])
            ->where('end_date', '>=', $validated['start_date'])
            ->first();

        if ($globalBlackout) {
            return response()->json([
                'start_date' => $globalBlackout->start_date->format('Y-m-d'),
                'end_date' => $globalBlackout->end_date->format('Y-m-d'),
                'reason' => $globalBlackout->reason,
            ]);
        }

        return response()->json([], 404);
    }

    /**
     * Calculate price for a specific facility and time range.
     */
    public function calculatePrice(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'facility_id' => 'required|exists:facilities,id',
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
        ]);

        $facility = Facility::findOrFail($validated['facility_id']);
        
        $price = ReservationAvailability::calculatePrice(
            $facility,
            $validated['start_date'],
            $validated['end_date'],
            $validated['start_time'],
            $validated['end_time']
        );

        return response()->json([
            'total_price' => number_format($price, 2)
        ]);
    }

    /**
     * Calculate a full quotation breakdown including arrangements and deposit.
     */
    public function calculateQuotation(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'facility_id'              => 'required|exists:facilities,id',
            'start_date'               => 'required|date_format:Y-m-d',
            'end_date'                 => 'required|date_format:Y-m-d',
            'start_time'               => 'required|date_format:H:i',
            'end_time'                 => 'required|date_format:H:i',
            'arrangements'             => 'nullable|array',
            'arrangements.*.item_id'   => 'required_with:arrangements|integer|exists:logistical_items,id',
            'arrangements.*.quantity'  => 'required_with:arrangements|integer|min:1',
        ]);

        $facility    = Facility::findOrFail($validated['facility_id']);
        $user        = $request->user();
        $isInternal  = ($user->client_type ?? null) === 'internal';

        $quotation = ReservationAvailability::calculateQuotation(
            $facility,
            $validated['start_date'],
            $validated['end_date'],
            $validated['start_time'],
            $validated['end_time'],
            $validated['arrangements'] ?? [],
            $isInternal
        );

        return response()->json($quotation);
    }
    
    // ... rest of the methods remain unchanged
}

