<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $fillable = [
        'name',
        'location',
        'contact_number',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parts()
    {
        return $this->hasMany(Part::class);
    }

    public function sales()
    {
        return $this->hasManyThrough(\App\Models\Sale::class, \App\Models\Part::class);
    }

    public function stockEntries()
    {
        return $this->hasManyThrough(\App\Models\StockEntry::class, \App\Models\Part::class);
    }


}

