<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$admin = App\Models\User::where('role', 'admin')->first() ?: App\Models\User::where('role', 'superadmin')->first();
Auth::login($admin);

$pages = [
    'web_dashboard.html'     => '/admin/reports?report_type=reservations',
    'web_dispatch.html'      => '/admin/reports?report_type=dispatch',
    'web_cancellations.html' => '/admin/reports?report_type=cancellations',
    'print_dispatch.html'    => '/admin/reports/print?view_type=dispatch',
    'print_cancellations.html' => '/admin/reports/print?view_type=cancellations',
    'print_flat_table.html'  => '/admin/reports/print?view_type=table',
    'print_analytics.html'   => '/admin/reports/print?view_type=analytics',
];

foreach ($pages as $file => $uri) {
    $req = Illuminate\Http\Request::create($uri, 'GET');
    $res = $app->handle($req);
    file_put_contents(__DIR__ . '/' . $file, $res->getContent());
    echo "Wrote {$file} (" . strlen($res->getContent()) . " bytes)\n";
}
