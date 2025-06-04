<?php

// app/Models/Discount.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = [
        'purchase_id', 'amount', 'percentage', 'reason',
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
}
