<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

/**
 * Handles global expiry of unpaid reservations triggered by a
 * shared-hosting cron URL call (no artisan schedule:work needed).
 *
 * Add this to cPanel crons:
 *   curl -s "https://yourdomain.com/internal/expire-reservations?token=YOUR_CRON_SECRET" > /dev/null
 */
class InternalCronController extends Controller
{
    public function handle(Request $request)
    {
        $secret = config('app.cron_secret');
        $token  = (string) ($request->header('X-Cron-Token') ?: $request->query('token', ''));

        // Timing-safe comparison; fail closed when no secret is configured.
        if (!$secret || !hash_equals((string) $secret, $token)) {
            abort(403, 'Unauthorized');
        }

        $cancelled = 0;

        Reservation::expiredUnpaid()
            ->each(function (Reservation $reservation) use (&$cancelled) {
                $reservation->update([
                    'status'         => 'expired_unpaid_cancelled',
                    'payment_status' => 'expired',
                ]);
                safe_notify($reservation->user, new \App\Notifications\ReservationStatusChanged($reservation, 'expired_unpaid_cancelled'));
                $cancelled++;
            });

        return response()->json([
            'status'    => 'ok',
            'cancelled' => $cancelled,
            'timestamp' => now()->toISOString(),
        ]);
    }
}
