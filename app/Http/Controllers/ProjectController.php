<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Project;


/**
 * @OA\Schema(
 *     schema="Project",
 *     title="Project",
 *     description="Project model",
 *     @OA\Property(property="id", type="integer", format="int64"),
 *     @OA\Property(property="code", type="string"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="name_institute", type="string"),
 *     @OA\Property(property="cicle", type="string"),
 *     @OA\Property(property="address", type="string"),
 *     @OA\Property(property="Modality", type="string"),
 *     @OA\Property(property="field", type="string"),
 *     @OA\Property(property="term_execution", type="integer", format="int64"),
 *     @OA\Property(property="start_date", type="string", format="date-time"),
 *     @OA\Property(property="end_date", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="linking_activity", type="string", format="json"),
 *     @OA\Property(property="sectors_intervention", type="string", format="json"),
 *     @OA\Property(property="strategic_axes", type="string", format="json"),
 *     @OA\Property(property="description", type="string"),
 *     @OA\Property(property="situational_analysis", type="string"),
 *     @OA\Property(property="foundation", type="string"),
 *     @OA\Property(property="justification", type="string"),
 *     @OA\Property(property="direct_beneficiaries", type="string", format="json"),
 *     @OA\Property(property="indirect_beneficiaries", type="string", format="json"),
 *     @OA\Property(property="schedule", type="string"),
 *     @OA\Property(property="evaluation_monitoring_strategy", type="string", format="json"),
 *     @OA\Property(property="bibliographies", type="string", format="json"),
 *     @OA\Property(property="attached_project", type="string", format="json"),
 *     @OA\Property(property="convention_id", type="string", format="int64", nullable=true),
 *     @OA\Property(property="school_period_id", type="string", format="int64", nullable=true),
 *     @OA\Property(property="beneficiary_institution_id", type="string", format="int64", nullable=true),
 *     @OA\Property(property="career_id", type="string", format="int64", nullable=true),
 *     @OA\Property(property="sub_line_investigation_id", type="string", format="int64", nullable=true),
 *     @OA\Property(property="authorized_by", type="string", format="int64", nullable=true),
 *     @OA\Property(property="made_by", type="string", format="int64", nullable=true),
 *     @OA\Property(property="approved_by", type="string", format="int64", nullable=true),
 *     @OA\Property(property="catalogue_id", type="string", format="int64", nullable=true),
 *     @OA\Property(property="state_id", type="string", format="int64", nullable=true),
 *     @OA\Property(property="stateTwo_id", type="string", format="int64", nullable=true),
 *     @OA\Property(property="frequency_id", type="string", format="int64", nullable=true),
 *     @OA\Property(property="created_by", type="string", format="int64", nullable=true),
 *     @OA\Property(property="archived", type="boolean", default="false"),
 *     @OA\Property(property="archived_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="archived_by", type="string", format="int64", nullable=true),
 *     @OA\Property(property="created_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="updated_at", type="string", format="date-time", nullable=true),
 * )
 * \OpenApi\Annotations\SecurityScheme
 */


