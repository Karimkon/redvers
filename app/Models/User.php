<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'role', 'station_id', 'nin_number', 'profile_photo', 'id_front', 'id_back'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }

    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    public function swaps()
    {
        return $this->hasMany(Swap::class, 'rider_id');
    }

    public function purchases()
    {
        return $this->hasMany(\App\Models\Purchase::class);
    }

    public function payments()
    {
        return $this->hasMany(\App\Models\Payment::class, 'rider_id');
    }

    public function allPayments()
    {
        return $this->hasManyThrough(
            \App\Models\Payment::class,     // Final model
            \App\Models\Swap::class,        // Intermediate
            'rider_id',                     // Foreign key on swaps
            'swap_id',                      // Foreign key on payments
            'id',                           // Local key on users
            'id'                            // Local key on swaps
        );
    }

    public function swapPromotions()
    {
        return $this->hasMany(SwapPromotion::class, 'rider_id');
    }

    public function shop()
    {
        return $this->hasOne(Shop::class, 'user_id');
    }


    public function wallet()
    { 
        return $this->hasOne(Wallet::class); 
    }
    
    public function walletLogs()
    { 
        return $this->hasMany(WalletTransaction::class); 
    }


}

