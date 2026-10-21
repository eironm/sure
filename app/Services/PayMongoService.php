<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * PayMongo Payment Gateway Service
 *
 * Abstracts all PayMongo API interactions for creating payment links,
 * checkout sessions, and verifying webhook signatures.
 *
 * @see https://developers.paymongo.com/reference
 */
class PayMongoService
{
    protected string $baseUrl = 'https://api.paymongo.com/v1';
    protected string $secretKey;
    protected string $publicKey;

    public function __construct()
    {
        $this->secretKey = config('services.paymongo.secret_key', env('PAYMONGO_SECRET_KEY', ''));
        $this->publicKey = config('services.paymongo.public_key', env('PAYMONGO_PUBLIC_KEY', ''));
    }

    /**
     * Create a PayMongo Checkout Session for a reservation.
     *
     * @param array $data [
     *     'amount'      => int (in centavos, e.g., 50000 = ₱500.00),
     *     'description' => string,
     *     'remarks'     => string,
     *     'reference'   => string (booking_reference),
     *     'success_url' => string,
     *     'cancel_url'  => string,
     * ]
     * @return array ['checkout_url' => string, 'checkout_session_id' => string]
     */
    public function createCheckoutSession(array $data): array
    {
        $payload = [
            'data' => [
                'attributes' => [
                    'send_email_receipt' => true,
                    'show_description'   => true,
                    'show_line_items'    => true,
                    'cancel_url'         => $data['cancel_url'] ?? url('/'),
                    'success_url'        => $data['success_url'] ?? url('/'),
                    'description'        => $data['description'] ?? 'Facility Reservation',
                    'line_items' => [
                        [
                            'currency'    => 'PHP',
                            // Round before casting: a float like 123409.99999 must not truncate to 123409
                            'amount'      => (int) round($data['amount']),
                            'name'        => $data['description'] ?? 'Facility Reservation',
                            'description' => $data['remarks'] ?? '',
                            'quantity'    => 1,
                        ],
                    ],
                    'payment_method_types' => [
                        'gcash',
                        'grab_pay',
                        'paymaya',
                        'card',
                        'dob',
                        'dob_ubp',
                    ],
                    'reference_number' => $data['reference'] ?? null,
                    'metadata' => [
                        'booking_reference' => $data['reference'] ?? null,
                    ],
                ],
            ],
        ];

        try {
            $response = Http::withBasicAuth($this->secretKey, '')
                ->post("{$this->baseUrl}/checkout_sessions", $payload);

            if ($response->successful()) {
                $session = $response->json('data');
                return [
                    'success'             => true,
                    'checkout_url'        => $session['attributes']['checkout_url'],
                    'checkout_session_id' => $session['id'],
                ];
            }

            Log::error('[PayMongo] Checkout session creation failed', [
                'status' => $response->status(),
                'body'   => $response->json(),
            ]);

            return [
                'success' => false,
                'error'   => $response->json('errors.0.detail', 'Payment session creation failed.'),
            ];
        } catch (\Exception $e) {
            Log::error('[PayMongo] Exception during checkout session creation', [
                'message' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error'   => 'Unable to connect to payment provider. Please try again later.',
            ];
        }
    }

    /**
     * Retrieve a checkout session by ID.
     */
    public function getCheckoutSession(string $sessionId): ?array
    {
        try {
            $response = Http::withBasicAuth($this->secretKey, '')
                ->get("{$this->baseUrl}/checkout_sessions/{$sessionId}");

            if ($response->successful()) {
                return $response->json('data');
            }

            return null;
        } catch (\Exception $e) {
            Log::error('[PayMongo] Failed to retrieve checkout session', [
                'session_id' => $sessionId,
                'message'    => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Verify a PayMongo webhook signature.
     */
    public function verifyWebhookSignature(string $payload, string $signature): bool
    {
        $webhookSecret = config('services.paymongo.webhook_secret', env('PAYMONGO_WEBHOOK_SECRET', ''));

        if (empty($webhookSecret)) {
            if (app()->environment('production')) {
                // Fail closed in production: never accept unsigned webhooks
                Log::error('[PayMongo] Webhook secret not configured in production. Rejecting webhook.');
                return false;
            }

            Log::warning('[PayMongo] Webhook secret not configured. Skipping signature verification (non-production only).');
            return true;
        }

        // PayMongo uses HMAC-SHA256 for webhook verification
        // Signature format: t=<timestamp>,li=<digest1>,te=<digest2>
        $parts = [];
        foreach (explode(',', $signature) as $part) {
            if (!str_contains($part, '=')) {
                continue; // Skip malformed segments instead of throwing
            }
            [$key, $value] = explode('=', $part, 2);
            $parts[$key] = $value;
        }

        // Live-mode events sign with 'li', test-mode with 'te' — accept whichever is present
        $providedDigest = $parts['li'] ?? $parts['te'] ?? null;

        if (!isset($parts['t']) || $providedDigest === null) {
            return false;
        }

        $expectedSignature = hash_hmac('sha256', "{$parts['t']}.{$payload}", $webhookSecret);
        return hash_equals($expectedSignature, $providedDigest);
    }

    /**
     * Check if PayMongo is properly configured.
     */
    public function isConfigured(): bool
    {
        return !empty($this->secretKey) && !empty($this->publicKey);
    }
}
