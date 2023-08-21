<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Catalogue;

class CatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    $catalogs = [
        [
            'code' => 'S01',
            'catalog_type' => 'Tipo Solicitud',
            'catalog_value' => 'Vinculación',
        ],
        [
            'code' => 'SO2',
            'catalog_type' => 'Tipo Solicitud',
            'catalog_value' => 'Certificado',
        ],
        [
            'code' => 'SE01',
            'catalog_type' => 'Estado Solicitud',
            'catalog_value' => 'Pendiente',
        ],
        [
            'code' => 'SEO2',
            'catalog_type' => 'Estado Solicitud',
            'catalog_value' => 'Aprobado',
        ],
        [
            'code' => 'N01',
            'catalog_type' => 'Nivel',
            'catalog_value' => 'primero',
        ],
        [
            'code' => 'P01',
            'catalog_type' => 'Estado Proyecto',
            'catalog_value' => 'En Ejecucion',
        ],
        [
            'code' => 'P02',
            'catalog_type' => 'Estado Proyecto',
            'catalog_value' => 'Culminado',
        ],
        [
            'code' => 'SEO3',
            'catalog_type' => 'Estado Solicitud',
            'catalog_value' => 'Generar Aprobado',
        ],
    ];

    foreach ($catalogs as $catalog) {
        Catalogue::create($catalog);
    }

    }

}
