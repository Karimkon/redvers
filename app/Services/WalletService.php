<?php

namespace App\Services;


use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WalletService
{
    public function credit(User $user, float $amount, string $reason, ?string $ref=null, ?string $desc=null): void
    {
        DB::transaction(function() use ($user,$amount,$reason,$ref,$desc) {
            $wallet = $user->wallet()->lockForUpdate()->firstOrCreate([]);
            $wallet->increment('balance', $amount);

            $wallet->logs()->create([
                'amount' =>  $amount,
                'reason' =>  $reason,
                'reference' => $ref,
                'description'=> $desc,
            ]);
        });
    }

    public function debitIfEnough(User $user, float $amount, string $reason, ?string $ref=null, ?string $desc=null): bool
    {
        return DB::transaction(function() use ($user,$amount,$reason,$ref,$desc){
            $wallet = $user->wallet()->lockForUpdate()->first();
            if(!$wallet || $wallet->balance < $amount) return false;

            $wallet->decrement('balance', $amount);

            $wallet->logs()->create([
                'amount' => -$amount,
                'reason' => $reason,
                'reference'=>$ref,
                'description'=>$desc
            ]);
            return true;
        });
    }
}