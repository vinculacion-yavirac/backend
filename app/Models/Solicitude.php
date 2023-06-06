<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitude extends Model
{
    use HasFactory;
    protected $fillable = [
        'solicitudes',
        'status',
        'created_by',
        'archived',
        'archived_at',
        'created_at',
        'archived_by',
    ];

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function archived_by()
    {
        return $this->belongsTo(User::class);
    }

     public function projects(){
         return $this->belongsToMany(Project::class,'integrantes');
     }

}
