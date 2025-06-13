<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Battery extends Model
{
    protected $fillable = ['serial_number', 'status', 'current_station_id', 'current_rider_id'];

    public function currentStation()
    {
        return $this->belongsTo(Station::class, 'current_station_id');
    }

    public function batterySwaps()
    {
        return $this->hasMany(BatterySwap::class);
    }

    public function currentRider()
    {
        return $this->belongsTo(User::class, 'current_rider_id');
    }

    public function deliveries()
    {
        return $this->hasMany(BatteryDelivery::class);
    }

    public function latestDelivery()
    {
        return $this->hasOne(BatteryDelivery::class)->latestOfMany();
    }



}
