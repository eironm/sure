<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$f = Illuminate\Http\UploadedFile::fake()->image('test.png');
$req = Illuminate\Http\Request::create('/test', 'PUT', ['settings' => ['foo' => 'bar']], [], ['settings' => ['logo_white' => $f]]);
print_r($req->input('settings'));
print_r($req->allFiles());
