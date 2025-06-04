<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MotorcycleUnit extends Model
{
    protected $fillable = [
        'motorcycle_id',
        'number_plate',
        'status',
    ];

    public function motorcycle()
    {
        return $this->belongsTo(Motorcycle::class);
    }

    public function purchase()
    {
        return $this->hasOne(Purchase::class);
    }

}
