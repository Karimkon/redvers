<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
        'lender', 'amount', 'interest_rate', 'interest_paid', 'issued_date', 'due_date', 'status', 'attachment_id'
    ];

    protected $casts = [
        'issued_date' => 'date',
        'due_date' => 'date',
        'amount' => 'decimal:2',
        'interest_rate' => 'decimal:2',
        'interest_paid' => 'decimal:2',
    ];

    public function attachment()
    {
        return $this->belongsTo(Attachment::class);
    }
}
