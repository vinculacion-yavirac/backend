<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Avanze extends Model
{
    use HasFactory;

    protected $fillable = [
        'resumen',
        'indicadores',
        'medios',
        'observacion',
        'created_at',
        'updated_at',
    ];
    // public function resumen()
    // {
    //     return $this->hasMany(Avanze::class,'avanze');
    // }
    // public function indicadores()
    // {
    //     return $this->belongsTo(Avanze::class,'indicadores');
    // }
    // public function medios()
    // {
    //     return $this->belongsTo(Avanze::class,'medios');
    // }
    // public function observacion()
    // {
    //     return $this->hasMany(Avanze::class,'observacion');
    // }
   
    // public function created_at()
    // {
    //     return $this->hasMany(Avanze::class,'created_at');
    // }
    // public function updated_at()
    // {
    //     return $this->hasMany(Avanze::class,'updated_at');
    // }


}
