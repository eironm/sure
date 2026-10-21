<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * E-mails the 6-digit verification code a user must enter before login.
 */
class VerificationCodeMail extends Mailable
{
    use Queueable, SerializesModels, BrandedEmail;

    public string $code;
    public int $minutes;

    /**
     * Create a new message instance.
     */
    public function __construct(public User $user, string $code, int $minutes = 10)
    {
        $this->code = $code;
        $this->minutes = $minutes;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        // Register the CID image embed callback (must be called for Laravel 11 lifecycle)
        $this->withBrandedLogo($this);

        $brand = \App\Services\BrandingService::get('system_name', 'Platform');
        return new Envelope(
            subject: $this->user->client_type === 'internal'
                ? 'Your ' . $brand . ' Verification Code & Account Approval Status'
                : 'Your ' . $brand . ' Verification Code — Complete Your Registration',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.auth.verification-code',
            with: [
                'user'    => $this->user,
                'code'    => $this->code,
                'minutes' => $this->minutes,
            ],
        );
    }

    /**
     * Prepare the message for sending — attach the brand logo as a CID image.
     */
    public function build(): static
    {
        return $this->withBrandedLogo($this);
    }
}
