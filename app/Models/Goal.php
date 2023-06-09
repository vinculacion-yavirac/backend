<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    use HasFactory;

    protected $fillable = [
        'target_name',
        'media_verification',
        'verifiable_indicators',
        'father_goals_id',
        'project_id',
        'target_type_id',
    ];

    public function fatherGoal()
    {
        return $this->belongsTo(Goal::class, 'father_goals_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function targetType()
    {
        return $this->belongsTo(Catalog::class, 'target_type_id');
    }

}
