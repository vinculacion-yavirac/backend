<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Convention;

class ConventionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    $conventions = [
        [
            'signature_date' => '2023-01-01 00:00:00',
            'expiration_date' => '2024-12-31 23:59:59',
        ],
        [
            'signature_date' => '2024-01-01 00:00:00',
            'expiration_date' => '2025-12-31 23:59:59',
        ],
    ];

    foreach ($conventions as $convention) {
        Convention::create($convention);
    }

    // Agrega más registros si es necesario
    }
}
