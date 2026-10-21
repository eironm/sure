<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\FacilityBlackoutDate;
use App\Models\PencilBooking;
use App\Models\Reservation;
use App\Models\SystemSetting;
use App\Services\PaymentDeadlineService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuestController extends Controller
{
    /**
     * Accent colors for facility dots (rotated per facility).
     */
    private const DOT_COLORS = ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ef4444', '#06b6d4'];

    /**
     * Display the guest dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        $now = now();
        $today = $now->toDateString();
        $currentTime = $now->format('H:i:s');

        $activeReservation = Reservation::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->whereDate('end_date', '>=', $today)
            ->with('facility')
            ->orderByRaw("CASE status WHEN 'approved' THEN 1 ELSE 2 END, start_date ASC, start_time ASC")
            ->first();

        $pendingPayment = Reservation::where('user_id', $user->id)
            ->whereIn('payment_status', ['pending', 'cash_pending', 'partially_paid'])
            ->where('status', '!=', 'cancelled')
            ->whereNotNull('payment_deadline')
            ->where('payment_deadline', '>', $now)
            ->orderBy('payment_deadline')
            ->with('facility')
            ->first();

        $upcomingReservations = Reservation::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->where(function ($q) use ($today, $currentTime) {
                $q->whereDate('start_date', '>', $today)
                    ->orWhere(function ($q2) use ($today, $currentTime) {
                        $q2->whereDate('start_date', '=', $today)
                            ->where('start_time', '>', $currentTime);
                    });
            })
            ->with('facility')
            ->orderBy('start_date')
            ->orderBy('start_time')
            ->take(5)
            ->get();

        $myPencils = \App\Models\PencilBooking::where('user_id', $user->id)
            ->active()
            ->with('facility')
            ->orderBy('expires_at')
            ->take(5)
            ->get();

        $notifications = $user->notifications()->take(5)->get();

        $allowMultipleAdvanceReservations = (bool) \App\Models\SystemSetting::getValue('allow_multiple_advance_reservations', '1');

        $statTotal = Reservation::where('user_id', $user->id)->count();
        $statUpcoming = Reservation::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'approved'])
            ->whereDate('start_date', '>=', $today)
            ->count();
        $statCompleted = Reservation::where('user_id', $user->id)->where('status', 'completed')->count();
        $statSpent = Reservation::where('user_id', $user->id)
            ->where('status', 'completed')
            ->sum('amount_paid');

        return view('guest.dashboard', compact(
            'user',
            'statTotal', 'statUpcoming', 'statCompleted', 'statSpent',
            'activeReservation', 'pendingPayment', 'upcomingReservations',
            'myPencils', 'notifications', 'allowMultipleAdvanceReservations',
            'today', 'currentTime'
        ));
    }

    /**
     * Show the reservation form with available facilities.
     */
    public function showReserve()
    {
        $user = Auth::user();
        $allowMultipleAdvanceReservations = (bool) SystemSetting::getValue('allow_multiple_advance_reservations', '0');

        if (!$allowMultipleAdvanceReservations) {
            $now = now();
            $today = $now->toDateString();
            $currentTime = $now->format('H:i:s');

            // Check if user has a pending or active reservation
            $currentReservation = Reservation::where('user_id', $user->id)
                ->whereIn('status', ['pending', 'approved'])
                ->where(function($query) use ($today, $currentTime) {
                    $query->where('end_date', '>', $today)
                          ->orWhere(function($q) use ($today, $currentTime) {
                              $q->where('end_date', $today)
                                ->where('end_time', '>', $currentTime);
                          });
                })
                ->exists();

            if ($currentReservation) {
                return redirect()->route('dashboard')->with('error', 'You already have a pending or active reservation.');
            }
        }

        // Fetch all available facilities with their images
        $facilities = Facility::with('images')
            ->where('is_available', true)
            ->get();

        // Logistical items grouped by category for Step 2
        $logisticalItems = \App\Models\LogisticalItem::active()->ordered()->get()->groupBy('category');

        // Reservation policy settings for the wizard
        $reservationSettings = [
            'security_deposit_amount'           => (float) \App\Models\SystemSetting::getValue('security_deposit_amount', '10000'),
            'payment_deadline_days'             => (int) \App\Models\SystemSetting::getValue('payment_deadline_days', '3'),
            'payment_deadline_basis'            => \App\Services\PaymentDeadlineService::basis(),
            'payment_deadline_value'            => \App\Services\PaymentDeadlineService::value(),
            'payment_deadline_unit'             => \App\Services\PaymentDeadlineService::unit(),
            'payment_earliest_date'             => \App\Services\PaymentDeadlineService::earliestEligibleDate()->toDateString(),
            'internal_fee_waived'               => (bool) \App\Models\SystemSetting::getValue('internal_fee_waived', '1'),
            'require_endorsement_internal'      => (bool) \App\Models\SystemSetting::getValue('require_endorsement_internal', '1'),
            'cancellation_policy_text'          => \App\Models\SystemSetting::getValue('cancellation_policy_text', ''),
            'allow_multiple_advance_reservations' => $allowMultipleAdvanceReservations,
            // Whitelabel payment method toggles
            'payment_method_cash_enabled'       => (bool) \App\Models\SystemSetting::getValue('payment_method_cash_enabled', '1'),
            'payment_method_paymongo_enabled'   => (bool) \App\Models\SystemSetting::getValue('payment_method_paymongo_enabled', '1'),
            // Dynamic CMS settings
            'reserve_hero_title'                => \App\Models\SystemSetting::getValue('reserve_hero_title', 'Reserve a Venue'),
            'reserve_hero_subheading'           => \App\Models\SystemSetting::getValue('reserve_hero_subheading', 'Find the perfect campus space for your event — availability checked in real time.'),
            'legal_terms'                       => \App\Models\SystemSetting::getValue('legal_terms', ''),
            'system_name'                       => \App\Models\SystemSetting::getValue('system_name', 'SURE'),
        ];

        // User's client type for conditional rendering
        $clientType = $user->client_type ?? null;

        return view('guest.reserve', compact(
            'facilities',
            'logisticalItems',
            'reservationSettings',
            'clientType'
        ));
    }

    /**
     * Show reservation history.
     */
    public function history()
    {
        $query = Reservation::where('user_id', Auth::id())->with(['facility', 'documents']);

        // Optional status filter from query string (All when omitted or invalid)
        $filter = request()->get('status');
        $allowedFilters = ['pending', 'approved', 'denied', 'cancelled', 'expired_unpaid_cancelled', 'paid', 'partially_paid', 'cash_pending', 'expired'];
        if (in_array($filter, $allowedFilters, true)) {
            if (in_array($filter, ['pending', 'approved', 'denied', 'cancelled', 'expired_unpaid_cancelled'])) {
                $query->where('status', $filter);
            } else {
                // Payment-status filters apply only to active reservations
                $query->where('status', 'approved')->where('payment_status', $filter);
            }
        }

        $reservations = $query->orderBy('created_at', 'desc')->paginate(10);

        // Total count across ALL statuses (unchanged by the current filter)
        $totalCount = Reservation::where('user_id', Auth::id())->count();

        return view('guest.history', compact('reservations', 'filter', 'totalCount'));
    }

        /**
     * Display the Campus Board — live transparency feed.
     *
     * The heart of this page is "What's Happening" — a public,
     * guestbook-style feed of approved campus activity grouped by
     * day (live now, today, next week, beyond), plus the availability
     * planner and facility openness for actual reservation work.
     */
    public function facilityBoard()
    {
        $today = Carbon::today();
        $now   = Carbon::now();

        // ── All approved reservations, today and onward ──────────
        $approved = Reservation::with(['user', 'facility'])
            ->where('status', 'approved')
            ->whereDate('end_date', '>=', $today)
            ->orderBy('start_date')
            ->orderBy('start_time')
            ->get();

        // ── All active facilities ────────────────────────────────
        $allFacilities = Facility::where('is_available', true)->get();

        // Assign a stable color to each facility
        $facilityColorMap = [];
        $allFacilities->each(function ($f, $idx) use (&$facilityColorMap) {
            $facilityColorMap[$f->id] = self::DOT_COLORS[$idx % count(self::DOT_COLORS)];
        });

        // ── Reservations spanning today (for live status + stats) ──
        $todayReservations = $approved->filter(function ($r) use ($today) {
            return $r->start_date->lte($today) && $r->end_date->gte($today);
        });

        // Reservations happening RIGHT NOW
        $activeNow = $todayReservations->filter(function ($r) use ($now) {
            $start = Carbon::parse($r->start_date->format('Y-m-d') . ' ' . Carbon::parse($r->start_time)->format('H:i'));
            $end   = Carbon::parse($r->end_date->format('Y-m-d')   . ' ' . Carbon::parse($r->end_time)->format('H:i'));
            return $now->between($start, $end);
        });

        $activeFacilityIds = $activeNow->pluck('facility_id')->unique();
        $activeByFacility = $activeNow->keyBy('facility_id');

        // ── Build event cards for the transparency feed ──────────
        $buildEvent = function ($r) use ($facilityColorMap) {
            return [
                'title'       => $r->event_title ?: ($r->reason ?: 'Campus Event'),
                'requester'   => $r->reserved_by_name ?: ($r->user->name ?? 'Unknown'),
                'facility'    => $r->facility->name,
                'facility_id' => $r->facility_id,
                'dot_color'   => $facilityColorMap[$r->facility_id] ?? '#94a3b8',
                'participants'=> $r->number_of_people ?? null,
                'start_date'  => $r->start_date->format('Y-m-d'),
                'end_date'    => $r->end_date->format('Y-m-d'),
                'start_time'  => Carbon::parse($r->start_time)->format('g:i A'),
                'end_time'    => Carbon::parse($r->end_time)->format('g:i A'),
                'multi_day'   => $r->start_date->format('Y-m-d') !== $r->end_date->format('Y-m-d'),
                'res_id'      => $r->booking_reference ?? ('RES-' . $r->id),
            ];
        };

        // ── Group approved events by date (feed sections) ────────
        $feedSections = [];
        $multiDayHandled = [];

        foreach ($approved as $r) {
            $dateStr = $r->start_date->format('Y-m-d');
            // Multi-day events attach to the first day only (flagged).
            if ($r->start_date->format('Y-m-d') !== $r->end_date->format('Y-m-d')) {
                $key = 'multi:' . $r->id;
                if (isset($multiDayHandled[$key])) {
                    continue;
                }
                $multiDayHandled[$key] = true;
            }
            if (!isset($feedSections[$dateStr])) {
                $feedSections[$dateStr] = collect();
            }
            $feedSections[$dateStr]->push($buildEvent($r));
        }
        ksort($feedSections);

        // ── Facility openness chips ──────────────────────────────
        $facilities = $allFacilities->map(function ($f) use ($activeFacilityIds, $facilityColorMap, $activeByFacility) {
            $isOccupied = $activeFacilityIds->contains($f->id);
            $currentEvent = null;
            if ($isOccupied && isset($activeByFacility[$f->id])) {
                $activeRes = $activeByFacility[$f->id];
                $currentEvent = $activeRes->event_title ?: ($activeRes->reason ?: null);
            }
            return [
                'name'          => $f->name,
                'occupied'      => $isOccupied,
                'dot_color'     => $facilityColorMap[$f->id] ?? '#94a3b8',
                'current_event' => $currentEvent,
            ];
        })->toArray();

        // ── Stats (reframed) ─────────────────────────────────────
        $upcoming7dEnd = $today->copy()->addWeek()->endOfDay();
        $stats = [
            'happening_today' => $todayReservations->count(),
            'live_now'        => $activeNow->count(),
            'upcoming_7d'     => $approved->filter(function ($r) use ($today, $upcoming7dEnd) {
                return $r->start_date->gt($today) && $r->start_date->lte($upcoming7dEnd);
            })->count(),
            'available'       => ($allFacilities->count() - $activeFacilityIds->count()) . '/' . $allFacilities->count(),
        ];

        // ── Availability planner (next 14 days) — unchanged logic ──
        $rangeStart = PaymentDeadlineService::earliestEligibleDate($now);
        $rangeEnd   = $today->copy()->addDays(13);

        $days = collect(range(0, 13))->map(fn ($i) => $rangeStart->copy()->addDays($i));

        $windowReservations = Reservation::whereIn('status', ['approved', 'pending'])
            ->whereDate('start_date', '<=', $rangeEnd)
            ->whereDate('end_date', '>=', $rangeStart)
            ->get(['facility_id', 'start_date', 'end_date', 'status']);

        $blackouts = FacilityBlackoutDate::whereDate('start_date', '<=', $rangeEnd)
            ->whereDate('end_date', '>=', $rangeStart)
            ->get(['facility_id', 'start_date', 'end_date']);

        $pencils = PencilBooking::active()
            ->whereBetween('date', [$rangeStart->toDateString(), $rangeEnd->toDateString()])
            ->get(['id', 'user_id', 'facility_id', 'date']);

        $userId = Auth::id();

        $availability = $allFacilities->map(function ($f) use ($days, $windowReservations, $blackouts, $pencils, $facilityColorMap, $userId) {
            $cells = $days->map(function ($day) use ($f, $windowReservations, $blackouts, $pencils, $userId) {
                $dateStr = $day->toDateString();

                $isBlackout = $blackouts->first(fn ($b) =>
                    $b->facility_id === $f->id
                    && $b->start_date->toDateString() <= $dateStr
                    && $b->end_date->toDateString() >= $dateStr);

                $reserved = $windowReservations->first(fn ($r) =>
                    $r->facility_id === $f->id
                    && $r->start_date->toDateString() <= $dateStr
                    && $r->end_date->toDateString() >= $dateStr);

                $dayPencils = $pencils->filter(fn ($p) =>
                    $p->facility_id === $f->id && $p->date->toDateString() === $dateStr);

                $mine = $dayPencils->firstWhere('user_id', $userId);

                $status = 'free';
                if ($isBlackout) {
                    $status = 'blackout';
                } elseif ($reserved) {
                    $status = $reserved->status === 'approved' ? 'booked' : 'pending';
                } elseif ($dayPencils->isNotEmpty()) {
                    $status = 'penciled';
                }

                return [
                    'date'         => $dateStr,
                    'date_label'   => \Carbon\Carbon::parse($dateStr)->format('D, M. j, Y'),
                    'status'       => $status,
                    'pencil_count' => $dayPencils->count(),
                    'my_pencil_id' => $mine?->id,
                ];
            })->values()->toArray();

            return [
                'id'        => $f->id,
                'name'      => $f->name,
                'dot_color' => $facilityColorMap[$f->id] ?? '#94a3b8',
                'cells'     => $cells,
            ];
        })->values()->toArray();

        $myPencils = PencilBooking::active()
            ->with('facility:id,name')
            ->where('user_id', $userId)
            ->orderBy('date')
            ->get();

        // ── Recent activity ledger (transparency, no sensitive detail) ──
        $recentActivity = Reservation::with(['user', 'facility:id,name'])
            ->where('status', 'approved')
            ->orderByDesc('created_at')
            ->take(8)
            ->get()
            ->map(function ($r) use ($facilityColorMap) {
                $when = Carbon::parse($r->created_at);
                return [
                    'title'      => $r->event_title ?: ($r->reason ?: 'A reservation'),
                    'facility'   => $r->facility->name ?? 'A facility',
                    'dot_color'  => $facilityColorMap[$r->facility_id] ?? '#94a3b8',
                    'date_label' => Carbon::parse($r->start_date)->format('M j'),
                    'when'       => $when->diffForHumans(now(), true) . ' ago',
                    'timestamp'  => $r->created_at->toISOString(),
                ];
            })
            ->sortByDesc('timestamp')
            ->values()
            ->toArray();

        return view('guest.facility-board', compact(
            'feedSections', 'today', 'now', 'facilities', 'stats',
            'availability', 'days', 'myPencils', 'recentActivity',
            'facilityColorMap'
        ));
    }      /**
     * Place a pencil hold (tentative, non-binding) on a facility date.
     */
    public function pencilBook(Request $request)
    {
        $validated = $request->validate([
            'facility_id' => ['required', 'exists:facilities,id'],
            'date'        => ['required', 'date', 'after_or_equal:today', 'before_or_equal:' . now()->addDays(13)->toDateString()],
            'note'        => ['nullable', 'string', 'max:255'],
        ]);

        // Block dates already firmly reserved or blacked out
        $taken = Reservation::whereIn('status', ['approved', 'pending'])
            ->where('facility_id', $validated['facility_id'])
            ->whereDate('start_date', '<=', $validated['date'])
            ->whereDate('end_date', '>=', $validated['date'])
            ->exists();

        $blackout = FacilityBlackoutDate::where('facility_id', $validated['facility_id'])
            ->whereDate('start_date', '<=', $validated['date'])
            ->whereDate('end_date', '>=', $validated['date'])
            ->exists();

        if ($taken || $blackout) {
            return back()->with('error', 'That date is no longer available for a pencil hold.');
        }

        $existing = PencilBooking::active()
            ->where('user_id', Auth::id())
            ->where('facility_id', $validated['facility_id'])
            ->whereDate('date', $validated['date'])
            ->exists();

        if ($existing) {
            return back()->with('error', 'You already have a pencil hold on that facility and date.');
        }

        PencilBooking::updateOrCreate(
            [
                'user_id'     => Auth::id(),
                'facility_id' => $validated['facility_id'],
                'date'        => $validated['date'],
            ],
            [
                'note'       => !empty(trim((string)($validated['note'] ?? ''))) ? trim($validated['note']) : null,
                'expires_at' => now()->addDays(PencilBooking::HOLD_DAYS),
            ]
        );

        return back()->with('success', 'Pencil hold placed! It expires in ' . PencilBooking::HOLD_DAYS . ' days — convert it to a real reservation before then.');
    }

    /**
     * Remove one of the user's own pencil holds.
     */
    public function pencilCancel(PencilBooking $pencilBooking)
    {
        abort_unless($pencilBooking->user_id === Auth::id(), 403);

        $pencilBooking->delete();

        return back()->with('success', 'Pencil hold removed.');
    }
}

