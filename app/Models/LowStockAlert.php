<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LowStockAlert extends Model
{
    protected $fillable = [
        'part_id',
        'shop_id',
        'remaining_quantity',
        'resolved',
    ];

    public function part()
    {
        return $this->belongsTo(Part::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }
}
