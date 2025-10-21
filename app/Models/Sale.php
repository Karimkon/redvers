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
        'cost_price',
        'customer_name',
        'payment_method',
        'sold_at',
    ];

    public function part()
    {
        return $this->belongsTo(Part::class);
    }
    public function getProfitAttribute()
    {
        return ($this->selling_price - $this->cost_price) * $this->quantity;
    }

}
