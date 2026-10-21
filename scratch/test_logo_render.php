<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::where('role', 'superadmin')->first();
\Illuminate\Support\Facades\Auth::login($user);

$req = \Illuminate\Http\Request::create('/superadmin/settings/logo', 'GET');
$resp = $app->handle($req);
echo "Status: " . $resp->getStatusCode() . "\n";
$html = $resp->getContent();

// Find brand-wrapper
if (preg_match('/<div class="brand-wrapper">.*?<\/div>/s', $html, $m)) {
    echo "Brand Wrapper:\n" . $m[0] . "\n\n";
} else {
    echo "Brand Wrapper not found!\n";
}

// Find lv-preview
if (preg_match_all('/<div class="lv-preview".*?<\/div>/s', $html, $matches)) {
    echo "Previews:\n";
    foreach ($matches[0] as $m) {
        echo $m . "\n";
    }
}

// Find CMS labels
if (preg_match_all('/<span class="file-label".*?<\/span>/s', $html, $matches)) {
    echo "File Labels:\n";
    foreach ($matches[0] as $m) {
        echo $m . "\n";
    }
}
