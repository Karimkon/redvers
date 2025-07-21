<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaxSetting extends Model
{
    protected $fillable = [
        'name', 'rate', 'active', 'attachment_id'
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'active' => 'boolean',
    ];

    public function attachment()
    {
        return $this->belongsTo(Attachment::class);
    }

}
