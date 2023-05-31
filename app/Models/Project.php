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

    public function foundations(){
        return $this->belongsTo(Foundation:: class,'foundations');
    }

    public function created_by(){
        return $this->belongsTo(User:: class, 'created_by');
    }

}
