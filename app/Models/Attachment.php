<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $fillable = [
        'file_path', 'file_type', 'description'
    ];

    public function revenues()
    {
        return $this->hasMany(Revenue::class);
    }

    public function expenditures()
    {
        return $this->hasMany(Expenditure::class);
    }

    public function cogs()
    {
        return $this->hasMany(COGS::class);
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function investors()
    {
        return $this->hasMany(Investor::class);
    }
}
