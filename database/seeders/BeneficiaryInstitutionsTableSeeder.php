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
            'name_gestion'=>'Pública',
            'name_autorize_by' =>'Juan Rocha',
            'activity_ruc' => 'Educativa',
            'email' =>'email57@edu.ec', 
            'phone'=> '028841445', 
            'address' => 'direccion entidad financiera', 
            'number_students_start' => '5', 
            'number_students_ability'=> '20',
            'Direct beneficiaries' => '1',
            'Indirect beneficiaries'=> '1', 
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
            'name_gestion'=>'Pública',
            'name_autorize_by' =>'Juan Rocha',
            'activity_ruc' => 'Educativa',
            'email' =>'email57@edu.ec', 
            'phone'=> '028841445', 
            'address' => 'direccion entidad financiera', 
            'number_students_start' => '5', 
            'number_students_ability'=> '20',
            'Direct beneficiaries' => '1',
            'Indirect beneficiaries'=> '1', 
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
        [
            'ruc' => '9876543210',
            'name' => 'Fundación Estrella del Sur',
            'name_gestion'=>'Pública',
            'name_autorize_by' =>'Juan Rocha',
            'activity_ruc' => 'Educativa',
            'email' =>'email57@edu.ec', 
            'phone'=> '028841445', 
            'address' => 'direccion entidad financiera', 
            'number_students_start' => '5', 
            'number_students_ability'=> '20',
            'Direct beneficiaries' => '1',
            'Indirect beneficiaries'=> '1', 
            'logo' => 'logo2.png',
            'state' => true,
            'place_location' => 'Lugar 3',
            'postal_code' => '54321',
            'parish_id' => 2,
            'created_by' => 6,
            'archived' => false,
            'archived_at' => '2023-06-09 02:38:10',
            'archived_by' => 1,
        ],
        [
            'ruc' => '2468135790',
            'name' => 'Fundación Esperanza Renovada',
            'name_gestion'=>'Pública',
            'name_autorize_by' =>'Juan Rocha',
            'activity_ruc' => 'Educativa',
            'email' =>'email57@edu.ec', 
            'phone'=> '028841445', 
            'address' => 'direccion entidad financiera', 
            'number_students_start' => '5', 
            'number_students_ability'=> '20',
            'Direct beneficiaries' => '1',
            'Indirect beneficiaries'=> '1', 
            'logo' => 'logo3.png',
            'state' => true,
            'place_location' => 'Lugar 4',
            'postal_code' => '13579',
            'parish_id' => 1,
            'created_by' => 7,
            'archived' => false,
            'archived_at' => '2023-06-09 02:38:10',
            'archived_by' => 1,
        ],
        [
            'ruc' => '9081726354',
            'name' => 'Fundación Camino de Luz',
            'name_gestion'=>'Pública',
            'name_autorize_by' =>'Juan Rocha',
            'activity_ruc' => 'Educativa',
            'email' =>'email57@edu.ec', 
            'phone'=> '028841445', 
            'address' => 'direccion entidad financiera', 
            'number_students_start' => '5', 
            'number_students_ability'=> '20',
            'Direct beneficiaries' => '1',
            'Indirect beneficiaries'=> '1', 
            'logo' => 'logo4.png',
            'state' => true,
            'place_location' => 'Lugar 5',
            'postal_code' => '98765',
            'parish_id' => 2,
            'created_by' => 8,
            'archived' => false,
            'archived_at' => '2023-06-09 02:38:10',
            'archived_by' => 1,
        ],
        [
            'ruc' => '1122334455',
            'name' => 'Fundación Nueva Esperanza',
            'name_gestion'=>'Pública',
            'name_autorize_by' =>'Juan Rocha',
            'activity_ruc' => 'Educativa',
            'email' =>'email57@edu.ec', 
            'phone'=> '028841445', 
            'address' => 'direccion entidad financiera', 
            'number_students_start' => '5', 
            'number_students_ability'=> '20',
            'Direct beneficiaries' => '1',
            'Indirect beneficiaries'=> '1', 
            'logo' => 'logo5.png',
            'state' => true,
            'place_location' => 'Lugar 6',
            'postal_code' => '11223',
            'parish_id' => 1,
            'created_by' => 9,
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
