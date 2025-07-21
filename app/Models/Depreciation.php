<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Depreciation extends Model
{
    protected $fillable = [
        'product_id', 'initial_value', 'depreciation_rate', 'lifespan_months', 'start_date', 'note'
    ];

    protected $casts = [
        'initial_value' => 'decimal:2',
        'depreciation_rate' => 'decimal:2',
        'start_date' => 'date',
    ];

    public function product()
    {
        return $this->belongsTo(\App\Models\Product::class);
    }
}
