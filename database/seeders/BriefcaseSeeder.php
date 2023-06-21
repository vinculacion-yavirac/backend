<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Briefcase;

class BriefcaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    $briefcases = [
        [
            'observations' => '',
            'state' => false,
            'archived' => false,
            'archived_at' => null,
            'created_by' => 9,
            'archived_by' => null,
            'project_participant_id' => 3,
        ],
        [
            'observations' => '',
            'state' => false,
            'archived' => false,
            'archived_at' => null,
            'created_by' => 9,
            'archived_by' => null,
            'project_participant_id' => 4,
        ],
        [
            'observations' => '',
            'state' => false,
            'archived' => false,
            'archived_at' => null,
            'created_by' => 9,
            'archived_by' => null,
            'project_participant_id' => 5,
        ],
        [
            'observations' => '',
            'state' => false,
            'archived' => false,
            'archived_at' => null,
            'created_by' => 9,
            'archived_by' => null,
            'project_participant_id' => 6,
        ],
        [
            'observations' => '',
            'state' => false,
            'archived' => false,
            'archived_at' => null,
            'created_by' => 9,
            'archived_by' => null,
            'project_participant_id' => 7,
        ],
        [
            'observations' => '',
            'state' => false,
            'archived' => false,
            'archived_at' => null,
            'created_by' => 9,
            'archived_by' => null,
            'project_participant_id' => 8,
        ],
        [
            'observations' => '',
            'state' => false,
            'archived' => false,
            'archived_at' => null,
            'created_by' => 9,
            'archived_by' => null,
            'project_participant_id' => 9,
        ],
        // Agrega más registros si es necesario
    ];

    foreach ($briefcases as $briefcase) {
        Briefcase::create($briefcase);
    }

    }
}
