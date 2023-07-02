<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Institute;

class InstituteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

     $institutes = [
         [
            'number_resolution' => '87653132323',
            'name' => 'Yavirac',
            'logo' => 'logo2.jpg',
            'state' => true,
            'place_location' => 'Direccion',
            'email' => 'correo@gmail.com',
            'phone' => '0982223412'
         ],
         [
            'number_resolution' => '5431242312',
            'name' => 'Espe',
            'logo' => 'logo1.jpg',
            'state' => true,
            'place_location' => 'Direccion',
            'email' => 'correo@gmail.com',
            'phone' => '0999108761'
         ],
     ];

     foreach ($institutes as $institute) {
        Institute::create($institute);
     }
    }
}
