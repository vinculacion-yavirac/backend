<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    public function getProject(){
        $projects = Project::all();
        //$projects -> load(['foundations','created_by']);
        return new JsonResponse([
            'status' => 'success',
            'data' => ['projects' => $projects]
        ],200);
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


        public function getProjectById($id)
             {
                 $projects = Project::where('id', $id)
                     ->where('id', '!=', 0)
                     ->first();

                 if (!$projects) {
                     return response()->json([
                         'message' => 'Solicitud no encontrada'
                     ]);
                 }

                 $projects->load(['foundations']);
                          $projects->foundations = $projects->foundations()->first();

                          return response()->json([
                              'status' => 'success',
                              'data' => [
                                  'projects' => $projects
                              ],
                          ]);
              }
}
