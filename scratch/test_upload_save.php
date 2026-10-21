<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::where('role', 'superadmin')->first();
\Illuminate\Support\Facades\Auth::login($user);

// Create fake uploaded files
$tempLight = tempnam(sys_get_temp_dir(), 'logo_l');
$tempWhite = tempnam(sys_get_temp_dir(), 'logo_w');

// Create a small 100x100 PNG
$img = imagecreatetruecolor(100, 100);
$red = imagecolorallocate($img, 255, 0, 0);
imagefilledellipse($img, 50, 50, 80, 80, $red);
imagepng($img, $tempLight);

$img2 = imagecreatetruecolor(100, 100);
$blue = imagecolorallocate($img2, 0, 0, 255);
imagefilledellipse($img2, 50, 50, 80, 80, $blue);
imagepng($img2, $tempWhite);

$fileLight = new \Illuminate\Http\UploadedFile($tempLight, 'my_seal_light.png', 'image/png', null, true);
$fileWhite = new \Illuminate\Http\UploadedFile($tempWhite, 'my_seal_white.png', 'image/png', null, true);

// Send PUT request to superadmin.settings.system.update
$req = \Illuminate\Http\Request::create(
    '/superadmin/settings/system',
    'PUT',
    [
        '_token' => csrf_token(),
        'settings' => [],
    ],
    [],
    [
        'settings' => [
            'logo_light' => $fileLight,
            'logo_white' => $fileWhite,
        ]
    ]
);

$resp = $app->handle($req);
echo "Status: " . $resp->getStatusCode() . "\n";
if ($resp->isRedirection()) {
    echo "Redirected to: " . $resp->headers->get('Location') . "\n";
}

// Clear BrandingService cache
\App\Services\BrandingService::flush();

$newLight = \App\Models\SystemSetting::getValue('logo_light');
$newWhite = \App\Models\SystemSetting::getValue('logo_white');
echo "DB logo_light: " . $newLight . "\n";
echo "DB logo_white: " . $newWhite . "\n";

echo "File exists on disk (light): " . (file_exists(public_path($newLight)) ? 'YES' : 'NO') . "\n";
echo "File exists on disk (white): " . (file_exists(public_path($newWhite)) ? 'YES' : 'NO') . "\n";

echo "BrandingService::logoForBg('dark'): " . \App\Services\BrandingService::logoForBg('dark') . "\n";
echo "BrandingService::logoForBg('light'): " . \App\Services\BrandingService::logoForBg('light') . "\n";
echo "BrandingService::logoVariant('white'): " . \App\Services\BrandingService::logoVariant('white') . "\n";
echo "BrandingService::logoVariant('light'): " . \App\Services\BrandingService::logoVariant('light') . "\n";
