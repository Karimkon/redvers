<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class COGS extends Model
{   
    protected $table = 'c_o_g_s';

    protected $fillable = [
        'product_id', 'description', 'unit_cost', 'quantity', 'date', 'attachment_id'
    ];

    protected $casts = [
        'date' => 'date',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    public function attachment()
    {
        return $this->belongsTo(Attachment::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productCategory()
    {
        return $this->product?->category;
    }


}