class ProjectController extends Controller
{

/**
 * @OA\Get(
 *     path="/api/project",
 *     summary="Obtener proyectos",
 *     tags={"Proyectos"},
 *     description="Obtiene una lista de proyectos activos.",
 *     security={ {"bearerAuth": {} } },
 *     @OA\Response(
 *         response=200,
 *         description="Operación exitosa",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="success"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="projects", type="array",
 *                     @OA\Items(ref="#/components/schemas/Project")
 *                 )
 *             )
 *         )
 *     ),
 * @OA\Response(
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
 * @OA\Get(
 *     path="/api/project/{id}",
 *     summary="Obtener proyecto por ID",
 *     tags={"Proyectos"},
 *     description="Obtiene un proyecto por su ID.",
 *     security={ {"bearerAuth": {} } },
 *     @OA\Parameter(
 *         name="id",
 *         description="ID del proyecto",
 *         required=true,
 *         in="path",
 *         @OA\Schema(type="integer", format="int64")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Operación exitosa",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="success"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="projects", ref="#/components/schemas/Project")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Proyecto no encontrado",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Proyecto no encontrado")
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


/**
 * @OA\Post(
 *     path="/api/project/create",
 *     summary="Crear proyecto",
 *     tags={"Proyectos"},
 *     description="Crea un nuevo proyecto.",
 *     security={ {"bearerAuth": {} } },
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(ref="#/components/schemas/Project")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Proyecto creado exitosamente",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="success"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="proyect", ref="#/components/schemas/Project")
 *             ),
 *             @OA\Property(property="message", type="string", example="Proyecto creado con éxito")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Error de solicitud",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Error de validación de entrada.")
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
 * @OA\Put(
 *     path="/api/project/updateProyectBeneficiaryInstitution/{id}",
 *     summary="Actualizar institución beneficiaria de un proyecto",
 *     tags={"Proyectos"},
 *     description="Actualiza la institución beneficiaria de un proyecto específico.",
 *     security={ {"bearerAuth": {} } },
 *     @OA\Parameter(
 *         name="id",
 *         description="ID del proyecto",
 *         required=true,
 *         in="path",
 *         @OA\Schema(type="integer", format="int64")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="beneficiary_institution_id", type="integer", format="int64")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Actualización exitosa",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="success"),
 *             @OA\Property(property="message", type="string", example="Portafolio actualizado con éxito")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Proyecto no encontrado",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="No se encontró el proyecto especificado")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Error de solicitud",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Error de validación de entrada.")
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

    public function updateProyectBeneficiaryInstitution(Request $request, $id)
    {


        $proyect = Project::find($id);
        if (!$proyect) {
            return response()->json([
                'message' => 'No se encontró el oficio especificado'
            ], 404);
        }

        $proyect->beneficiary_institution_id = $request->beneficiary_institution_id;

        try {
            $proyect->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Portafolio actualizado con éxito'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar el Portafolio: ' . $e->getMessage()
            ]);
        }
    }


    /**
 * @OA\Get(
 *     path="/api/project/archived/list",
 *     summary="Obtener proyectos archivados",
 *     tags={"Proyectos"},
 *     description="Obtiene una lista de proyectos archivados.",
 *     security={ {"bearerAuth": {} } },
 *     @OA\Response(
 *         response=200,
 *         description="Operación exitosa",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="success"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="projects", type="array",
 *                     @OA\Items(ref="#/components/schemas/Project")
 *                 )
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
 * @OA\Get(
 *     path="/api/project/search/term/{term?}",
 *     summary="Buscar proyectos por término",
 *     tags={"Proyectos"},
 *     description="Busca proyectos por un término en el nombre, institución beneficiaria o nombre del autorizado.",
 *     security={ {"bearerAuth": {} } },
 *     @OA\Parameter(
 *         name="term",
 *         description="Término de búsqueda",
 *         required=false,
 *         in="path",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Búsqueda exitosa",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="success"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="projects", type="array",
 *                     @OA\Items(ref="#/components/schemas/Project")
 *                 )
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
 * @OA\Get(
 *     path="/api/project/search/archived/term/{term?}",
 *     summary="Buscar proyectos archivados por término",
 *     tags={"Proyectos"},
 *     description="Busca proyectos archivados por un término en el nombre, institución beneficiaria o nombre del autorizado.",
 *     security={ {"bearerAuth": {} } },
 *     @OA\Parameter(
 *         name="term",
 *         description="Término de búsqueda",
 *         required=false,
 *         in="path",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Búsqueda exitosa",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="success"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="projects", type="array",
 *                     @OA\Items(ref="#/components/schemas/Project")
 *                 )
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
 * @OA\Put(
 *     path="/api/project/archive/{id}",
 *     summary="Archivar proyecto",
 *     tags={"Proyectos"},
 *     description="Archiva un proyecto específico.",
 *     security={ {"bearerAuth": {} } },
 *     @OA\Parameter(
 *         name="id",
 *         description="ID del proyecto",
 *         required=true,
 *         in="path",
 *         @OA\Schema(type="integer", format="int64")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Archivado exitoso",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="success"),
 *             @OA\Property(property="message", type="string", example="Proyecto archivado correctamente"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="project", ref="#/components/schemas/Project")
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
 *         description="Proyecto no encontrado",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="No se encontró el proyecto especificado")
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
 * @OA\Put(
 *     path="/api/project/restore/{id}",
 *     summary="Restaurar proyecto",
 *     tags={"Proyectos"},
 *     description="Restaura un proyecto archivado.",
 *     security={ {"bearerAuth": {} } },
 *     @OA\Parameter(
 *         name="id",
 *         description="ID del proyecto",
 *         required=true,
 *         in="path",
 *         @OA\Schema(type="integer", format="int64")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Restauración exitosa",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="success"),
 *             @OA\Property(property="message", type="string", example="Proyecto restaurado correctamente"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="project", ref="#/components/schemas/Project")
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
 *         description="Proyecto no encontrado",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="No se encontró el proyecto especificado")
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


    /**
 * @OA\Get(
 *     path="/api/project/foundation/{value}",
 *     summary="Obtener proyecto por fundación",
 *     tags={"Proyectos"},
 *     description="Obtiene un proyecto por su fundación.",
 *     security={ {"bearerAuth": {} } },
 *     @OA\Parameter(
 *         name="project",
 *         description="ID numérico o nombre del proyecto",
 *         required=true,
 *         in="path",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Operación exitosa",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="success"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="project", ref="#/components/schemas/Project")
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
 *         description="Proyecto no encontrado",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="Proyecto no encontrado.")
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
