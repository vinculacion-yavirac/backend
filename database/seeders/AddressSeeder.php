<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Poblar la tabla 'addresses' con datos de muestra
        DB::table('addresses')->insert([
            [
                'name' => 'Dirección 1',
                'father_code' => null,
            ],
            [
                'name' => 'Dirección 2',
                'father_code' => null,
            ],
            // Agrega más datos de muestra si es necesario
        ]);
    }
}
