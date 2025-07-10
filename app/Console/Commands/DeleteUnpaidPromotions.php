<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SwapPromotion;
use Illuminate\Support\Carbon;

class DeleteUnpaidPromotions extends Command
{
    protected $signature = 'promotions:delete-unpaid';

    protected $description = 'Delete pending promotions not paid within 30 minutes';

    public function handle()
    {
        $cutoff = Carbon::now()->subMinutes(30); // Or subHour(1), etc.

        $deleted = SwapPromotion::where('status', 'pending')
            ->where('created_at', '<', $cutoff)
            ->delete();

        $this->info("Deleted $deleted unpaid pending promotions.");
    }
}
