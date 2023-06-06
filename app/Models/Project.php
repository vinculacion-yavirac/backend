<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
        'foundations',
        'created_by',
    ];

    public function created_by(){
        return $this->belongsTo(User::class, 'created_by');
    }

    public function foundations(){
            return $this->belongsTo(Foundation::class, 'foundations');
    }

    public function solicitudes(){
        return $this->belongsToMany(Solicitude::class,'integrantes');
    }

    //public function integrantes(){
      // return $this->belongsToMany(Integrante::class, 'project_integrante');
    //}
}
