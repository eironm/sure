<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Facility;
use App\Models\Reservation;
use App\Models\AuditLog;
use App\Models\FacilityCategory;
use App\Models\FacilityTag;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index()
    {
        // User Statistics (Only keep totals for standard metrics)
        $totalUsers = User::count();
        $adminCount = User::where('role', 'admin')->count();
        $guestCount = User::where('role', 'guest')->count();

        // Facility Statistics
        $totalFacilities = Facility::count();

        // Reservation Statistics
        $totalReservations = Reservation::count();
        $pendingReservations = Reservation::where('status', 'pending')->count();
        $approvedReservations = Reservation::where('status', 'approved')->count();
        $deniedReservations = Reservation::where('status', 'denied')->count();
        
        // Critical View Data
        $recentReservations = Reservation::with(['user', 'facility'])
                                         ->latest()
                                         ->take(5)
                                         ->get();

        // Upcoming Operations (Today and Future)
        $upcomingReservations = Reservation::with(['user', 'facility'])
            ->where('status', 'approved')
            ->whereDate('start_date', '>=', now()->toDateString())
            ->orderBy('start_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->take(8)
            ->get();

        // Category & Tag Statistics
        $totalCategories = FacilityCategory::count();
        $totalTags = FacilityTag::count();

        // Audit Logs (Optional for Quick View)
        $recentLogs = AuditLog::with('admin')->latest()->take(5)->get();

        /* ── Business metrics ─────────────────────────────────── */

        // Revenue: collected amounts (excludes cancelled)
        $revenueThisMonth = (float) Reservation::where('status', '!=', 'cancelled')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount_paid');

        $revenueTotal = (float) Reservation::where('status', '!=', 'cancelled')
            ->sum('amount_paid');

        // Outstanding: cash/digital payments still owed
        $outstandingPayments = Reservation::whereIn('payment_status', ['pending', 'cash_pending', 'partially_paid'])
            ->where('status', '!=', 'cancelled')
            ->get(['total_price', 'amount_paid']);
        $outstandingValue = (float) $outstandingPayments->sum(fn ($r) => max(0, ($r->total_price ?? 0) - ($r->amount_paid ?? 0)));
        $outstandingCount = $outstandingPayments->count();

        // Approval rate (decided requests only)
        $decided      = $approvedReservations + $deniedReservations;
        $approvalRate = $decided > 0 ? round(($approvedReservations / $decided) * 100) : null;

        // 14-day booking trend for the CSS bar chart
        $trendRaw = Reservation::selectRaw("date(created_at) as day, count(*) as total")
            ->where('created_at', '>=', now()->subDays(13)->startOfDay())
            ->groupBy('day')
            ->pluck('total', 'day');

        $bookingTrend = collect(range(13, 0))->map(function ($i) use ($trendRaw) {
            $day = now()->subDays($i);
            return [
                'label' => $day->format('M j'),
                'short' => $day->format('D'),
                'count' => (int) ($trendRaw[$day->toDateString()] ?? 0),
            ];
        });
        $trendMax = max(1, $bookingTrend->max('count'));

        // Facility utilization: most-booked facilities (last 30 days)
        $topFacilities = Reservation::selectRaw('facility_id, count(*) as total')
            ->with('facility:id,name')
            ->where('created_at', '>=', now()->subDays(30))
            ->where('status', '!=', 'cancelled')
            ->groupBy('facility_id')
            ->orderByDesc('total')
            ->take(5)
            ->get();
        $topFacilityMax = max(1, (int) ($topFacilities->max('total') ?? 0));

        $cancelledReservations = Reservation::whereIn('status', ['cancelled', 'expired_unpaid_cancelled'])->count();

        // Status breakdown for analytics Donut Chart
        $statusDistribution = [
            'approved'  => $approvedReservations,
            'pending'   => $pendingReservations,
            'denied'    => $deniedReservations,
            'cancelled' => $cancelledReservations,
        ];

        // Formatted chart arrays for Chart.js
        $chartLabels = $bookingTrend->pluck('label')->all();
        $chartData   = $bookingTrend->pluck('count')->all();

        // Mini calendar metrics for current month
        $calendarBookingDays = Reservation::whereYear('start_date', now()->year)
            ->whereMonth('start_date', now()->month)
            ->where('status', '!=', 'cancelled')
            ->pluck('start_date')
            ->map(fn ($d) => (int) \Carbon\Carbon::parse($d)->format('j'))
            ->unique()
            ->values()
            ->all();
        $daysInMonth = now()->daysInMonth;
        $firstDayOfWeek = now()->startOfMonth()->dayOfWeek; // 0 for Sunday

        // Daily dynamic greeting logic inside controller
        $hour = now()->format('H');
        $greeting = 'Good evening';
        if ($hour < 12) {
            $greeting = 'Good morning';
        } elseif ($hour < 17) {
            $greeting = 'Good afternoon';
        }

        return view('admin.dashboard', compact(
            'totalUsers',
            'adminCount',
            'guestCount',
            'totalFacilities',
            'totalReservations',
            'pendingReservations',
            'approvedReservations',
            'deniedReservations',
            'cancelledReservations',
            'statusDistribution',
            'chartLabels',
            'chartData',
            'calendarBookingDays',
            'daysInMonth',
            'firstDayOfWeek',
            'recentReservations',
            'upcomingReservations',
            'totalCategories',
            'totalTags',
            'recentLogs',
            'greeting',
            'revenueThisMonth',
            'revenueTotal',
            'outstandingValue',
            'outstandingCount',
            'approvalRate',
            'bookingTrend',
            'trendMax',
            'topFacilities',
            'topFacilityMax'
        ));
    }
}
