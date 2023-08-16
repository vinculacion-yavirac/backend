<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Project;

/**
 * @OA\Schema(
 *     schema="Project",
 *     title="Project",
 *     description="Project model",
 *     @OA\Property(property="id", type="integer", format="int64"),
 *     @OA\Property(property="code", type="string", maxLength=20),
 *     @OA\Property(property="name", type="string", maxLength=200),
 *     @OA\Property(property="field", type="string", maxLength=100),
 *     @OA\Property(property="term_execution", type="integer"),
 *     @OA\Property(property="start_date", type="string", format="date-time"),
 *     @OA\Property(property="end_date", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="linking_activity", type="object"),
 *     @OA\Property(property="sectors_intervention", type="object"),
 *     @OA\Property(property="strategic_axes", type="object"),
 *     @OA\Property(property="description", type="string", maxLength=500),
 *     @OA\Property(property="situational_analysis", type="string", maxLength=500),
 *     @OA\Property(property="foundation", type="string", maxLength=500),
 *     @OA\Property(property="justification", type="string", maxLength=500),
 *     @OA\Property(property="direct_beneficiaries", type="object"),
 *     @OA\Property(property="indirect_beneficiaries", type="object"),
 *     @OA\Property(property="schedule", type="string", maxLength=200),
 *     @OA\Property(property="evaluation_monitoring_strategy", type="object"),
 *     @OA\Property(property="bibliographies", type="object"),
 *     @OA\Property(property="attached_project", type="object"),
 *     @OA\Property(property="convention_id", type="integer", format="int64", nullable=true),
 *     @OA\Property(property="school_period_id", type="integer", format="int64", nullable=true),
 *     @OA\Property(property="beneficiary_institution_id", type="integer", format="int64", nullable=true),
 *     @OA\Property(property="career_id", type="integer", format="int64", nullable=true),
 *     @OA\Property(property="sub_line_investigation_id", type="integer", format="int64", nullable=true),
 *     @OA\Property(property="authorized_by", type="integer", format="int64", nullable=true),
 *     @OA\Property(property="made_by", type="integer", format="int64", nullable=true),
 *     @OA\Property(property="approved_by", type="integer", format="int64", nullable=true),
 *     @OA\Property(property="catalogue_id", type="integer", format="int64", nullable=true),
 *     @OA\Property(property="state_id", type="integer", format="int64", nullable=true),
 *     @OA\Property(property="stateTwo_id", type="integer", format="int64", nullable=true),
 *     @OA\Property(property="frequency_id", type="integer", format="int64", nullable=true),
 *     @OA\Property(property="created_by", type="integer", format="int64", nullable=true),
 *     @OA\Property(property="archived", type="boolean", default=false),
 *     @OA\Property(property="archived_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="archived_by", type="integer", format="int64", nullable=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 * )
 *  \OpenApi\Annotations\SecurityScheme
 */
class ProjectController extends Controller
{


    /**
     * @OA\Get(
     *     path="/api/project",
     *     summary="Obtener lista de proyectos",
     *     operationId="getProject",
     *     tags={"Proyecto"},
     *     security={{"bearer":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Respuesta exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="project", type="array", @OA\Items(ref="#/components/schemas/Project"))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="No autorizado.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Recurso no encontrado.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Ocurrió un error en el servidor."),
     *             @OA\Property(property="error", type="string", example="Mensaje de error detallado.")
     *         )
     *     )
     * )
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
     * Get a specific Solicitud by ID.
     *
     * @OA\Get(
     *     path="/api/project/{id}",
     *     summary="Obtener Proyecto por ID",
     *     operationId="getProjectById",
     *     tags={"Proyecto"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del proyecto",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="project", type="object", ref="#/components/schemas/Project")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="No autorizado"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Solicitud not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Proyecto no encontrada"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Error interno del servidor"),
     *             @OA\Property(property="file", type="string"),
     *             @OA\Property(property="line", type="integer"),
     *             @OA\Property(property="errors", type="array", @OA\Items(type="string"))
     *         )
     *     )
     * )
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
          ->with('created_by.person','archived_by.person','beneficiary_institution_id','stateTwo_id','authorized_by.user_id.person')
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

    /**
     * @OA\Delete(
     *     path="/api/project/delete/{id}",
     *     summary="Eliminar un  proyecto",
     *     operationId="deleteProject",
     *     tags={"Proyecto"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del proyecto a eliminar",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Respuesta exitosa al eliminar el proyecto",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Proyecto eliminado correctamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Proyecto no encontrada")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Error de autenticación",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Error al eliminar el proyecto")
     *         )
     *     )
     * )
     */
    public function deleteProject($id)
    {
        try {
            DB::transaction(
                function () use ($id) {

                    $project = Project::find($id);
                    if (!$project) {
                        return response()->json([
                            'message' => 'Proyecto no encontrado'
                        ]);
                    }
                    $project->delete();
                }
            );
            return response()->json([
                'status' => 'success',
                'message' => 'Proyecto eliminado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al eliminar la proyecto: ' . $e->getMessage()
            ], 500);
        }
    }
}
