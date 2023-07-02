<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\SchoolPeriod;
use App\Models\Career;
use App\Models\BeneficiaryInstitution;
use App\Models\Institute;
use App\Models\Responsible;
use App\Models\Parish;


class ProjectController extends Controller
{

    /**
     * Summary of getProject
     * @return JsonResponse
     * Obtener todas las fundaciones
     */
    public function getProject(){
        $projects = Project::where('archived', false)
           ->with('created_by.person', 'beneficiary_institution_id', 'stateTwo_id', 'authorizedBy.user_id.person')
           ->with('beneficiary_institution_id', 'beneficiary_institution_id.addresses_id')
           ->with('beneficiary_institution_id', 'beneficiary_institution_id.parish_main_id')
           ->with('beneficiary_institution_id', 'beneficiary_institution_id.parish_branch_id')
           ->get();
        return new JsonResponse([
            'status' => 'success',
            'data' => ['projects' => $projects]
        ], 200);
    }

        /**
     * Summary of getProjectById
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     * Obtener por el id
     */
    public function getProjectById($id)
    {
      $projects = Project::where('id', $id)
          //->where('id', '!=', 0)
          ->where('archived', false)
          ->with('created_by.person', 'beneficiary_institution_id', 'stateTwo_id', 'authorizedBy.user_id.person')
          ->with('created_by', 'created_by.person')
          ->with('beneficiary_institution_id', 'beneficiary_institution_id.addresses_id')
          ->with('beneficiary_institution_id', 'beneficiary_institution_id.parish_main_id')
          ->with('beneficiary_institution_id', 'beneficiary_institution_id.parish_branch_id')
          ->first();

        if (!$projects) {
            return response()->json([
                'message' => 'Proyecto no encontrado'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'projects' => $projects
            ],
        ]);
    }


    /**
     * Summary of getArchivedProject
     * @return \Illuminate\Http\JsonResponse
     * Obtener todas las archivadas por true
     */
    public function getArchivedProject()
    {
      $projects = Project::where('id', '!=', 0)
          ->where('archived', true)
          ->with('created_by.person','beneficiary_institution_id','stateTwo_id','authorizedBy.user_id.person')
          ->get();

      return response()->json([
          'status' => 'success',
          'data' => [
              'projects' => $projects,
          ],
      ]);
    }


