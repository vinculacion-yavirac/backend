<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'father_code',
    ];

    public function father()
    {
        return $this->belongsTo(Address::class, 'father_code');
    }
}
