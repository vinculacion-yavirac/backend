<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Documents;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $documents = [
             [
                 'name' => 'Carta de compromiso',
                 'template' => 'https://drive.google.com/file/d/1xV5cSALN1cdwTS2isNHRZbth-Dt6ezhY/view?usp=sharing',
                 'state' => true,
                 'order' => 1,
                 'responsible_id' => 1,
                 'created_by' => 8,
                 'archived' => false,
                 'archived_at' => null,
                 'archived_by' => null,
             ],
             [
                 'name' => 'Informe de control de vinculación',
                 'template' => 'https://drive.google.com/file/d/1pnMl0D8jjO4G4x8lJWe4X0OBC6X49cUi/view?usp=sharing',
                 'state' => true,
                 'order' => 2,
                 'responsible_id' => 2,
                 'created_by' => 8,
                 'archived' => false,
                 'archived_at' => null,
                 'archived_by' => null,
             ],
             [
                'name' => 'Informe final',
                'template' => 'https://drive.google.com/file/d/1m5ZyeCHrE6dli-it23Ip-h68JCwmIKvJ/view?usp=sharing',
                'state' => true,
                'order' => 2,
                'responsible_id' => 2,
                'created_by' => 8,
                'archived' => false,
                'archived_at' => null,
                'archived_by' => null,
            ],
             // Add more document entries as needed

         ];

         foreach ($documents as $document) {
             Documents::create($document);
         }
    }
}
