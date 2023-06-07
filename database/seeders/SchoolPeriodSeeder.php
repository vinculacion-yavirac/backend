<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SchoolPeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Poblar la tabla 'school_periods' con datos de muestra
        DB::table('school_periods')->insert([
            [
                'name' => 'Periodo 1',
                'state' => true,
            ],
            [
                'name' => 'Periodo 2',
                'state' => true,
            ],
            // Agrega más datos de muestra si es necesario
        ]);
    }
}
