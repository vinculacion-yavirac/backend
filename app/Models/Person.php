<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Person extends Model
{
    use HasFactory;

    protected $fillable = [
        'identification_type',
        'identification',
        'names',
        'last_names',
        'gender',
        'date_birth',
        'place_birth',
        'mobile_phone',
        'landline_phone',
        'address',
        'province',
        'canton',
        'parish',
    ];
}
