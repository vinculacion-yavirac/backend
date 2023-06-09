<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ResearchLine;

class ResearchLinesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    $researchLines = [
        [
            'name' => 'Research Line 1',
            'career_id' => 1,
        ],
        [
            'name' => 'Research Line 2',
            'career_id' => 1,
        ],
        // Agrega más registros si es necesario
    ];

    ResearchLine::insert($researchLines);
    }
}
