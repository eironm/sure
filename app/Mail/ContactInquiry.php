<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Delivers a visitor's landing-page contact form message to the admin.
 */
class ContactInquiry extends Mailable
{
    use Queueable, SerializesModels, BrandedEmail;

    public function __construct(
        public string $visitorName,
        public string $visitorEmail,
        public string $body,
        public string $submittedAt,
    ) {}

    public function envelope(): Envelope
    {
        // Register the CID image embed callback (must be called for Laravel 11 lifecycle)
        $this->withBrandedLogo($this);

        return new Envelope(
            subject: 'New contact form inquiry — ' . $this->visitorName,
            replyTo: [
                new \Illuminate\Mail\Mailables\Address($this->visitorEmail, $this->visitorName),
            ],
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.contact-inquiry');
    }

    /**
     * Attach the brand logo as a CID-embedded image.
     */
    public function build(): static
    {
        return $this->withBrandedLogo($this);
    }
}
