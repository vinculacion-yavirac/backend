<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProjectParticipant;

class ProjectParticipantController extends Controller
{
         public function getProyectParticipant(){
                $projectParticipants = ProjectParticipant::all();
                $projectParticipants -> load(['project','participant_id','participant_id.person']);
                return response()->json([
                    'status' => 'success',
                    'message' => 'Relación actualizada correctamente',
                    'data' => [
                        'projectParticipants' => $projectParticipants,
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
