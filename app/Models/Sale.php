<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
     protected $fillable = [
        'part_id',
        'quantity',
        'selling_price',
        'total_price',
        'customer_name',
        'sold_at',
    ];

    public function part()
    {
        return $this->belongsTo(Part::class);
    }
}
