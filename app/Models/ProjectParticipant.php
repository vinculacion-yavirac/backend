<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectParticipant extends Model
{
    use HasFactory;

        protected $table = 'project_participants';

        protected $fillable = [
            'functions',
            'assignment_date',
            'level_id',
            'catalogue_id',
            'schedule_id',
            'state_id',
            'project_id',
            'participant_id',
        ];

        protected $casts = [
            'functions' => 'json',
            'assignment_date' => 'datetime',
        ];

        public function level()
        {
            return $this->belongsTo(Catalog::class, 'level_id');
        }

        public function catalogue()
        {
            return $this->belongsTo(Catalog::class, 'catalogue_id');
        }

        public function schedule()
        {
            return $this->belongsTo(Catalog::class, 'schedule_id');
        }

        public function state()
        {
            return $this->belongsTo(Catalog::class, 'state_id');
        }

        public function project()
        {
            return $this->belongsTo(Project::class);
        }

        public function participant()
        {
            return $this->belongsTo(User::class, 'participant_id');
        }
}
