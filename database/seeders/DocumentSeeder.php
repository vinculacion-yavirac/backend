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
                 'name' => 'Commitment letter',
                 'template' => 'path/to/commitment_letter_template',
                 'state' => true,
                 'order' => 1,
                 'responsible_id' => 1,
             ],
             [
                 'name' => 'Start report',
                 'template' => 'path/to/start_report_template',
                 'state' => true,
                 'order' => 2,
                 'responsible_id' => 2,
             ],
             // Add more document entries as needed

         ];

         foreach ($documents as $document) {
             Documents::create($document);
         }
    }
}
