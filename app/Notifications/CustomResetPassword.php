<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class CustomResetPassword extends ResetPassword
{
    /**
     * Build the mail representation of the notification.
     *
     * Uses our branded Blade template instead of the default Laravel one.
     */
    public function toMail($notifiable): MailMessage
    {
        $resetUrl = $this->resetUrl($notifiable);

        return (new MailMessage)
            ->subject('Reset Your ' . \App\Services\BrandingService::get('system_name', 'Platform') . ' Password')
            ->view('emails.auth.reset-password', [
                'resetUrl' => $resetUrl,
            ]);
    }
}
