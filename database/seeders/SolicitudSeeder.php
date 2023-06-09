<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Solicitude;

class SolicitudSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
     $solicitudes = [
         [
             'approval_date' => '2023-06-09 02:38:10',
             'solicitudes_status_id' => 3,
             'type_request_id' => 1,
             'who_made_request_id' => 1,
             'created_by' => 1,
             'archived' => false,
             'archived_at' => null,
             'archived_by' => null,
         ],
         [
             'approval_date' => '2023-06-09 02:38:10',
             'solicitudes_status_id' => 3,
             'type_request_id' => 1,
             'who_made_request_id' => 1,
             'created_by' => 1,
             'archived' => false,
             'archived_at' => null,
             'archived_by' => null,
         ],
         [
             'approval_date' => '2023-06-09 02:38:10',
             'solicitudes_status_id' => 3,
             'type_request_id' => 1,
             'who_made_request_id' => 1,
             'created_by' => 1,
             'archived' => false,
             'archived_at' => null,
             'archived_by' => null,
         ],
         [
             'approval_date' => '2023-06-09 02:38:10',
             'solicitudes_status_id' => 3,
             'type_request_id' => 1,
             'who_made_request_id' => 1,
             'created_by' => 1,
             'archived' => false,
             'archived_at' => null,
             'archived_by' => null,
         ],
         [
             'approval_date' => '2023-06-09 02:38:10',
             'solicitudes_status_id' => 4,
             'type_request_id' => 2,
             'who_made_request_id' => 2,
             'created_by' => 2,
             'archived' => true,
             'archived_at' => '2023-06-09 02:38:10',
             'archived_by' => 1,
         ],
     ];

     foreach ($solicitudes as $solicitude) {
         Solicitude::create($solicitude);
     }
    }

 }
