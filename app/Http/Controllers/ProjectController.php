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
    public function getProject()
    {
        $projects = Project::where('archived', false)
            ->with('created_by.person', 'beneficiary_institution_id', 'stateTwo_id', 'authorized_by.user_id.person','career_id')
            ->get();

        return response()->json([
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
            ->where('archived', false)
            ->with('created_by.person', 'beneficiary_institution_id', 'stateTwo_id', 'authorized_by.user_id.person','career_id')
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





    public function createProyect(Request $request)
    {
        try {
            // Crear registro del documento oficial
            $proyect = Project::create(array_merge(
                $request->except('files', 'comments'),
                ['created_by' => auth()->user()->id]
            ));
            return response()->json([
                'status' => 'success',
                'data' => [
                    'proyect' => $proyect
                ],
                'message' => 'Proyecto creado con éxito'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear el proyecto: ' . $e->getMessage()
            ]);
        }
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
                    ->orWhereHas('authorized_by.user_id.person', function ($query) use ($lowerTerm) {
                        $query->whereRaw('LOWER(names) like ?', ['%' . $lowerTerm . '%']);
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
                    ->orWhereHas('authorized_by.user_id.person', function ($query) use ($lowerTerm) {
                        $query->whereRaw('LOWER(names) like ?', ['%' . $lowerTerm . '%']);
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
}
