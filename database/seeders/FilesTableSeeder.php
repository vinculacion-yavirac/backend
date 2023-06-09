<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\File;

class FilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crea registros de ejemplo en la tabla 'files'
        $files = [
            [
                'name' => 'File 1',
                'type' => 'pdf',
                'content' => 'Lorem ipsum dolor sit amet.',
                'observation' => 'Some observation',
                'state' => true,
                'size' => 1024,
                'briefcase_id' => 1,
                'document_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'File 2',
                'type' => 'doc',
                'content' => 'Consectetur adipiscing elit.',
                'observation' => 'Another observation',
                'state' => false,
                'size' => 2048,
                'briefcase_id' => 2,
                'document_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Agrega más registros de ejemplo si es necesario
        ];

        foreach ($files as $file) {
            File::create($file);
        }
    }
}
