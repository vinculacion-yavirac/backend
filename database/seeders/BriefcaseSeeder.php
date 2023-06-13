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
            'observations' => 'Observation 1',
            'state' => true,
            'archived' => false,
            'archived_at' => null,
            'created_by' => 9,
            'archived_by' => null,
            'project_participant_id' => 1,
        ],
        [
            'observations' => 'Observation 2',
            'state' => true,
            'archived' => false,
            'archived_at' => null,
            'created_by' => 9,
            'archived_by' => null,
            'project_participant_id' => 2,
        ],
        // Agrega más registros si es necesario
    ];

    foreach ($briefcases as $briefcase) {
        Briefcase::create($briefcase);
    }

    }
}
