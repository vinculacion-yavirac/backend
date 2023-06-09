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
            'name' => 'Alas de Colibri',
            'logo' => 'logo1.png',
            'state' => true,
            'place_location' => 'Lugar 1',
            'postal_code' => '12345',
            'parish_id' => 1,
            'created_by' => 5,
            'archived' => false,
            'archived_at' => '2023-06-09 02:38:10',
            'archived_by' => 1,
        ],
        [
            'ruc' => '0987654321',
            'name' => 'Gad Guayabanba',
            'logo' => 'logo2.png',
            'state' => false,
            'place_location' => 'Lugar 2',
            'postal_code' => '54321',
            'parish_id' => 2,
            'created_by' => 5,
            'archived' => false,
            'archived_at' => '2023-06-09 02:38:10',
            'archived_by' => 1,
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
