<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Revenue extends Model
{
    protected $fillable = [
        'source', 'description', 'amount', 'payment_method', 'date', 'reference', 'attachment_id'
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
