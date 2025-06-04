<?php

// app/Models/Motorcycle.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Motorcycle extends Model
{
    protected $fillable = [
        'type',
        'cash_price',
        'hire_price_total',
        'daily_payment',
        'weekly_payment',
        'duration_days',
        'number_plate'
    ];

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}
