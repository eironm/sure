<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Ensure all required Laravel storage directories exist.
// On Wasmer Edge the persistent volume starts empty on first boot,
// so we create them here before the framework boots.
foreach ([
    __DIR__.'/../storage/framework/views',
    __DIR__.'/../storage/framework/cache/data',
    __DIR__.'/../storage/framework/sessions',
    __DIR__.'/../storage/logs',
    __DIR__.'/../storage/app/public',
    __DIR__.'/uploads/avatars',
    __DIR__.'/uploads/branding',
    __DIR__.'/uploads/facilities',
    __DIR__.'/uploads/homepage',
    __DIR__.'/uploads/reservation_documents',
] as $_dir) {
    if (!is_dir($_dir)) {
        mkdir($_dir, 0775, true);
    }
}
unset($_dir);

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
