<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::find(4);
Auth::login($user);

// Check dashboard rendering
$req = Illuminate\Http\Request::create('/guest/dashboard', 'GET');
$response = $app->handle($req);
$content = $response->getContent();

echo "=== Dashboard Check ===\n";
echo "Status: " . $response->getStatusCode() . "\n";
echo "Has cancelBookingModal: " . (str_contains($content, 'id="cancelBookingModal"') ? 'YES' : 'NO') . "\n";
echo "Has data-cancel-booking: " . (str_contains($content, 'data-cancel-booking="true"') ? 'YES' : 'NO') . "\n";
echo "Has window.confirm: " . (str_contains($content, "confirm('Cancel") ? 'YES' : 'NO') . "\n";

// Check history rendering
$reqHistory = Illuminate\Http\Request::create('/guest/history', 'GET');
$responseHistory = $app->handle($reqHistory);
$contentHistory = $responseHistory->getContent();

echo "\n=== History Check ===\n";
echo "Status: " . $responseHistory->getStatusCode() . "\n";
echo "Has cancelBookingModal: " . (str_contains($contentHistory, 'id="cancelBookingModal"') ? 'YES' : 'NO') . "\n";
echo "Has data-cancel-booking: " . (str_contains($contentHistory, 'data-cancel-booking="true"') ? 'YES' : 'NO') . "\n";
echo "Has Cancel Booking button: " . (str_contains($contentHistory, 'Cancel Booking') ? 'YES' : 'NO') . "\n";
echo "Has window.confirm: " . (str_contains($contentHistory, "confirm('Cancel") ? 'YES' : 'NO') . "\n";
