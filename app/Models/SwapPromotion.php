<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class SwapPromotion extends Model
{
    protected $fillable = [
        'rider_id', 'agent_id', 'starts_at', 'ends_at', 'amount',
        'payment_reference', 'status',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'amount' => 'decimal:2',
    ];
    
    public function rider()
    {
        return $this->belongsTo(User::class, 'rider_id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function isActive()
    {
        return $this->status === 'active' && now()->between(Carbon::parse($this->starts_at), Carbon::parse($this->ends_at));
    }
}
