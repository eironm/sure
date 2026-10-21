<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeRegistration extends Mailable
{
    use SerializesModels, BrandedEmail;

    public User $user;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        // Register the CID image embed callback (must be called for Laravel 11 lifecycle)
        $this->withBrandedLogo($this);

        return new Envelope(
            subject: 'Welcome to ' . \App\Services\BrandingService::get('system_name', 'Platform') . '!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.auth.welcome-registration',
            with: [
                'user' => $this->user,
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
