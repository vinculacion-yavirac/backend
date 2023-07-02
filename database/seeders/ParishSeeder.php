<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Parish;

class ParishSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

     $parishes = [
         [
            'parish' => 'Nono',
            'canton' => 'Quito',
            'province' => 'Pichincha'
         ],
         [
            'parish' => 'Divino Niño',
            'canton' => 'Durán',
            'province' => 'Guayas'
         ],
         [
            'parish' => 'Calderón',
            'canton' => 'Quito',
            'province' => 'Pichincha'
         ],
         [
            'parish' => 'Calderón',
            'canton' => 'Quito',
            'province' => 'Pichincha'
         ]
     ];

     foreach ($parishes as $parish) {
        Parish::create($parish);
     }
    }
}
