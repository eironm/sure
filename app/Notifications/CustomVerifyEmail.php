<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class CustomVerifyEmail extends VerifyEmail
{
    /**
     * Build the mail representation of the notification.
     *
     * Uses our branded Blade template instead of the default Laravel one.
     */
    public function toMail($notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verify Your Email — ' . \App\Services\BrandingService::get('system_name', 'Platform'))
            ->view('emails.auth.verify-email', [
                'verificationUrl' => $verificationUrl,
            ]);
    }
}
