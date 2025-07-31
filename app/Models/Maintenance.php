<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    protected $fillable = [
        'motorcycle_unit_id',
        'reported_issue',
        'diagnosis',
        'action_taken',
        'status',
        'mechanic_id',
        'repair_date',
    ];

    public function motorcycleUnit()
    {
        return $this->belongsTo(MotorcycleUnit::class);
    }

    public function mechanic()
    {
        return $this->belongsTo(User::class, 'mechanic_id');
    }
}
