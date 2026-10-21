<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * A branded Mailable for reservation status notifications.
 *
 * Used by ReservationStatusChanged to send properly branded emails
 * with the logo embedded as a CID image (works in all email clients).
 */
class BrandedReservationMail extends Mailable
{
    use Queueable, SerializesModels, BrandedEmail;

    public Reservation $reservation;
    public string $status;

    /**
     * Template and subject mapping per reservation status.
     */
    protected array $statusMap = [
        'submitted' => ['emails.reservations.reservation-submitted', 'Reservation Submitted — '],
        'approved' => ['emails.reservations.reservation-approved', 'Reservation Approved! — '],
        'denied' => ['emails.reservations.reservation-denied', 'Reservation Denied — '],
        'cancelled' => ['emails.reservations.reservation-cancelled', 'Reservation Cancelled — '],
        'payment_received' => ['emails.reservations.payment-received', 'Payment Confirmed — '],
        'payment_pending' => ['emails.reservations.payment-pending', 'Payment Required — '],
        'cash_pending' => ['emails.reservations.payment-pending', 'Payment Required — '],
        'partially_paid' => ['emails.reservations.payment-pending', 'Remaining Balance Due — '],
        'expired_unpaid_cancelled' => ['emails.reservations.reservation-expired', 'Reservation Expired — '],
    ];

    /**
     * Create a new message instance.
     */
    public function __construct(Reservation $reservation, string $status)
    {
        $this->reservation = $reservation;
        $this->status = $status;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        // Register the CID image embed callback (must be called for Laravel 11 lifecycle)
        $this->withBrandedLogo($this);

        $brand = \App\Services\BrandingService::get('system_name', 'Platform');
        $subjectSuffix = $this->statusMap[$this->status][1] ?? 'Reservation Update — ';
        return new Envelope(
            subject: $subjectSuffix . $this->reservation->booking_reference,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        [$view] = $this->statusMap[$this->status] ?? ['emails.reservations.reservation-submitted', ''];
        return new Content(
            view: $view,
            with: [
                'reservation' => $this->reservation,
            ],
        );
    }

    /**
     * Attach the brand logo as a CID-embedded image.
     */
    public function build(): static
    {
        return $this->withBrandedLogo($this);
    }
}
