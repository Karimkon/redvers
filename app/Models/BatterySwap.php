<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatterySwap extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'battery_id',
        'swap_id',
        'from_station_id',
        'to_station_id',
        'swapped_at',
    ];

     protected $casts = [
        'swapped_at' => 'datetime',
    ];

    public function battery()
    {
        return $this->belongsTo(Battery::class);
    }
    
    public function swap()
    {
        return $this->belongsTo(Swap::class);
    }

    public function fromStation()
    {
        return $this->belongsTo(Station::class, 'from_station_id');
    }

    public function toStation()
    {
        return $this->belongsTo(Station::class, 'to_station_id');
    }

    /**
     * Get the rider directly (if you add rider_id column)
     * This would be simpler than going through swap->riderUser
     */
    public function rider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rider_id');
    }

    /**
     * Scope to get swaps for a specific battery
     */
    public function scopeForBattery($query, $batteryId)
    {
        return $query->where('battery_id', $batteryId);
    }

    /**
     * Scope to get recent swaps
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('swapped_at', '>=', now()->subDays($days));
    }
}

