<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class foundation extends Model
{
    use HasFactory;
     
    protected $fillable = [
        'nombre',
        'encargado',
        'numero_telefono',
        'estado'
    ];

}
