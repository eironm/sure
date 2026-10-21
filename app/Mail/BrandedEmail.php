<?php

namespace App\Mail;

/**
 * Trait that handles brand branding for Mailables.
 *
 * The logo is now served via a public CDN URL in BrandingService
 * to ensure reliable inline rendering in Gmail without attachments.
 */
trait BrandedEmail
{
    public static string $lastCid = '';

    public static function prepareLogoCid(): string
    {
        return '';
    }

    protected function withBrandedLogo(\Illuminate\Mail\Mailable $mailable): \Illuminate\Mail\Mailable
    {
        // No longer embedding CID parts as we use public URLs for better compatibility
        return $mailable;
    }
}
