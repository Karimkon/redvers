<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatteryDelivery extends Model
{
    protected $fillable = [
        'delivery_code',
        'battery_id',
        'delivered_to_agent_id',
        'station_id',
        'delivered_by',
        'received',
        'received_at',
        'returned_to_admin',
        'returned_at',
        'returned_by_admin_id' // Track admin
    ];

    protected $casts = [
    'received_at' => 'datetime',
    'returned_at' => 'datetime',
];


    public function battery()
    {
        return $this->belongsTo(Battery::class);
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'delivered_to_agent_id');
    }

    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    public function returnedByAdmin()
    {
        return $this->belongsTo(User::class, 'returned_by_admin_id');
    }
}