<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expenditure extends Model
{
    protected $fillable = [
        'category', 'description', 'amount', 'payment_method', 'date', 'attachment_id'
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function attachment()
    {
        return $this->belongsTo(Attachment::class);
    }
}
