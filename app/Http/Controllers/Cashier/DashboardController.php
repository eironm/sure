<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Reservation;

class DashboardController extends Controller
{
    public function index()
    {
        $pendingCash = Reservation::where('payment_status', 'cash_pending')
            ->where('status', '!=', 'cancelled')
            ->whereNotNull('payment_deadline')
            ->where('payment_deadline', '>', now())
            ->with('user', 'facility')
            ->latest()
            ->take(5)
            ->get();

        $todayCollected = Reservation::where('payment_status', 'paid')
            ->whereDate('cashier_signed_at', today())
            ->sum('total_price');

        $todayCount = Reservation::where('payment_status', 'paid')
            ->whereDate('cashier_signed_at', today())
            ->count();

        $totalPendingCount = Reservation::where('payment_status', 'cash_pending')
            ->where('status', '!=', 'cancelled')
            ->whereNotNull('payment_deadline')
            ->where('payment_deadline', '>', now())
            ->count();

        return view('cashier.dashboard', compact(
            'pendingCash', 'todayCollected', 'todayCount', 'totalPendingCount'
        ));
    }
}
