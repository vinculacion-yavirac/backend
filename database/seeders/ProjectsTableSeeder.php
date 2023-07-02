<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Project;

class ProjectsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

     $projects = [
         [
             'code' => 'P001',
             'name' => 'Proyecto 1',
             'field' => 'Campo de proyecto',
             'term_execution' => 5,
             'start_date' => now(),
             'end_date' => now()->addMonths(6),
             'linking_activity' => ["diaria"=>false,"semanal"=>false,"quincenal"=>false,"mensual"=>false,"correos"=>false,"pvpij"=>false,"pcc"=>false,"pvc"=>false,"investigacion"=>false,"acuerdo"=>false,"otros"=>false,"educacion"=>false,"salud"=>false,"sa"=>false,"ap"=>false,"agyp"=>false,"vivienda"=>false,"pma"=>false,"rne"=>false,"tcv"=>false,"desarrolloU"=>false,"turismo"=>false,"cultura"=>false,"dic"=>false,"deportes"=>false,"jys"=>false,"ambiente"=>false,"dsyc"=>false,"ia"=>false,"ig"=>false,"desarrolloL"=>false,"epys"=>false,"dtyt"=>false,"inovacion"=>false,"rsu"=>false,"matrizP"=>false,"otros2"=>false],
             'sectors_intervention' => json_encode(['Sector 1', 'Sector 2']),
             'strategic_axes' => json_encode(['Eje 1', 'Eje 2']),
             'description' => 'Descripción del proyecto',
             'situational_analysis' => 'Análisis situacional',
             'foundation' => 'Fundamentación del proyecto',
             'justification' => 'Justificación del proyecto',
             'direct_beneficiaries' => json_encode(['Beneficiario 1', 'Beneficiario 2']),
             'indirect_beneficiaries' => json_encode(['Beneficiario indirecto 1', 'Beneficiario indirecto 2']),
             'schedule' => 'Horario del proyecto',
             'evaluation_monitoring_strategy' => json_encode(['Estrategia de evaluación 1', 'Estrategia de evaluación 2']),
             'bibliographies' => json_encode(['Bibliografía 1', 'Bibliografía 2']),
             'attached_project' => json_encode(['Proyecto adjunto 1', 'Proyecto adjunto 2']),
             'convention_id' => 1,
             'school_period_id' => 2,
             'beneficiary_institution_id' => 2,
             'career_id' => 2,
             'sub_line_investigation_id' => 2,
             'authorized_by' => 2,
             'made_by' => 1,
             'approved_by' => 1,
             'catalogue_id' => 1,
             'state_id' => 2,
             'stateTwo_id' => 2,
             'frequency_id' => 1,
             'created_by' => 9,
             'archived' => false,
             'archived_at' => null,
             'archived_by' => null,
         ],
         [
             'code' => 'P002',
             'name' => 'Proyecto 12',
             'field' => 'Campo de proyecto',
             'term_execution' => 5,
             'start_date' => now(),
             'end_date' => now()->addMonths(6),
             'linking_activity' => ["diaria"=>false,"semanal"=>false,"quincenal"=>false,"mensual"=>false,"correos"=>false,"pvpij"=>false,"pcc"=>false,"pvc"=>false,"investigacion"=>false,"acuerdo"=>false,"otros"=>false,"educacion"=>false,"salud"=>false,"sa"=>false,"ap"=>false,"agyp"=>false,"vivienda"=>false,"pma"=>false,"rne"=>false,"tcv"=>false,"desarrolloU"=>false,"turismo"=>false,"cultura"=>false,"dic"=>false,"deportes"=>false,"jys"=>false,"ambiente"=>false,"dsyc"=>false,"ia"=>false,"ig"=>false,"desarrolloL"=>false,"epys"=>false,"dtyt"=>false,"inovacion"=>false,"rsu"=>false,"matrizP"=>false,"otros2"=>false],
             'sectors_intervention' => json_encode(['Sector 1', 'Sector 2']),
             'strategic_axes' => json_encode(['Eje 1', 'Eje 2']),
             'description' => 'Descripción del proyecto',
             'situational_analysis' => 'Análisis situacional',
             'foundation' => 'Fundamentación del proyecto',
             'justification' => 'Justificación del proyecto',
             'direct_beneficiaries' => json_encode(['Beneficiario 1', 'Beneficiario 2']),
             'indirect_beneficiaries' => json_encode(['Beneficiario indirecto 1', 'Beneficiario indirecto 2']),
             'schedule' => 'Horario del proyecto',
             'evaluation_monitoring_strategy' => json_encode(['Estrategia de evaluación 1', 'Estrategia de evaluación 2']),
             'bibliographies' => json_encode(['Bibliografía 1', 'Bibliografía 2']),
             'attached_project' => json_encode(['Proyecto adjunto 1', 'Proyecto adjunto 2']),
             'convention_id' => 1,
             'school_period_id' => 2,
             'beneficiary_institution_id' => 2,
             'career_id' => 2,
             'sub_line_investigation_id' => 2,
             'authorized_by' => 2,
             'made_by' => 1,
             'approved_by' => 1,
             'catalogue_id' => 1,
             'state_id' => 2,
             'stateTwo_id' => 2,
             'frequency_id' => 1,
             'created_by' => 9,
             'archived' => false,
             'archived_at' => null,
             'archived_by' => null,
         ],



     ];

     foreach ($projects as $project) {
         Project::create($project);
     }

    }
}
