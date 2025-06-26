<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    protected $fillable = [
        'shop_id',
        'name',
        'part_number',
        'category',
        'brand',
        'cost_price',
        'stock',
        'price',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function stockEntries()
    {
        return $this->hasMany(StockEntry::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
