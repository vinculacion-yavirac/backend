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
            'created_by' => 8,
            'archived_by' => null,

        ],
        [
            'observations' => '',
            'state' => false,
            'archived' => false,
            'archived_at' => null,
            'created_by' => 6,
            'archived_by' => null,

        ],
        // Agrega más registros si es necesario
    ];

    foreach ($briefcases as $briefcase) {
        Briefcase::create($briefcase);
    }

    }
}
