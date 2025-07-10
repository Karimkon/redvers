<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Carbon;
use App\Models\SwapPromotion;
use Illuminate\Support\Facades\Log;

// 🧠 Just for fun
Artisan::command('inspire', function () {
    $this->comment(\Illuminate\Foundation\Inspiring::quote());
})->purpose('Redvers E- Mobility');

// ✅ Auto-deduct motorcycle payments every minute
Schedule::command('motorcycle:autodeduct')
    ->everyMinute()
    ->withoutOverlapping()
    ->onOneServer();

// 🧹 Delete unpaid promotions older than 30 minutes
Artisan::command('promotions:cleanup-unpaid', function () {
    $cutoff = Carbon::now()->subMinutes(30);

    $count = SwapPromotion::where('status', 'pending')
        ->where('created_at', '<', $cutoff)
        ->delete();

    $this->info("✅ Deleted $count unpaid pending promotions.");
    Log::info("Promotions Cleanup: Deleted $count unpaid promotions older than 30 minutes.");
})->purpose('Delete unpaid pending promotions older than 30 minutes');

// ⏱ Schedule it every 5 minutes
Schedule::command('promotions:cleanup-unpaid')
    ->everyFiveMinutes()
    ->withoutOverlapping()
    ->onOneServer();
