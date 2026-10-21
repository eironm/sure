<?php

namespace App\Http\Middleware;

use App\Models\Reservation;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Notifications\ReservationStatusChanged;

/**
 * Lazily cancels the authenticated user's own expired unpaid reservations
 * on every page load — no cron process required.
 */
class CheckPaymentDeadlines
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            // Cancel only the current user's expired reservations.
            // This is fast (indexed query) and runs inline.
            Reservation::where('user_id', Auth::id())
                ->expiredUnpaid()
                ->each(function (Reservation $reservation) {
                    // Set BOTH status AND payment_status so the scope never re-matches
                    // this record on future page loads, preventing endless notifications.
                    $reservation->update([
                        'status'         => 'expired_unpaid_cancelled',
                        'payment_status' => 'expired',
                    ]);
                    safe_notify($reservation->user, new ReservationStatusChanged($reservation, 'expired_unpaid_cancelled'));
                });
        }

        return $next($request);
    }
}
