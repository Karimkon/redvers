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
}

