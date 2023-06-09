<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Goal;

class GoalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    $goals = [
        [
            'target_name' => 'Objetivo 1',
            'media_verification' => json_encode(['verification1', 'verification2']),
            'verifiable_indicators' => 'Indicador 1',
            'father_goals_id' => null,
            'project_id' => 1,
            'target_type_id' => 1,
        ],
        [
            'target_name' => 'Objetivo 2',
            'media_verification' => json_encode(['verification3', 'verification4']),
            'verifiable_indicators' => 'Indicador 2',
            'father_goals_id' => 1,
            'project_id' => 1,
            'target_type_id' => 2,
        ],
        // Agrega más registros si es necesario
    ];

    foreach ($goals as $goal) {
        Goal::create($goal);
    }

    }
}
