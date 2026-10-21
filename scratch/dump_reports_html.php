<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$admin = App\Models\User::where('role', 'admin')->first() ?: App\Models\User::where('role', 'superadmin')->first();
Auth::login($admin);

$pages = [
    'att1_flat_table.html' => '/admin/reports/print?view_type=table',
    'att2_grouped_facility.html' => '/admin/reports/print?view_type=grouped&group_by=facility',
    'att3_grouped_month.html' => '/admin/reports/print?view_type=grouped&group_by=month',
    'att4_analytics_dashboard.html' => '/admin/reports/print?view_type=analytics',
    'admin_reports_dashboard.html' => '/admin/reports?report_type=reservations',
];

foreach ($pages as $file => $uri) {
    $req = Illuminate\Http\Request::create($uri, 'GET');
    $res = $app->handle($req);
    file_put_contents(__DIR__ . '/' . $file, $res->getContent());
    echo "Wrote {$file} (" . strlen($res->getContent()) . " bytes)\n";
}
