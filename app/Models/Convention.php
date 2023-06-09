<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Convention extends Model
{
    use HasFactory;

    protected $table = 'conventions';

    protected $fillable = [
        'signature_date',
        'expiration_date',
    ];

    public $timestamps = true;
}
