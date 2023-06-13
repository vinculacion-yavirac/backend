<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Briefcase extends Model
{
    use HasFactory;

    protected $fillable = [
        'observations',
        'state',
        'created_by',
        'archived',
        'archived_at',
        'archived_by',
        'project_participant_id',
    ];

    public function project_participant_id()
    {
        return $this->belongsTo(ProjectParticipant::class,'project_participant_id');
    }

}
