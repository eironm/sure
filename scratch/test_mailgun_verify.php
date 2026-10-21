<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\Mail;

$code = '1ACE43';
$recipients = [
    'eironmendoza00@gmail.com',
    'eironm@proton.me',
];

$html = <<<HTML
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body style="margin:0;padding:0;background:#0f0f0f;font-family:'Helvetica Neue',Arial,sans-serif;">
  <table width="100%" cellpadding="0" cellspacing="0" style="background:#0f0f0f;padding:40px 0;">
    <tr><td align="center">
      <table width="520" cellpadding="0" cellspacing="0" style="background:#1a1a2e;border-radius:16px;overflow:hidden;border:1px solid #2a2a4a;">
        <tr>
          <td style="background:linear-gradient(135deg,#6c47ff,#a855f7);padding:32px 40px;text-align:center;">
            <p style="margin:0;color:#fff;font-size:13px;letter-spacing:3px;text-transform:uppercase;opacity:.8;">SURE Platform</p>
            <h1 style="margin:8px 0 0;color:#fff;font-size:26px;font-weight:700;">Mailgun Delivery Test</h1>
          </td>
        </tr>
        <tr>
          <td style="padding:40px;">
            <p style="color:#a0a0c0;font-size:15px;line-height:1.6;margin:0 0 28px;">
              This is a live delivery test from the <strong style="color:#c4b5fd;">SURE</strong> app to verify the new Mailgun SMTP integration is working correctly.
            </p>
            <p style="color:#a0a0c0;font-size:14px;margin:0 0 12px;">Your verification code is:</p>
            <div style="background:#0f0f1a;border:1px solid #6c47ff;border-radius:12px;padding:24px;text-align:center;margin-bottom:28px;">
              <span style="font-size:42px;font-weight:800;letter-spacing:12px;color:#c4b5fd;font-family:'Courier New',monospace;">{$code}</span>
            </div>
            <p style="color:#606080;font-size:13px;line-height:1.5;margin:0;">
              If you received this email, Mailgun SMTP is delivering successfully to this inbox. 🎉<br>
              Report the code back to confirm.
            </p>
          </td>
        </tr>
        <tr>
          <td style="background:#111128;padding:20px 40px;border-top:1px solid #2a2a4a;text-align:center;">
            <p style="color:#404060;font-size:12px;margin:0;">Sent via Mailgun SMTP · no-reply@prod.qzz.io · SURE Internal Test</p>
          </td>
        </tr>
      </table>
    </td></tr>
  </table>
</body>
</html>
HTML;

$results = [];

foreach ($recipients as $email) {
    try {
        Mail::html($html, function ($message) use ($email, $code) {
            $message->to($email)
                    ->subject("🔐 SURE Mailgun Test — Your code: {$code}");
        });
        $results[$email] = 'SUCCESS ✅';
    } catch (\Exception $e) {
        $results[$email] = 'FAILED ❌ — ' . $e->getMessage();
    }
}

echo "\n=== Mailgun Delivery Test Results ===\n";
foreach ($results as $email => $status) {
    echo "{$email}: {$status}\n";
}
echo "\nCode sent: {$code}\n";
