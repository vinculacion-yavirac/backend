<?php

namespace App\Models;

use App\Models\File;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Briefcase extends Model
{
    use HasFactory;

    protected $fillable = [

        'observations',
        'state',
        'archived',
        'archived_at',
        'created_by',
        'archived_by',
        'project_participant_id',
    ];

    public function project_participant_id()
    {
        return $this->belongsTo(ProjectParticipant::class,'project_participant_id');
    }


    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function archived_by()
    {
        return $this->belongsTo(User::class, 'archived_by');
    }
    
    /*
    public function documents()
    {
        return $this->belongsToMany(Documents::class, 'files', 'briefcase_id','document_id')
            ->using(File::class)
            ->withPivot(['name', 'type', 'content', 'observation', 'state', 'size']);
    }

    */

    public function documents()
    {
        return $this->belongsToMany(Documents::class, 'files', 'briefcase_id', 'document_id')
            ->using(File::class)
            ->withPivot(['name', 'type', 'content', 'observation', 'state', 'size']);
    }

    public function files()
    {
        return $this->hasMany(File::class, 'briefcase_id');
    }
}


    /*
    public function documents()
    {
        return $this->belongsToMany(Documents::class, 'files', 'briefcase_id', 'document_id');
    }
    */
