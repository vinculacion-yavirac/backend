<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoudationStudenBriefcase extends Model
{
    use HasFactory;

    protected $fillable = [
        'foundations',
        'solicitudes',
        'projects',
        'briefcases',
        'created_at'
    ];

    public function foundations(){
        return $this->belongsTo(Foundation::class,'foundations');
    }

    public function solicitudes(){
        return $this->belongsTo(Solicitude::class,'solicitudes');
    }

    public function briefcases(){
        return $this->belongsTo(Briefcase::class,'briefcases');
    }
}
