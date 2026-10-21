<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\FacilityBlackoutDate;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FacilityBlackoutDateController extends Controller
{
    /**
     * Display a listing of blackout dates.
     */
    public function index()
    {
        $blackoutDates = FacilityBlackoutDate::with('facility')->orderBy('start_date', 'desc')->paginate(15);
        $facilities = Facility::where('is_available', true)->get();
        
        return view('admin.settings.blackout-dates', compact('blackoutDates', 'facilities'));
    }

    /**
     * Store a newly created blackout date.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'facility_id' => 'nullable|exists:facilities,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string|max:255',
        ]);

        $blackoutDate = FacilityBlackoutDate::create($validated);

        AuditLog::create([
            'admin_id' => auth()->id(),
            'action' => 'created',
            'target_type' => 'BlackoutDate',
            'target_id' => $blackoutDate->id,
            'details' => "Admin created blackout date: {$blackoutDate->reason} (" . ($blackoutDate->facility_id ? "Facility #{$blackoutDate->facility_id}" : "Global") . ")",
            'ip_address' => $request->ip(),
        ]);

        return redirect()->back()->with('success', 'Blackout date added successfully.');
    }

    /**
     * Remove the specified blackout date.
     */
    public function destroy(FacilityBlackoutDate $blackoutDate)
    {
        $details = "Admin deleted blackout date: {$blackoutDate->reason}";
        $blackoutDate->delete();

        AuditLog::create([
            'admin_id' => auth()->id(),
            'action' => 'deleted',
            'target_type' => 'BlackoutDate',
            'target_id' => $blackoutDate->id,
            'details' => $details,
            'ip_address' => request()->ip(),
        ]);

        return redirect()->back()->with('success', 'Blackout date removed successfully.');
    }
}
