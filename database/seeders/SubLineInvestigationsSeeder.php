<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SubLineInvestigation;

class SubLineInvestigationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $subLineInvestigations = [
            [
                'description' => 'Subline Investigation 1',
                'research_line_id' => 1,
            ],
            [
                'description' => 'Subline Investigation 2',
                'research_line_id' => 2,
            ],
            // Agrega más registros según tus necesidades
        ];

        foreach ($subLineInvestigations as $subLineInvestigation) {
            SubLineInvestigation::create($subLineInvestigation);
        }
    }
}
