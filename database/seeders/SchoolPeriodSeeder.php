<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SchoolPeriod;

class SchoolPeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    $schoolPeriods = [
        [
            'name' => 'Periodo 1',
            'state' => true,
        ],
        [
            'name' => 'Periodo 2',
            'state' => true,
        ],
        // Agrega más registros si es necesario
    ];

    foreach ($schoolPeriods as $schoolPeriod) {
        SchoolPeriod::create($schoolPeriod);
    }

   }
}
