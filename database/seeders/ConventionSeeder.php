<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConventionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Poblar la tabla 'conventions' con datos de muestra
        DB::table('conventions')->insert([
            [
                'signature_date' => now(),
                'expiration_date' => now()->addYear(),
            ],
            [
                'signature_date' => now(),
                'expiration_date' => now()->addYear(),
            ],
            // Agrega más datos de muestra si es necesario
        ]);
    }

}
