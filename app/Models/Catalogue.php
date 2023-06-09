<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Catalogue extends Model
{
    use HasFactory;

        // Define la tabla asociada al modelo
        protected $table = 'catalogs';

        // Define las columnas que se pueden asignar masivamente
        protected $fillable = [
            'code',
            'catalog_type',
            'catalog_value',
        ];

        // Si tu tabla tiene columnas de fecha "created_at" y "updated_at", puedes
        // especificarlas aquí para que Laravel las maneje automáticamente.
        // public $timestamps = true;
}
