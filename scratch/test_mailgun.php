<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Mail;

echo "Testing Mailgun SMTP connection...\n";
echo "Mailer: " . config('mail.default') . "\n";
echo "Host: " . config('mail.mailers.smtp.host') . "\n";
echo "Port: " . config('mail.mailers.smtp.port') . "\n";
echo "Username: " . config('mail.mailers.smtp.username') . "\n";
echo "From: " . config('mail.from.address') . "\n\n";

try {
    Mail::raw(
        'Mailgun SMTP test from SURE app — migration from Resend successful! Sent via no-reply@prod.qzz.io.',
        function ($message) {
            $message->to('eironmendoza00@gmail.com')
                    ->subject('✅ Mailgun SMTP Test — SURE App');
        }
    );
    echo "SUCCESS: Email sent via Mailgun SMTP!\n";
} catch (\Exception $e) {
    echo "FAILED: " . $e->getMessage() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
