<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'swap_id', 'amount', 'method', 'status', 'reference', 'initiated_by'
    ];

    public function swap()
    {
        return $this->belongsTo(Swap::class);
    }

    public function rider()
    {
        return $this->swap->riderUser(); // safer and cleaner
    }

}
