<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parish extends Model
{
    use HasFactory;

    protected $table = 'parish';

    protected $fillable = ['parish', 'canton', 'province'];

    // Relaciones u otras funciones del modelo, si las hay
}
