<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Integrantes extends Model
{
    use HasFactory;

    protected $fillable = [
        'solicitude_id',
        'briefcase_id',
        'project_id',
    ];

}
