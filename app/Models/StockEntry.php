<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockEntry extends Model
{
    protected $fillable = [
        'part_id',
        'quantity',
        'cost_price',
        'received_at',
    ];

    public function part()
    {
        return $this->belongsTo(Part::class);
    }
}
