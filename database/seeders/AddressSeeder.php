<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Address;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    // Crear una dirección raíz
    $rootAddress = Address::create([
        'name' => 'Dirección Raíz',
    ]);

    // Crear direcciones hijas
    $childAddresses = [
        [
            'name' => 'Dirección Hija 1',
            'father_code' => $rootAddress->id,
        ],
        [
            'name' => 'Dirección Hija 2',
            'father_code' => $rootAddress->id,
        ],
    ];

    foreach ($childAddresses as $childAddress) {
        $child = Address::create($childAddress);

        // Crear direcciones nietas si es necesario
        if ($child->id === $childAddresses[0]['father_code']) {
            $grandchild1 = Address::create([
                'name' => 'Dirección Nieto 1',
                'father_code' => $child->id,
            ]);
        }
    }

    // Crear más direcciones según tus necesidades

    // Puedes repetir estos pasos para crear más direcciones

    // Ejemplo adicional: Crear una dirección sin padre
    Address::create([
        'name' => 'Dirección Independiente',
    ]);
    }
}
