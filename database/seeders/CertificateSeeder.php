<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Certificate;

class CertificateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

     $certificates = [
         [
            'code' => 'ABC123',
            'certificate_url' => 'https://example.com/certificate',
            'certificate_type_id' => 1,
            'certificate_status_id' => 2,
            'project_participants_id' => 3,
         ],
         [
            'code' => 'ADCGF1',
            'certificate_url' => 'https://example.com/certificate',
            'certificate_type_id' => 1,
            'certificate_status_id' => 2,
            'project_participants_id' => 3,
         ],
     ];

     foreach ($certificates as $certificate) {
         Certificate::create($certificate);
     }
    }
}
