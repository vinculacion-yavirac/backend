<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProjectParticipant;

class ProjectParticipantsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    $projectParticipants = [
        [
            'functions' => json_encode(['Function 1', 'Function 2']),
            'assignment_date' => now(),
            'level_id' => 1,
            'catalogue_id' => 2,
            'schedule_id' => 1,
            'state_id' => 2,
            'project_id' => 1,
            'participant_id' => 1,
        ],
        [
            'functions' => json_encode(['Function 3', 'Function 4']),
            'assignment_date' => now(),
            'level_id' => 2,
            'catalogue_id' => 2,
            'schedule_id' => 2,
            'state_id' => 1,
            'project_id' => 2,
            'participant_id' => 2,
        ],
        [
            'functions' => json_encode(['Function 3', 'Function 4']),
            'assignment_date' => now(),
            'level_id' => 2,
            'catalogue_id' => 2,
            'schedule_id' => 2,
            'state_id' => 1,
            'project_id' => 2,
            'participant_id' => 3,
        ],
        [
            'functions' => json_encode(['Function 3', 'Function 4']),
            'assignment_date' => now(),
            'level_id' => 2,
            'catalogue_id' => 2,
            'schedule_id' => 2,
            'state_id' => 1,
            'project_id' => 2,
            'participant_id' => 4,
        ],
        [
            'functions' => json_encode(['Function 3', 'Function 4']),
            'assignment_date' => now(),
            'level_id' => 2,
            'catalogue_id' => 2,
            'schedule_id' => 2,
            'state_id' => 1,
            'project_id' => 2,
            'participant_id' => 5,
        ],
        [
            'functions' => json_encode(['Function 3', 'Function 4']),
            'assignment_date' => now(),
            'level_id' => 2,
            'catalogue_id' => 2,
            'schedule_id' => 2,
            'state_id' => 1,
            'project_id' => 2,
            'participant_id' => 6,
        ],
        [
            'functions' => json_encode(['Function 3', 'Function 4']),
            'assignment_date' => now(),
            'level_id' => 2,
            'catalogue_id' => 2,
            'schedule_id' => 2,
            'state_id' => 1,
            'project_id' => 2,
            'participant_id' => 7,
        ],
        [
            'functions' => json_encode(['Function 3', 'Function 4']),
            'assignment_date' => now(),
            'level_id' => 2,
            'catalogue_id' => 2,
            'schedule_id' => 2,
            'state_id' => 1,
            'project_id' => 2,
            'participant_id' => 8,
        ],
        [
            'functions' => json_encode(['Function 3', 'Function 4']),
            'assignment_date' => now(),
            'level_id' => 2,
            'catalogue_id' => 2,
            'schedule_id' => 2,
            'state_id' => 1,
            'project_id' => 2,
            'participant_id' => 9,
        ],
        // Agrega más registros si es necesario
    ];

    foreach ($projectParticipants as $projectParticipant) {
        ProjectParticipant::create($projectParticipant);
    }

    }
}