    /**
     * Summary of searchProjectByTerm
     * @param mixed $term
     * @return \Illuminate\Http\JsonResponse
     * Buscador de proyectos
     */
    public function searchProjectByTerm($term = '')
    {
        $projects = Project::where('archived', false)
            ->where(function ($query) use ($term) {
                $lowerTerm = strtolower($term);
                $query->whereRaw('LOWER(name) like ?', ['%' . $lowerTerm . '%'])
                    ->orWhereHas('beneficiary_institution_id', function ($query) use ($lowerTerm) {
                        $query->whereRaw('LOWER(name) like ?', ['%' . $lowerTerm . '%'])
                            ->orWhereRaw('LOWER(code) like ?', ['%' . $lowerTerm . '%']);
                    })
                    ->orWhereHas('authorizedBy.user_id.person', function ($query) use ($lowerTerm) {
                        $query->whereRaw('LOWER(names) like ?', ['%' . $lowerTerm . '%']);
                    });
            })
            ->with('created_by.person', 'beneficiary_institution_id', 'stateTwo_id', 'authorizedBy.user_id.person')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'projects' => $projects
            ],
        ]);
    }


    /**
     * Summary of searchArchivedProjectByTerm
     * @param mixed $term
     * @return \Illuminate\Http\JsonResponse
     * Buscador de archivados
     */
    public function searchArchivedProjectByTerm($term = '')
    {
        $projects = Project::where('archived', true)
            ->where(function ($query) use ($term) {
                $lowerTerm = strtolower($term);
                $query->whereRaw('LOWER(name) like ?', ['%' . $lowerTerm . '%'])
                    ->orWhereHas('beneficiary_institution_id', function ($query) use ($lowerTerm) {
                        $query->whereRaw('LOWER(name) like ?', ['%' . $lowerTerm . '%'])
                            ->orWhereRaw('LOWER(code) like ?', ['%' . $lowerTerm . '%']);
                    })
                    ->orWhereHas('authorizedBy.user_id.person', function ($query) use ($lowerTerm) {
                        $query->whereRaw('LOWER(names) like ?', ['%' . $lowerTerm . '%']);
                    });
            })
            ->with('created_by.person', 'beneficiary_institution_id', 'stateTwo_id', 'authorizedBy.user_id.person')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'projects' => $projects
            ],
        ]);
    }

    /**
     * Summary of archiveProject
     * @param mixed $id
     * @return JsonResponse
     * Archivar proyecto por el id
     */
    public function archiveProject($id)
    {
        $project = Project::findOrFail($id);
        $project->update([
            'archived' => true,
            'archived_at' => now(),
            'archived_by' => auth()->user()->id,
        ]);

        return new JsonResponse([
            'status' => 'success',
            'message' => 'Proyecto archivado correctamente',
            'data' => [
                'project' => $project,
            ],
        ], 200);
    }

    /**
     * Summary of restoreProject
     * @param mixed $id
     * @return JsonResponse
     * Restaurar proyecto por id
     */
    public function restoreProject($id)
    {
        $project = Project::findOrFail($id);
        $project->update(['archived' => false]);

        return new JsonResponse([
            'status' => 'success',
            'message' => 'Proyecto restaurado correctamente',
            'data' => [
                'project' => $project,
            ],
        ], 200);
    }


        public function getProjectByFoundation($project)
        {
            try {
                // Obtener el proyecto por su nombre
                 //$project = Project::where('id', $project)->orWhere('name', $project)->first();

                if (is_numeric($project)) {
                    // Buscar el proyecto por ID
                    //$project = Project::find($project);
                     // Buscar el proyecto por ID y cargar las fundaciones y sus campos
                     $project = Project::with('foundations')->find($project);
                } else {
                    // Buscar el proyecto por nombre
                    //$project = Project::where('name', $project)->first();
                    // Buscar el proyecto por nombre y cargar las fundaciones y sus campos
                    $project = Project::with('foundations')->where('name', $project)->first();
                }

                if (!$project) {
                    throw new \Exception('Project not found.');
                }

                // Obtener la fundación asociada al proyecto
                $foundations = $project->foundations;

                return response()->json([
                    'status' => 'success',
                    'data' => [
                        'project' => $project
                    ]
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ]);
            }
        }

        public function createProject(Request $request)
        {
        /*$request->validate([
            'subject' => 'required|string',
            'description' => 'required|string',
        ]);*/
        try {
            // Create a new project instance
            $project = new Project();

            $project->code = $request->code;
            $project->name = $request->name;
            $project->term_execution = $request->term_execution;
            $project->start_date = $request->start_date;
            $project->end_date = $request->end_date;
            $project->coverage = $request->coverage;
            $project->modality = $request->modality;
            $project->financing = $request->financing;
            $project->linking_activity = ["diaria"=>false,"semanal"=>false,"quincenal"=>false,"mensual"=>false,"correos"=>false,"pvpij"=>false,"pcc"=>false,"pvc"=>false,"investigacion"=>false,"acuerdo"=>false,"otros"=>false,"educacion"=>false,"salud"=>false,"sa"=>false,"ap"=>false,"agyp"=>false,"vivienda"=>false,"pma"=>false,"rne"=>false,"tcv"=>false,"desarrolloU"=>false,"turismo"=>false,"cultura"=>false,"dic"=>false,"deportes"=>false,"jys"=>false,"ambiente"=>false,"dsyc"=>false,"ia"=>false,"ig"=>false,"desarrolloL"=>false,"epys"=>false,"dtyt"=>false,"inovacion"=>false,"rsu"=>false,"matrizP"=>false,"otros2"=>false];
            $project->beneficiary_institution_id = 1;

            // Find the corresponding SchoolPeriod and Career models and associate them with the project
            $schoolPeriod = SchoolPeriod::where('id', $request->school_period_id)->first();
            $project->schoolPeriod()->associate($schoolPeriod);

            $institute = Institute::where('id', $request->institute_id)->first();
            $project->institute()->associate($institute);

            $authorizedBy = Responsible::where('id', $request->authorized_by)->first();
            $project->authorizedBy()->associate($authorizedBy);

            $career = Career::where('id', $request->career_id)->first();
            $project->career()->associate($career);

            // Set the created_by field to the authenticated user's ID
            $project->created_by = auth()->user()->id;
            // Save the project
            $project->save();


            return response()->json([
                'status' => 'success',
                'data' => [
                    'project' => $project
                ],
                'message' => 'Proyecto creado con éxito'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear el Proyecto: ' . $e->getMessage()
            ]);
        }
    }

    public function updateProject(Request $request, $id)
{
    try {
        // Buscar el proyecto por su ID
        $project = Project::find($id);

        // Actualizar los campos del proyecto con los datos del request
        $project->code = $request->code;
        $project->name = $request->name;
        $project->term_execution = $request->term_execution;
        $project->start_date = $request->start_date;
        $project->end_date = $request->end_date;
        $project->coverage = $request->coverage;
        $project->modality = $request->modality;
        $project->financing = $request->financing;
        $project->linking_activity = $request->linking_activity;

        // Find the corresponding SchoolPeriod and Career models and associate them with the project
        $schoolPeriod = SchoolPeriod::where('id', $request->school_period_id)->first();
        $project->schoolPeriod()->associate($schoolPeriod);

        $institute = Institute::where('id', $request->institute_id)->first();
        $project->institute()->associate($institute);

        $authorizedBy = Responsible::where('id', $request->authorized_by)->first();
        $project->authorizedBy()->associate($authorizedBy);

        $career = Career::where('id', $request->career_id)->first();
        $project->career()->associate($career);

        // Set the created_by field to the authenticated user's ID
        $project->created_by = auth()->user()->id;

        // Save the project
        $project->save();

        $beneficiaryInstitution = BeneficiaryInstitution::where('id', $request->beneficiary_institution_id)->first();
        $beneficiaryInstitution->name = $request->beneficiary_institution_id['name'];
        $beneficiaryInstitution->management_nature = $request->beneficiary_institution_id['management_nature'];
        $beneficiaryInstitution->ruc = $request->beneficiary_institution_id['ruc'];
        $beneficiaryInstitution->economic_activity = $request->beneficiary_institution_id['economic_activity'];
        $beneficiaryInstitution->phone = $request->beneficiary_institution_id['phone'];
        $beneficiaryInstitution->email = $request->beneficiary_institution_id['email'];
        $beneficiaryInstitution->postal_code = $request->beneficiary_institution_id['postal_code'];
        $beneficiaryInstitution->save();

        $parishMain = Parish::where('id', $beneficiaryInstitution->parish_main_id)->first();
        $parishMain->parish = $request->beneficiary_institution_id['parish_main_id']['parish'];
        $parishMain->canton = $request->beneficiary_institution_id['parish_main_id']['canton'];
        $parishMain->province = $request->beneficiary_institution_id['parish_main_id']['province'];
        $parishMain->save();

        $parishBranch = Parish::where('id', $beneficiaryInstitution->parish_branch_id)->first();
        $parishBranch->parish = $request->beneficiary_institution_id['parish_branch_id']['parish'];
        $parishBranch->canton = $request->beneficiary_institution_id['parish_branch_id']['canton'];
        $parishBranch->province = $request->beneficiary_institution_id['parish_branch_id']['province'];
        $parishBranch->save();

        $beneficiaryInstitution->parish_main_id()->associate($parishMain);
        $beneficiaryInstitution->parish_branch_id()->associate($parishBranch);
        $beneficiaryInstitution->save();

        $project->beneficiary_institution_id()->associate($beneficiaryInstitution);
        $project->save();

        return response()->json([
            'status' => 'success',
            'data' => [
                'project' => $project
            ],
            'message' => 'Proyecto actualizado con éxito'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Error al actualizar el proyecto: ' . $e->getMessage()
        ]);
    }
}


}
