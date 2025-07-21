<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['name', 'category_id', 'description', 'unit_cost'];

    public function cogs()
    {
        return $this->hasMany(COGS::class);
    }

    // Automatically calculate total production cost from COGS
    public function getTotalProductionCostAttribute()
    {
        return $this->cogs->sum(fn($c) => $c->unit_cost * $c->quantity);
    }

    public function getLatestUnitCostAttribute()
    {
        return $this->cogs()->latest()->first()?->unit_cost;
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

}
