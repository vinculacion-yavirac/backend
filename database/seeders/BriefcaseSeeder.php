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
            'state' => 'Active',
            'document_url' => 'https://example.com/document1.pdf',
            'project_participant_id' => 1,
        ],
        [
            'observations' => 'Observation 2',
            'state' => 'Inactive',
            'document_url' => 'https://example.com/document2.pdf',
            'project_participant_id' => 2,
        ],
        // Agrega más registros si es necesario
    ];

    foreach ($briefcases as $briefcase) {
        Briefcase::create($briefcase);
    }

    }
}
