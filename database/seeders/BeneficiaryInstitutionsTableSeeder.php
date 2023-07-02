<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BeneficiaryInstitution;

class BeneficiaryInstitutionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    $beneficiaryInstitutions = [
        [
            'ruc' => '1234567890',
            'name' => 'Institución Beneficiaria 1',
            'logo' => 'logo1.png',
            'state' => true,
            'place_location' => 'Lugar 1',
            'postal_code' => '12345',
            'addresses_id' => 1,
            'management_nature' => 'publica',
            'economic_activity' => 'Actividad Ecónomica',
            'phone' => '098887131',
            'email' => 'correo@gmail.com',
            'parish_main_id' => 1,
            'parish_branch_id' => 2
        ],
        [
            'ruc' => '0987654321',
            'name' => 'Institución Beneficiaria 2',
            'logo' => 'logo2.png',
            'state' => false,
            'place_location' => 'Lugar 2',
            'postal_code' => '54321',
            'addresses_id' => 2,
            'management_nature' => 'privada',
            'economic_activity' => 'Actividad Ecónomica',
            'phone' => '098887131',
            'email' => 'correo@gmail.com',
            'parish_main_id' => 3,
            'parish_branch_id' => 4
        ],
        // Agrega más registros de instituciones beneficiarias si es necesario
    ];

    foreach ($beneficiaryInstitutions as &$beneficiaryInstitution) {
        $beneficiaryInstitution['created_at'] = now();
        $beneficiaryInstitution['updated_at'] = now();
    }

    BeneficiaryInstitution::insert($beneficiaryInstitutions);
    }
}
