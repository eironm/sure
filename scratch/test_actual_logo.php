<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

\App\Models\SystemSetting::setValue('logo_white', 'uploads/branding/test-upload-white.png');
\App\Models\SystemSetting::setValue('logo_light', 'uploads/branding/test-upload-logo.png');
\App\Services\BrandingService::flush();

echo "logoForBg('dark'): " . \App\Services\BrandingService::logoForBg('dark') . "\n";
echo "logoForBg('light'): " . \App\Services\BrandingService::logoForBg('light') . "\n";

$user = \App\Models\User::where('role', 'superadmin')->first();
\Illuminate\Support\Facades\Auth::login($user);

$req = \Illuminate\Http\Request::create('/superadmin/settings/logo', 'GET');
$resp = $app->handle($req);
$html = $resp->getContent();

if (preg_match('/<div class="brand-wrapper">.*?<\/div>/s', $html, $m)) {
    echo "Brand Wrapper:\n" . $m[0] . "\n\n";
}
