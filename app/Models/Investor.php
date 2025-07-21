<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Investor extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'contribution', 'ownership_percentage', 'payment_method', 'date', 'attachment_id'
    ];

    protected $casts = [
        'contribution' => 'decimal:2',
        'ownership_percentage' => 'decimal:2',
        'date' => 'date',
    ];

    public function attachment()
    {
        return $this->belongsTo(Attachment::class);
    }
}
