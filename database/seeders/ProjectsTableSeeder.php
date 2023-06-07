<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $projects = [
                    [
                        'code' => 'PRJ001',
                        'name' => 'Proyecto 1',
                        'field' => 'Campo 1',
                        'term_execution' => 2,
                        'start_date' => '2023-01-01',
                        'end_date' => '2023-12-31',
                        // Otros campos del proyecto
                        'convention_id' => 1,
                        'school_period_id' => 1,
                        'beneficiary_institution_id' => 1,
                        'career_id' => 1,
                        'sub_line_investigation_id' => 1,
                        'authorized_by' => 1,
                        'made_by' => 1,
                        'approved_by' => 1,
                        'catalogue_id' => 1,
                        'state_id' => 1,
                        'stateTwo_id' => 1,
                        'frequency_id' => 1,
                    ],
    }
}
