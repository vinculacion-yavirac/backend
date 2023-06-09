<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Responsible;

class ResponsibleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    $responsible = [
        [
             'user_id' => 1,
             'charge_id' => 1,
        ],
        [
             'user_id' => 2,
             'charge_id' => 2,
        ],
        // Agrega más registros si es necesario
    ];

    foreach ($responsible as $responsible) {
        Responsible::create($responsible);
    }

   }
}
