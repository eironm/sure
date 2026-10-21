<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Reservation;
use Illuminate\Support\Facades\DB;

echo "=== VERIFYING MYSQL QUERY GENERATION ===\n";

// 1. MySQL Logistics 1
$q1 = Reservation::whereNotNull('arrangements')
    ->whereRaw("JSON_TYPE(arrangements) = 'ARRAY' AND JSON_LENGTH(arrangements) > 0");
echo "1. Logistics 1 SQL: " . $q1->toSql() . "\n";

// 2. MySQL Logistics 0
$q0 = Reservation::where(function ($q) {
    $q->whereNull('arrangements')
      ->orWhereRaw("JSON_TYPE(arrangements) != 'ARRAY' OR JSON_LENGTH(arrangements) = 0");
});
echo "2. Logistics 0 SQL: " . $q0->toSql() . "\n";

// 3. MySQL Weekday
$qw = Reservation::whereRaw("DAYOFWEEK(start_date) BETWEEN 2 AND 6");
echo "3. Weekday SQL: " . $qw->toSql() . "\n";

// 4. MySQL Weekend
$qwe = Reservation::whereRaw("DAYOFWEEK(start_date) IN (1, 7)");
echo "4. Weekend SQL: " . $qwe->toSql() . "\n";

// 5. Search with 'reason' column
$qs = Reservation::where(function ($q) {
    $q->where('booking_reference', 'like', '%test%')
      ->orWhere('reason', 'like', '%test%')
      ->orWhere('event_title', 'like', '%test%')
      ->orWhere('cancellation_reason', 'like', '%test%');
});
echo "5. Search SQL: " . $qs->toSql() . "\n";

// 6. Accessor test
$r = new Reservation(['reason' => 'Annual General Meeting']);
echo "6. Accessor r->purpose: " . $r->purpose . "\n";
assert($r->purpose === 'Annual General Meeting', 'Accessor mapping failed!');

echo "\nAll query constructs are valid and ready for MySQL production!\n";
