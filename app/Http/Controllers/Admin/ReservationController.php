<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\SlotReservation;
use App\Models\FacilityBlackoutDate;
use App\Models\AuditLog;
use App\Notifications\ReservationStatusChanged;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReservationController extends Controller
{
    /**
     * Display a listing of all reservations.
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');

        $query = Reservation::with(['user', 'facility', 'documents']);

        // Status filter
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Text search (requester name / email / event title / reference)
        if ($request->filled('search')) {
            $s = trim($request->get('search'));
            $query->where(function ($q) use ($s) {
                $q->whereHas('user', function ($uq) use ($s) {
                        $uq->where('name', 'like', '%' . $s . '%')
                          ->orWhere('email', 'like', '%' . $s . '%');
                    })
                  ->orWhere('reason', 'like', '%' . $s . '%')
                  ->orWhere('booking_reference', 'like', '%' . $s . '%');
            });
        }

        // Facility filter
        if ($request->filled('facility_id')) {
            $query->where('facility_id', (int) $request->get('facility_id'));
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->where('start_date', '>=', $request->get('date_from'));
        }
        if ($request->filled('date_to')) {
            $query->where('end_date', '<=', $request->get('date_to'));
        }

        // Sorting (whitelist-protected)
        $sortable = ['start_date', 'end_date', 'number_of_people', 'total_price', 'status', 'created_at'];
        $sortBy = in_array($request->get('sort_by'), $sortable, true) ? $request->get('sort_by') : 'start_date';
        $sortDir = strtolower((string) $request->get('sort_dir')) === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortBy, $sortDir);

        // Pagination
        $reservations = $query->paginate($request->integer('per_page', 15), ['*'], 'page')->withQueryString();

        // Facilities list for the filter dropdown
        $facilities = \App\Models\Facility::select('id', 'name')->orderBy('name')->get();

        // Share filter context for the view
        $viewData = [
            'status' => $status,
            'search' => (string) $request->get('search', ''),
            'facility_id' => (string) $request->get('facility_id', ''),
            'date_from' => (string) $request->get('date_from', ''),
            'date_to' => (string) $request->get('date_to', ''),
            'sort_by' => $sortBy,
            'sort_dir' => $sortDir,
            'per_page' => $request->integer('per_page', 15),
        ];

        // ── Quick stats for the dashboard cards (always full scope) ──
        $stats = [
            'total'    => Reservation::count(),
            'pending'  => Reservation::where('status', 'pending')->count(),
            'approved' => Reservation::where('status', 'approved')->count(),
            'denied'   => Reservation::whereIn('status', ['denied', 'cancelled', 'expired_unpaid_cancelled'])->count(),
        ];

        return view('admin.reservations.index', array_merge($viewData, [
            'reservations' => $reservations,
            'facilities'   => $facilities,
            'stats'        => $stats,
        ]));
    }

    /**
     * Return all reservations and blackout dates as FullCalendar-compatible JSON events.
     */
    public function calendarEvents(Request $request): JsonResponse
    {
        $start = $request->get('start') ? Carbon::parse($request->get('start')) : now()->startOfMonth();
        $end   = $request->get('end')   ? Carbon::parse($request->get('end'))   : now()->endOfMonth();

        $events = [];

        // ── Reservations ───────────────────────────────────────────
        $reservations = Reservation::with(['user', 'facility', 'documents'])
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_date', [$start->toDateString(), $end->toDateString()])
                  ->orWhereBetween('end_date',   [$start->toDateString(), $end->toDateString()])
                  ->orWhere(function ($q2) use ($start, $end) {
                      $q2->where('start_date', '<=', $start->toDateString())
                         ->where('end_date',   '>=', $end->toDateString());
                  });
            })
            ->get();

        $statusConfig = [
            'pending'   => ['color' => '#f59e0b', 'textColor' => '#1c1917', 'icon' => '⏳'],
            'approved'  => ['color' => '#10b981', 'textColor' => '#ffffff', 'icon' => '✓'],
            'denied'    => ['color' => '#94a3b8', 'textColor' => '#ffffff', 'icon' => '✕'],
            'cancelled' => ['color' => '#64748b', 'textColor' => '#ffffff', 'icon' => '⊘'],
            'expired_unpaid_cancelled' => ['color' => '#64748b', 'textColor' => '#ffffff', 'icon' => '⊘'],
        ];

        foreach ($reservations as $res) {
            $cfg = $statusConfig[$res->status] ?? ['color' => '#94a3b8', 'textColor' => '#fff', 'icon' => '?'];

            // ── Always emit all-day events ───────────────────────────────────
            // Timed events in dayGridMonth render as tiny "dot" entries that are
            // hard to see and sometimes not clickable. Using allDay blocks ensures
            // every reservation shows as a clearly visible, full-width pill.
            //
            // For single-day events FullCalendar's all-day end is EXCLUSIVE, so
            // we must add 1 day to end_date so the block fills the correct cell.
            $startDay = $res->start_date->format('Y-m-d');
            $endDay   = $res->end_date->copy()->addDay()->format('Y-m-d');

            $events[] = [
                'id'              => 'res-' . $res->id,
                'title'           => $cfg['icon'] . ' ' . ($res->event_title ?: $res->facility->name),
                'start'           => $startDay,
                'end'             => $endDay,
                'allDay'          => true,
                'backgroundColor' => $cfg['color'],
                'borderColor'     => $cfg['color'],
                'textColor'       => $cfg['textColor'],
                'extendedProps'   => [
                    'type'             => 'reservation',
                    'reservationId'    => $res->id,
                    'status'           => $res->status,
                'statusLabel'    => $res->status_label,
                    'userName'         => $res->user->name,
                    'userEmail'        => $res->user->email,
                    'reservedByName'   => $res->reserved_by_name,
                    'reservedByEmail'  => $res->reserved_by_email,
                    'reservedByPhone'  => $res->reserved_by_phone,
                    'eventTitle'       => $res->event_title,
                    'clientTypeLabel'  => $res->client_type_label,
                    'endUserName'      => $res->end_user_name,
                    'endUserType'      => $res->end_user_type,
                    'paymentMethod'    => $res->payment_method,
                    'arrangements'     => $res->arrangements,
                    'facilityName'     => $res->facility->name,
                    'facilityLocation' => $res->facility->location,
                    'startDate'        => $res->start_date->format('M d, Y'),
                    'endDate'          => $res->end_date->format('M d, Y'),
                    'startTime'        => $res->start_time ? $res->start_time->format('g:i A') : null,
                    'endTime'          => $res->end_time   ? $res->end_time->format('g:i A')   : null,
                    'numberOfPeople'   => $res->number_of_people,
                    'reason'           => $res->reason,
                    'adminRemarks'     => $res->admin_remarks,
                    'totalPrice'       => $res->total_price ? '₱' . number_format($res->total_price, 2) : null,
                    'approveUrl'       => route('admin.reservations.approve', $res->id),
                    'denyUrl'          => route('admin.reservations.deny', $res->id),
                    'cancelUrl'        => route('admin.reservations.cancel', $res->id),
                    'documents'        => $res->documents->map(function ($doc) use ($res) {
                        return [
                            'id'             => $doc->id,
                            'original_name'  => $doc->original_name,
                            'file_type'      => $doc->file_type,
                            'file_size'      => $doc->file_size,
                            'formatted_size' => $doc->formatted_size,
                            'file_category'  => $doc->file_category,
                            'icon_class'     => $doc->icon_class,
                            'view_url'       => route('admin.reservations.documents.view', [$res->id, $doc->id]),
                            'download_url'   => route('admin.reservations.documents.download', [$res->id, $doc->id]),
                        ];
                    }),
                ],
            ];
        }

        // ── Blackout Dates ─────────────────────────────────────────
        $blackouts = FacilityBlackoutDate::with('facility')
            ->where(function ($q) use ($start, $end) {
                $q->where('start_date', '<=', $end->toDateString())
                  ->where('end_date',   '>=', $start->toDateString());
            })
            ->get();

        foreach ($blackouts as $blackout) {
            $label = $blackout->facility_id
                ? '🚫 Blackout: ' . ($blackout->facility->name ?? 'Facility')
                : '🚫 Global Blackout';

            // FullCalendar end date for all-day events must be exclusive (+1 day)
            $endDate = $blackout->end_date->copy()->addDay()->format('Y-m-d');

            $events[] = [
                'id'              => 'blackout-' . $blackout->id,
                'title'           => $label,
                'start'           => $blackout->start_date->format('Y-m-d'),
                'end'             => $endDate,
                'allDay'          => true,
                'display'         => 'block',
                'backgroundColor' => '#fecaca',
                'borderColor'     => '#ef4444',
                'textColor'       => '#b91c1c',
                'extendedProps'   => [
                    'type'             => 'blackout',
                    'reason'           => $blackout->reason,
                    'facilityName'     => $blackout->facility_id ? ($blackout->facility->name ?? 'Unknown') : 'All Facilities',
                    'facilityLocation' => $blackout->facility_id ? ($blackout->facility->location ?? '') : '',
                    'isGlobal'         => is_null($blackout->facility_id),
                    'startDate'        => $blackout->start_date->format('M d, Y'),
                    'endDate'          => $blackout->end_date->format('M d, Y'),
                ],
            ];
        }

        return response()->json($events);
    }

    /**
     * Display the specified reservation.
     * When requested as JSON/AJAX, return full reservation data with documents.
     * Otherwise deep-link into the index offcanvas.
     */
    public function show(Request $request, Reservation $reservation)
    {
        if ($request->expectsJson() || $request->ajax()) {
            $reservation->loadMissing(['user', 'facility', 'documents']);
            return response()->json([
                'reservationId'    => $reservation->id,
                'status'           => $reservation->status,
                'statusLabel'      => $reservation->status_label,
                'userName'         => $reservation->user->name,
                'userEmail'        => $reservation->user->email,
                'reservedByName'   => $reservation->reserved_by_name,
                'reservedByEmail'  => $reservation->reserved_by_email,
                'reservedByPhone'  => $reservation->reserved_by_phone,
                'eventTitle'       => $reservation->event_title,
                'clientTypeLabel'  => $reservation->client_type_label,
                'endUserName'      => $reservation->end_user_name,
                'endUserType'      => $reservation->end_user_type,
                'paymentMethod'    => $reservation->payment_method,
                'arrangements'     => $reservation->arrangements,
                'facilityName'     => $reservation->facility->name,
                'facilityLocation' => $reservation->facility->location,
                'startDate'        => $reservation->start_date->format('M d, Y'),
                'endDate'          => $reservation->end_date->format('M d, Y'),
                'startTime'        => $reservation->start_time?->format('g:i A'),
                'endTime'          => $reservation->end_time?->format('g:i A'),
                'numberOfPeople'   => $reservation->number_of_people,
                'reason'           => $reservation->reason,
                'adminRemarks'     => $reservation->admin_remarks,
                'totalPrice'       => $reservation->total_price ? '₱' . number_format($reservation->total_price, 2) : null,
                'approveUrl'       => route('admin.reservations.approve', $reservation->id),
                'denyUrl'          => route('admin.reservations.deny', $reservation->id),
                'cancelUrl'        => route('admin.reservations.cancel', $reservation->id),
                'documents'        => $reservation->documents->map(fn($doc) => [
                    'id'             => $doc->id,
                    'original_name'  => $doc->original_name,
                    'file_type'      => $doc->file_type,
                    'file_size'      => $doc->file_size,
                    'formatted_size' => $doc->formatted_size,
                    'file_category'  => $doc->file_category,
                    'icon_class'     => $doc->icon_class,
                    'view_url'       => route('admin.reservations.documents.view', [$reservation->id, $doc->id]),
                    'download_url'   => route('admin.reservations.documents.download', [$reservation->id, $doc->id]),
                ]),
            ]);
        }

        return redirect()->route('admin.reservations.index', ['open' => $reservation->id]);
    }

    /**
     * Approve the specified reservation.
     */
    public function approve(Request $request, Reservation $reservation)
    {
        $request->validate([
            'admin_remarks' => 'nullable|string|max:1000',
        ]);

        if ($reservation->status !== 'pending') {
            return $this->decisionErrorResponse($request, 'Only pending reservations can be approved.');
        }

        // Guard against approving a booking that would push any slot past capacity
        $reservation->loadMissing('slotReservations.facilityTimeSlot');

        if ($reservation->slotReservations->isNotEmpty()) {
            foreach ($reservation->slotReservations as $slotBooking) {
                $capacity = max(1, (int) $slotBooking->facilityTimeSlot?->capacity);

                $approvedQty = (int) SlotReservation::where('facility_time_slot_id', $slotBooking->facility_time_slot_id)
                    ->whereDate('reserved_date', $slotBooking->reserved_date)
                    ->where('reservation_id', '!=', $reservation->id)
                    ->whereHas('reservation', fn ($q) => $q->where('status', 'approved'))
                    ->sum('quantity_booked');

                if ($approvedQty + (int) $slotBooking->quantity_booked > $capacity) {
                    return $this->decisionErrorResponse($request, sprintf(
                        'Cannot approve: the time slot on %s is already at full capacity with approved reservations.',
                        $slotBooking->reserved_date->format('M d, Y')
                    ));
                }
            }
        } else {
            // Legacy fallback for reservations without slot bookings: treat overlap as exclusive
            $conflict = Reservation::where('facility_id', $reservation->facility_id)
                ->where('id', '!=', $reservation->id)
                ->where('status', 'approved')
                ->whereDate('start_date', '<=', $reservation->end_date)
                ->whereDate('end_date', '>=', $reservation->start_date)
                ->whereTime('start_time', '<', $reservation->end_time->format('H:i:s'))
                ->whereTime('end_time', '>', $reservation->start_time->format('H:i:s'))
                ->exists();

            if ($conflict) {
                return $this->decisionErrorResponse($request, 'Cannot approve: this booking overlaps an already approved reservation for the same facility.');
            }
        }

        $reservation->update([
            'status' => 'approved',
            'admin_remarks' => $request->admin_remarks,
        ]);

        // Log the action
        AuditLog::create([
            'admin_id' => Auth::id(),
            'action' => 'Approved reservation #' . $reservation->id,
            'target_type' => 'Reservation',
            'target_id' => $reservation->id,
            'details' => 'Reservation for ' . $reservation->facility->name . ' by ' . $reservation->user->name,
            'ip_address' => $request->ip(),
        ]);

        // Notify User (never crash an admin decision on mail failure)
        safe_notify($reservation->user, new ReservationStatusChanged($reservation, 'approved'));

        return $this->decisionSuccessResponse($request, 'Reservation approved successfully.');
    }

    private function decisionSuccessResponse(Request $request, string $message)
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return redirect()->route('admin.reservations.index')->with('success', $message);
    }

    private function decisionErrorResponse(Request $request, string $message)
    {
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $message,
            ], 422);
        }

        return back()->with('error', $message);
    }
    /**
     * Deny the specified reservation.
     */
    public function deny(Request $request, Reservation $reservation)
    {
        $request->validate([
            'admin_remarks' => 'required|string|max:1000',
        ]);

        if (!in_array($reservation->status, ['pending', 'approved'])) {
            return $this->decisionErrorResponse($request, 'This reservation can no longer be denied.');
        }

        $reservation->update([
            'status' => 'denied',
            'admin_remarks' => $request->admin_remarks,
        ]);

        // Log the action
        AuditLog::create([
            'admin_id' => Auth::id(),
            'action' => 'Denied reservation #' . $reservation->id,
            'target_type' => 'Reservation',
            'target_id' => $reservation->id,
            'details' => 'Reason: ' . $request->admin_remarks,
            'ip_address' => $request->ip(),
        ]);

        // Notify User (never crash an admin decision on mail failure)
        safe_notify($reservation->user, new ReservationStatusChanged($reservation, 'denied'));

        return $this->decisionSuccessResponse($request, 'Reservation denied.');
    }

    /**
     * Cancel an already-approved reservation.
     */
    public function cancel(Request $request, Reservation $reservation)
    {
        $request->validate([
            'admin_remarks' => 'required|string|max:1000',
        ]);

        if ($reservation->status !== 'approved') {
            return $this->decisionErrorResponse($request, 'Only approved reservations can be cancelled.');
        }

        $reservation->update([
            'status'        => 'cancelled',
            'admin_remarks' => $request->admin_remarks,
        ]);

        AuditLog::create([
            'admin_id'    => Auth::id(),
            'action'      => 'Cancelled reservation #' . $reservation->id,
            'target_type' => 'Reservation',
            'target_id'   => $reservation->id,
            'details'     => 'Cancellation reason: ' . $request->admin_remarks,
            'ip_address'  => $request->ip(),
        ]);

        // Notify the user (never crash an admin decision on mail failure)
        safe_notify($reservation->user, new ReservationStatusChanged($reservation, 'cancelled'));

        return $this->decisionSuccessResponse($request, 'Reservation #' . $reservation->id . ' has been cancelled.');
    }
}
