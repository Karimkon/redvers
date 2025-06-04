<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FollowUp extends Model
{
    protected $fillable = [
        'purchase_id', 'missed_date', 'contacted_at', 'status', 'note',
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }
}
