<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Activity;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    $activities = [
        [
            'activity_name' => 'Actividad 1',
            'goals_id' => 1,
        ],
        [
            'activity_name' => 'Actividad 2',
            'goals_id' => 2,
        ],
        // Agrega más registros si es necesario
    ];

    foreach ($activities as $activity) {
        Activity::create($activity);
    }

    }
}
