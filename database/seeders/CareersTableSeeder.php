<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Career;

class CareersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $careers = [
            ['name' => 'Carrera 1', 'code' => 'C1'],
            ['name' => 'Carrera 2', 'code' => 'C2'],
            ['name' => 'Carrera 3', 'code' => 'C3'],
            // Agrega más registros de carreras aquí si es necesario
        ];

        foreach ($careers as $career) {
            Career::create($career);
        }
    }
}
