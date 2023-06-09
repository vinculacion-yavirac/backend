<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolPeriod extends Model
{
    use HasFactory;

    protected $table = 'school_periods';

    protected $fillable = [
        'name',
        'state',
    ];

    public $timestamps = true;
}
