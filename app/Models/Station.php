<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'latitude', 'longitude'
    ];

    public function swaps()
    {
        return $this->hasMany(Swap::class);
    }

    public function agent()
    {
        return $this->hasOne(Agent::class);
    }
}
