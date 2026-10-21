<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('queue:work --stop-when-empty')->everyMinute()->withoutOverlapping();

// Clean up facility gallery drafts abandoned on the Create screen.
Schedule::command('facilities:prune-draft-images')->dailyAt('03:30')->withoutOverlapping();
