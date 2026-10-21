<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'       => User::count(),
            'superadmins'       => User::where('role', 'superadmin')->count(),
            'admins'            => User::where('role', 'admin')->count(),
            'cashiers'          => User::where('role', 'cashier')->count(),
            'guests'            => User::where('role', 'guest')->count(),
            'inactive_accounts' => User::where('is_active', false)->count(),

            'total_reservations'   => Reservation::count(),
            'pending_reservations' => Reservation::where('status', 'pending')->count(),
            'approved_reservations'=> Reservation::where('status', 'approved')->count(),

            'cash_pending'    => Reservation::where('payment_status', 'cash_pending')->count(),
            'paid'            => Reservation::where('payment_status', 'paid')->count(),
            'total_collected' => Reservation::where('payment_status', 'paid')->sum('total_price'),
        ];

        $recentUsers = User::latest()->take(5)->get();
        $recentReservations = Reservation::with('user', 'facility')->latest()->take(5)->get();

        return view('superadmin.dashboard', compact('stats', 'recentUsers', 'recentReservations'));
    }
}
