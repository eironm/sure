<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\FacilityTimeSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class FacilityTimeSlotController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of time slots for a facility.
     */
    public function index(Facility $facility)
    {

        $timeSlots = $facility->timeSlots()
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->paginate(20);

        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        return view('admin.facility-time-slots.index', compact('facility', 'timeSlots', 'days'));
    }

    /**
     * Show the form for creating a new time slot.
     */
    public function create(Facility $facility)
    {

        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        return view('admin.facility-time-slots.create', compact('facility', 'days'));
    }

    /**
     * Store a newly created time slot in storage.
     */
    public function store(Request $request, Facility $facility)
    {

        $validated = $request->validate([
            'day_of_week' => 'required|integer|between:0,6',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'capacity' => 'required|integer|min:1',
            'price_per_hour' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        // Check for duplicate time slots
        $existing = FacilityTimeSlot::where('facility_id', $facility->id)
            ->where('day_of_week', $validated['day_of_week'])
            ->where('start_time', $validated['start_time'])
            ->where('end_time', $validated['end_time'])
            ->exists();

        if ($existing) {
            return redirect()->back()->with('error', 'A time slot already exists for this day and time range.');
        }

        $validated['facility_id'] = $facility->id;
        $validated['is_active'] = $request->has('is_active');

        FacilityTimeSlot::create($validated);

        return redirect()->route('admin.facilities.time-slots.index', $facility)
            ->with('success', 'Time slot created successfully.');
    }

    /**
     * Show the form for editing the specified time slot.
     */
    public function edit(Facility $facility, FacilityTimeSlot $timeSlot)
    {

        if ($timeSlot->facility_id !== $facility->id) {
            abort(404);
        }

        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        return view('admin.facility-time-slots.edit', compact('facility', 'timeSlot', 'days'));
    }

    /**
     * Update the specified time slot in storage.
     */
    public function update(Request $request, Facility $facility, FacilityTimeSlot $timeSlot)
    {

        if ($timeSlot->facility_id !== $facility->id) {
            abort(404);
        }

        $validated = $request->validate([
            'day_of_week' => 'required|integer|between:0,6',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'capacity' => 'required|integer|min:1',
            'price_per_hour' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        // Check for duplicate time slots (excluding current)
        $existing = FacilityTimeSlot::where('facility_id', $facility->id)
            ->where('id', '!=', $timeSlot->id)
            ->where('day_of_week', $validated['day_of_week'])
            ->where('start_time', $validated['start_time'])
            ->where('end_time', $validated['end_time'])
            ->exists();

        if ($existing) {
            return redirect()->back()->with('error', 'A time slot already exists for this day and time range.');
        }

        $validated['is_active'] = $request->has('is_active');

        $timeSlot->update($validated);

        return redirect()->route('admin.facilities.time-slots.index', $facility)
            ->with('success', 'Time slot updated successfully.');
    }

    /**
     * Remove the specified time slot from storage.
     */
    public function destroy(Facility $facility, FacilityTimeSlot $timeSlot)
    {

        if ($timeSlot->facility_id !== $facility->id) {
            abort(404);
        }

        // Check if there are any reservations for this slot
        $hasReservations = $timeSlot->slotReservations()->exists();

        if ($hasReservations) {
            return redirect()->back()->with('error', 'Cannot delete time slot with existing reservations.');
        }

        $timeSlot->delete();

        return redirect()->route('admin.facilities.time-slots.index', $facility)
            ->with('success', 'Time slot deleted successfully.');
    }

    /**
     * Bulk create time slots for a facility.
     */
    public function bulkCreate(Facility $facility)
    {

        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        return view('admin.facility-time-slots.bulk-create', compact('facility', 'days'));
    }

    /**
     * Store bulk created time slots.
     */
    public function bulkStore(Request $request, Facility $facility)
    {

        $validated = $request->validate([
            'days' => 'required|array|min:1',
            'days.*' => 'integer|between:0,6',
            'start_hour' => 'required|integer|between:0,23',
            'end_hour' => 'required|integer|between:0,23|gt:start_hour',
            'capacity' => 'required|integer|min:1',
            'price_per_hour' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        try {
            DB::beginTransaction();

            $created = 0;
            $skipped = 0;

            for ($hour = $validated['start_hour']; $hour < $validated['end_hour']; $hour++) {
                $startTime = sprintf('%02d:00:00', $hour);
                $endTime = sprintf('%02d:00:00', $hour + 1);

                foreach ($validated['days'] as $day) {
                    $existing = FacilityTimeSlot::where('facility_id', $facility->id)
                        ->where('day_of_week', $day)
                        ->where('start_time', $startTime)
                        ->where('end_time', $endTime)
                        ->exists();

                    if (!$existing) {
                        FacilityTimeSlot::create([
                            'facility_id' => $facility->id,
                            'day_of_week' => $day,
                            'start_time' => $startTime,
                            'end_time' => $endTime,
                            'capacity' => $validated['capacity'],
                            'price_per_hour' => $validated['price_per_hour'],
                            'is_active' => $validated['is_active'] ?? true,
                        ]);
                        $created++;
                    } else {
                        $skipped++;
                    }
                }
            }

            DB::commit();

            return redirect()->route('admin.facilities.time-slots.index', $facility)
                ->with('success', "Created {$created} time slots. ({$skipped} duplicates skipped)");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create time slots: ' . $e->getMessage());
        }
    }

    /**
     * Toggle time slot active status.
     */
    public function toggle(Facility $facility, FacilityTimeSlot $timeSlot)
    {

        if ($timeSlot->facility_id !== $facility->id) {
            abort(404);
        }

        $timeSlot->update(['is_active' => !$timeSlot->is_active]);

        return redirect()->back()->with('success', 'Time slot status updated successfully.');
    }
}
