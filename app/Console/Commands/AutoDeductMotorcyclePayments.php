<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Purchase;
use App\Models\MotorcyclePayment;
use App\Models\WalletTransaction;
use Carbon\Carbon;

class AutoDeductMotorcyclePayments extends Command
{
    protected $signature = 'motorcycle:autodeduct';
    protected $description = 'Automatically deduct daily motorcycle payments from rider wallets';

    public function handle()
    {
        $today = Carbon::today()->toDateString();

        $purchases = Purchase::with('user.wallet')
            ->where('status', 'active')
            ->get();

        foreach ($purchases as $purchase) {
            // Skip if already paid today
            if ($purchase->payments()->whereDate('payment_date', $today)->exists()) {
                continue;
            }

            // Get the rider wallet
            $wallet = $purchase->user->wallet;

            // Determine daily amount (assuming UGX 12,000 daily)
            $dailyAmount = 12000;

            if ($wallet && $wallet->balance >= $dailyAmount) {
                // Deduct from wallet
                $wallet->decrement('balance', $dailyAmount);

                // Log wallet transaction
                WalletTransaction::create([
                    'user_id' => $wallet->user_id,
                    'amount' => $dailyAmount,
                    'type' => 'debit',
                    'reason' => 'Daily motorcycle payment',
                    'description' => 'Automated daily motorcycle payment',
                    'reference' => 'AUTO-MOTO-' . uniqid(),
                ]);

                // Record motorcycle payment
                MotorcyclePayment::create([
                    'purchase_id' => $purchase->id,
                    'payment_date' => $today,
                    'amount' => $dailyAmount,
                    'type' => 'daily',
                    'note' => 'Automated daily payment from wallet',
                    'status' => 'paid', // ✅ important: set status explicitly
                ]);

                $purchase->amount_paid += $dailyAmount;
                $purchase->remaining_balance -= $dailyAmount;

                if ($purchase->remaining_balance <= 0) {
                    $purchase->remaining_balance = 0;
                    $purchase->status = 'cleared';
                }

                $purchase->save();

                $this->info("Payment successful for Rider ID {$purchase->user_id}");
            } else {
                $this->warn("Insufficient funds for Rider ID {$purchase->user_id}");
                // Optionally mark as missed payment or notify admin/rider
            }
        }
    }
}
