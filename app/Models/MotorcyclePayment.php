<?php

// app/Models/MotorcyclePayment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MotorcyclePayment extends Model
{
    protected $fillable = [
        'purchase_id',
        'payment_date',
        'amount',
        'type',
        'note',
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
}

