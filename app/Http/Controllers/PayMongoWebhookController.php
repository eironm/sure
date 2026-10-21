<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\User;
use App\Notifications\ReservationStatusChanged;
use App\Services\PayMongoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

/**
 * Handles PayMongo webhook events for payment confirmation.
 *
 * When a checkout.session.payment.paid event is received,
 * the reservation's payment status is updated and admins are notified.
 */
class PayMongoWebhookController extends Controller
{
    protected PayMongoService $paymongo;

    public function __construct(PayMongoService $paymongo)
    {
        $this->paymongo = $paymongo;
    }

    /**
     * Handle incoming PayMongo webhook events.
     */
    public function handle(Request $request)
    {
        $payload   = $request->getContent();
        $signature = $request->header('Paymongo-Signature', '');

        // Verify webhook signature
        if (!$this->paymongo->verifyWebhookSignature($payload, $signature)) {
            Log::warning('[PayMongo Webhook] Invalid signature received.');
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        $event = $request->json('data.attributes');
        $type  = $event['type'] ?? null;

        Log::info('[PayMongo Webhook] Event received', ['type' => $type]);

        switch ($type) {
            case 'checkout_session.payment.paid':
                return $this->handlePaymentPaid($event);

            case 'payment.failed':
                return $this->handlePaymentFailed($event);

            default:
                Log::info('[PayMongo Webhook] Unhandled event type', ['type' => $type]);
                return response()->json(['status' => 'ignored'], 200);
        }
    }

    /**
     * Process successful payment.
     */
    protected function handlePaymentPaid(array $event)
    {
        $checkoutData = $event['data'] ?? [];
        $attributes   = $checkoutData['attributes'] ?? [];
        $metadata     = $attributes['metadata'] ?? [];
        $reference    = $metadata['booking_reference'] ?? ($attributes['reference_number'] ?? null);

        if (!$reference) {
            Log::warning('[PayMongo Webhook] Payment paid but no booking reference found.', $event);
            return response()->json(['status' => 'no_reference'], 200);
        }

        $reservation = Reservation::where('booking_reference', $reference)->first();

        if (!$reservation) {
            Log::warning('[PayMongo Webhook] No reservation found for reference', ['reference' => $reference]);
            return response()->json(['status' => 'not_found'], 200);
        }

        // Idempotency: webhooks may be delivered more than once — never re-process
        if ($reservation->payment_status === 'paid') {
            return response()->json(['status' => 'already_paid'], 200);
        }

        // Update payment status
        $reservation->update([
            'payment_status' => 'paid',
            'payment_id'     => $checkoutData['id'] ?? null,
        ]);

        Log::info('[PayMongo Webhook] Reservation payment marked as paid', [
            'reservation_id'    => $reservation->id,
            'booking_reference' => $reference,
        ]);

        // Notify the user (never let a mail failure break the payment webhook)
        safe_notify($reservation->user, new ReservationStatusChanged($reservation, 'payment_received'));

        // Notify admins (mail/broadcast failures must never block a
        // payment webhook that has already marked the reservation paid)
        try {
            $admins = User::where('role', 'admin')
                ->where('id', '!=', $reservation->user_id)
                ->get();

            if ($admins->isNotEmpty()) {
                Notification::send($admins, new ReservationStatusChanged($reservation, 'payment_received'));
            }
        } catch (\Exception $e) {
            Log::warning('[PayMongo Webhook] Admin payment notification failed', [
                'error'             => $e->getMessage(),
                'reservation_id'    => $reservation->id,
                'booking_reference' => $reference,
            ]);
        }

        return response()->json(['status' => 'success'], 200);
    }

    /**
     * Process failed payment.
     */
    protected function handlePaymentFailed(array $event)
    {
        $metadata  = $event['data']['attributes']['metadata'] ?? [];
        $reference = $metadata['booking_reference'] ?? null;

        if ($reference) {
            $reservation = Reservation::where('booking_reference', $reference)->first();
            if ($reservation) {
                $reservation->update(['payment_status' => 'failed']);
                Log::info('[PayMongo Webhook] Payment failed for reservation', ['reference' => $reference]);
            }
        }

        return response()->json(['status' => 'recorded'], 200);
    }
}
