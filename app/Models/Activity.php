<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_name',
        'goals_id',
    ];

    public function goal()
    {
        return $this->belongsTo(Goal::class, 'goals_id');
    }
}
