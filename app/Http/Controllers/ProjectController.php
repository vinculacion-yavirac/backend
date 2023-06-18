<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{

    /**
     * Summary of getProject
     * @return JsonResponse
     * Obtener todas las fundaciones
     */
    public function getProject(){
        $projects = Project::where('id', '!=', 0)
           ->where('archived', false)
           ->with('created_by.person','beneficiary_institution_id','stateTwo_id','authorized_by.user_id.person')
           ->get();
        return new JsonResponse([
            'status' => 'success',
            'data' => ['projects' => $projects]
        ],200);
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
          ->with('created_by.person','beneficiary_institution_id','stateTwo_id','authorized_by.user_id.person')
          ->get();

      return response()->json([
          'status' => 'success',
          'data' => [
              'projects' => $projects,
          ],
      ]);
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
          ->where('id', '!=', 0)
          ->where('archived', false)
          ->with('created_by.person','beneficiary_institution_id','stateTwo_id','authorized_by.user_id.person')
          ->first();

      if (!$projects) {
          return response()->json([
              'message' => 'Proyecto no encontrada'
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
     * Summary of ArchiveProject
     * @param mixed $id
     * @return JsonResponse
     * Archivar proyecto por el id
     */
    public function archiveProject($id)
    {
      $projects = Project::findOrFail($id);

      $projects->archived = true;
      $projects->archived_at = now();
      $projects->archived_by = auth()->user()->id;
      $projects->save();

      return new JsonResponse([
          'status' => 'success',
          'message' => 'Proyecto archivada correctamente',
          'data' => [
              'projects' => $projects,
          ],
      ], 200);
    }

    /**
     * Summary of restaureProject
     * @param mixed $id
     * @return JsonResponse
     * restarurar proyecto por id
     */
    public function restaureProject($id)
    {
      $projects = Project::findOrFail($id);

      $projects->archived = false;
      $projects->save();

      return new JsonResponse([
          'status' => 'success',
          'message' => 'Solicitud restaurada correctamente',
          'data' => [
              'projects' => $projects,
          ],
      ], 200);
    }


    public function searchProjectByTerm($term = '')
    {
        $projects = Project::where('archived', false)
            ->where(function ($query) use ($term) {
                $query->whereRaw('LOWER(name) like ?', ['%' . strtolower($term) . '%'])
                    ->orWhereHas('beneficiary_institution_id', function ($query) use ($term) {
                        $query->whereRaw('LOWER(name) like ?', ['%' . strtolower($term) . '%'])
                            ->orWhereRaw('LOWER(code) like ?', ['%' . strtolower($term) . '%']);
                    })
                    ->orWhereHas('authorized_by.user_id.person', function ($query) use ($term) {
                        $query->whereRaw('LOWER(names) like ?', ['%' . strtolower($term) . '%']);
                    });
            })
            ->with('created_by.person', 'beneficiary_institution_id', 'stateTwo_id', 'authorized_by.user_id.person')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'projects' => $projects
            ],
        ]);
    }


    public function searchArchivedProjectByTerm($term = '')
    {
        $projects = Project::where('archived', true)
            ->where(function ($query) use ($term) {
                $query->whereRaw('LOWER(name) like ?', ['%' . strtolower($term) . '%'])
                    ->orWhereHas('beneficiary_institution_id', function ($query) use ($term) {
                        $query->whereRaw('LOWER(name) like ?', ['%' . strtolower($term) . '%'])
                            ->orWhereRaw('LOWER(code) like ?', ['%' . strtolower($term) . '%']);
                    })
                    ->orWhereHas('authorized_by.user_id.person', function ($query) use ($term) {
                        $query->whereRaw('LOWER(names) like ?', ['%' . strtolower($term) . '%']);
                    });
            })
            ->with('created_by.person', 'beneficiary_institution_id', 'stateTwo_id', 'authorized_by.user_id.person')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'projects' => $projects
            ],
        ]);
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
}
