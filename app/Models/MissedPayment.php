<?php

// app/Models/MissedPayment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MissedPayment extends Model
{
    protected $fillable = [
        'purchase_id',
        'missed_date',
        'status',
        'note',
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
}
