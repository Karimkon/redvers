<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Swap extends Model
{
    protected $fillable = [
        'rider_id',
        'station_id',
        'agent_id',
        'battery_id', 
        'motorcycle_unit_id',
        'battery_returned_id',
        'percentage_difference',
        'payable_amount',
        'payment_method',
        'swapped_at',
    ];

    protected $casts = [
        'swapped_at' => 'datetime',
    ];

    public function riderUser()
    {
        return $this->belongsTo(User::class, 'rider_id');
    }

    public function rider()
    {
        return $this->riderUser(); // Alias
    }

    public function agentUser()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function batterySwaps()
    {
        return $this->hasMany(BatterySwap::class);
    }

    public function batterySwap()
    {
        return $this->hasOne(BatterySwap::class);
    }

    public function returnedBattery()
    {
        return $this->belongsTo(Battery::class, 'battery_returned_id');
    }

    public function batteryIssued()
    {
        return $this->belongsTo(Battery::class, 'battery_id'); // ✅ now valid
    }

    public function battery()
    {
        return $this->batteryIssued();
    }

    public function motorcycleUnit()
    {
        return $this->belongsTo(\App\Models\MotorcycleUnit::class, 'motorcycle_unit_id');
    }


}
