<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ✅ Register your daily motorcycle payment deduction command
Schedule::command('motorcycle:autodeduct')
    ->dailyAt('00:05')
    ->withoutOverlapping()
    ->onOneServer();