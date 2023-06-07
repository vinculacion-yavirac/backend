<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('catalogs')->insert([
                    [
                        'code' => '001',
                        'catalog_type' => 'Tipo 1',
                        'catalog_value' => 'Valor 1',
                    ],
                    [
                        'code' => '002',
                        'catalog_type' => 'Tipo 2',
                        'catalog_value' => 'Valor 2',
                    ],
                    // Agrega más datos de muestra si es necesario
                ]);
    }
}
