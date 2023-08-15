<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Solicitude;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Schema(
 *     schema="Solicitude",
 *     title="Solicitud",
 *     description="Solicitud model",
 *     @OA\Property(property="id", type="integer", format="int64"),
 *     @OA\Property(property="approval_date", type="string", format="date-time"),
 *     @OA\Property(property="solicitudes_status_id", type="integer", format="int64", nullable=true),
 *     @OA\Property(property="type_request_id", type="integer", format="int64", nullable=true),
 *     @OA\Property(property="project_id", type="integer", format="int64", nullable=true),
 *     @OA\Property(property="created_by", type="integer", format="int64", nullable=true),
 *     @OA\Property(property="archived", type="boolean", default=false),
 *     @OA\Property(property="archived_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="archived_by", type="integer", format="int64", nullable=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 * )
 * \OpenApi\Annotations\SecurityScheme
 */
class SolicitudeController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/solicitud",
     *     summary="Obtener lista de solicitudes",
     *     operationId="getSolicitudes",
     *     tags={"Solicitudes"},
     *     security={{"bearer":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Respuesta exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="solicitudes", type="array", @OA\Items(ref="#/components/schemas/Solicitude"))
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
    public function getSolicitudes()
    {
        try {
            $user = auth()->user();
            
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No autorizado.',
                ], 401);
            }
            
            $solicitudes = Solicitude::with('created_by', 'created_by.person', 'solicitudes_status_id', 'type_request_id', 'project_id')
                ->where('archived', false)
                ->get();

            if ($solicitudes->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Recurso no encontrado.',
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => ['solicitudes' => $solicitudes],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ocurrió un error en el servidor.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Get a specific Solicitud by ID.
     *
     * @OA\Get(
     *     path="/api/solicitud/{id}",
     *     summary="Obtener Solicitud por ID",
     *     tags={"Solicitudes"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the Solicitud",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="solicitudes", type="object", ref="#/components/schemas/Solicitude")
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
     *             @OA\Property(property="message", type="string", example="Solicitud no encontrada"),
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
    public function getSolicitudeById($id)
    {
        try {
            $user = auth()->user();
            
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No autorizado.',
                ], 401);
            }
            
            $solicitudes = Solicitude::with('created_by', 'created_by.person', 'solicitudes_status_id', 'type_request_id', 'project_id')
                ->where('id', $id)
                ->where('archived', false)
                ->first();

            if (!$solicitudes) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Solicitud no encontrada'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => [
                    'solicitudes' => $solicitudes
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error interno del servidor',
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'errors' => [$e->getMessage()],
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/solicitud/archived/list",
     *     summary="Obtener lista de solicitudes archivadas",
     *     operationId="getArchivedSolicitude",
     *     tags={"Solicitudes"},
     *     security={{"bearer":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Respuesta exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="solicitudes", type="array", @OA\Items(ref="#/components/schemas/Solicitude"))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="No autorizado."),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Solicitudes no encontradas",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="No se encontraron solicitudes archivadas."),
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
    public function getArchivedSolicitude()
    {
        try {
            $user = auth()->user();
            
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No autorizado.',
                ], 401);
            }

            $solicitudes = Solicitude::where('archived', true)
                ->with('created_by', 'created_by.person', 'solicitudes_status_id', 'type_request_id')
                ->get();

            if ($solicitudes->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No se encontraron solicitudes archivadas.',
                    'data' => [
                        'solicitudes' => $solicitudes,
                    ],
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => [
                    'solicitudes' => $solicitudes,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ocurrió un error en el servidor.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/solicitud/search/term/{term?}",
     *     summary="Buscar solicitudes por término",
     *     operationId="searchSolicitudeByTerm",
     *     tags={"Solicitudes"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="term?",
     *         in="path",
     *         required=false,
     *         description="Término de búsqueda",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Respuesta exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="solicitudes", type="array", @OA\Items(ref="#/components/schemas/Solicitude"))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="No autorizado."),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Solicitudes no encontradas",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="No se encontraron solicitudes."),
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
    public function searchSolicitudeByTerm($term = '')
    {
        try {
            $user = auth()->user();
            
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No autorizado.',
                ], 401);
            }

            $term = strtolower($term);

            $solicitudes = Solicitude::where('archived', false)
                ->whereHas('created_by.person', function ($query) use ($term) {
                    $query->whereRaw('LOWER(names) like ?', ['%' . $term . '%'])
                        ->orWhereRaw('LOWER(last_names) like ?', ['%' . $term . '%'])
                        ->orWhereRaw('LOWER(identification) like ?', ['%' . $term . '%']);
                })
                ->with('created_by', 'created_by.person', 'solicitudes_status_id', 'type_request_id')
                ->get();

            if ($solicitudes->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No se encontraron solicitudes.',
                    'data' => [
                        'solicitudes' => $solicitudes,
                    ],
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => [
                    'solicitudes' => $solicitudes
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ocurrió un error en el servidor.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * @OA\Get(
     *     path="/api/solicitud/search/archived/term/{term?}",
     *     summary="Buscar solicitudes archivadas por término",
     *     operationId="searchArchivedSolicitudeByTerm",
     *     tags={"Solicitudes"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="term?",
     *         in="path",
     *         required=false,
     *         description="Término de búsqueda",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Respuesta exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="solicitudes", type="array", @OA\Items(ref="#/components/schemas/Solicitude"))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Solicitud incorrecta",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="El término de búsqueda no puede estar vacío."),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="No autorizado."),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Recurso no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="No se encontraron solicitudes archivadas."),
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
    public function searchArchivedSolicitudeByTerm($term = '')
    {
        // Verificar si el término de búsqueda es válido antes de continuar
        if ($term === '') {
            return response()->json([
                'status' => 'error',
                'message' => 'El término de búsqueda no puede estar vacío.',
            ], 400); // Código 400 Bad Request
        }

        try {
            $user = auth()->user();
            
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No autorizado.',
                ], 401);
            }

            $term = strtolower($term);

            $solicitudes = Solicitude::where('archived', true)
                ->whereHas('created_by.person', function ($query) use ($term) {
                    $query->whereRaw('LOWER(names) like ?', ['%' . $term . '%'])
                        ->orWhereRaw('LOWER(last_names) like ?', ['%' . $term . '%'])
                        ->orWhereRaw('LOWER(identification) like ?', ['%' . $term . '%']);
                })
                ->with('created_by', 'created_by.person', 'solicitudes_status_id', 'type_request_id')
                ->get();

            if ($solicitudes->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No se encontraron solicitudes archivadas.',
                    'data' => [
                        'solicitudes' => $solicitudes,
                    ],
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => [
                    'solicitudes' => $solicitudes
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ocurrió un error en el servidor.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/solicitud/filter/value/{value}",
     *     summary="Filtrar solicitudes por tipo de solicitud",
     *     operationId="filterSolicitudeByValue",
     *     tags={"Solicitudes"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="value",
     *         in="path",
     *         required=false,
     *         description="Valor para filtrar las solicitudes por tipo de solicitud",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Respuesta exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="solicitudes", type="array", @OA\Items(ref="#/components/schemas/Solicitude"))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="No autorizado."),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Recurso no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="No se encontraron solicitudes."),
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
    public function filterSolicitudeByValue($value = '')
    {
        try {
            $user = auth()->user();
                
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No autorizado.',
                ], 401);
            }

            $solicitudes = Solicitude::where('archived', false)
                ->whereHas('type_request_id', function ($query) use ($value) {
                    $query->where('catalog_type', 'Tipo Solicitud')
                        ->where('catalog_value', $value);
                })->with('created_by.person', 'solicitudes_status_id', 'type_request_id')->get();

            if ($solicitudes->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No se encontraron solicitudes.',
                    'data' => [
                        'solicitudes' => $solicitudes,
                    ],
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => [
                    'solicitudes' => $solicitudes,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ocurrió un error en el servidor.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/solicitud/filter/status/{status}",
     *     summary="Filtrar solicitudes por estado",
     *     operationId="filterSolicitudeByStatus",
     *     tags={"Solicitudes"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="status",
     *         in="path",
     *         required=false,
     *         description="Estado para filtrar las solicitudes",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Respuesta exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="solicitudes", type="array", @OA\Items(ref="#/components/schemas/Solicitude"))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="No autorizado."),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Recurso no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="No se encontraron solicitudes."),
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
    public function filterSolicitudeByStatus($status = '')
    {
        try {
            $user = auth()->user();
                
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No autorizado.',
                ], 401);
            }

            $solicitudes = Solicitude::where('archived', false)
                ->whereHas('solicitudes_status_id', function ($query) use ($status) {
                    $query->where('catalog_type', 'Estado Solicitud')
                        ->where('catalog_value', $status);
                })->with('created_by.person', 'solicitudes_status_id', 'type_request_id')->get();

            if ($solicitudes->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No se encontraron solicitudes.',
                    'data' => [
                        'solicitudes' => $solicitudes,
                    ],
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => [
                    'solicitudes' => $solicitudes,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ocurrió un error en el servidor.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * @OA\Get(
     *     path="/api/solicitud/search/type/vinculacion/{term?}",
     *     summary="Buscar solicitudes de vinculación por término",
     *     operationId="searchSolicitudeVinculacionByTerm",
     *     tags={"Solicitudes"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="term?",
     *         in="path",
     *         required=false,
     *         description="Término de búsqueda",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Respuesta exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="solicitudes", type="array", @OA\Items(ref="#/components/schemas/Solicitude"))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="No autorizado."),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Recurso no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="No se encontraron solicitudes."),
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
    public function searchSolicitudeVinculacionByTerm($term = '')
    {
        try {
            $user = auth()->user();
                
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No autorizado.',
                ], 401);
            }

            $solicitudes = Solicitude::where('archived', false)
                ->whereHas('type_request_id', function ($query) {
                    $query->where('catalog_type', 'Tipo Solicitud')
                        ->where('catalog_value', 'Vinculación');
                })
                ->where(function ($query) use ($term) {
                    $query->whereHas('created_by.person', function ($query) use ($term) {
                        $query->whereRaw('LOWER(names) like ?', ['%' . strtolower($term) . '%'])
                            ->orWhereRaw('LOWER(last_names) like ?', ['%' . strtolower($term) . '%'])
                            ->orWhereRaw('LOWER(identification) like ?', ['%' . strtolower($term) . '%']);
                    });
                })
                ->with('created_by.person', 'solicitudes_status_id', 'type_request_id')
                ->get();

            if ($solicitudes->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No se encontraron solicitudes.',
                    'data' => [
                        'solicitudes' => [],
                    ],
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => [
                    'solicitudes' => $solicitudes
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ocurrió un error en el servidor.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * @OA\Get(
     *     path="/api/solicitud/search/type/certificado/{term?}",
     *     summary="Buscar solicitudes de certificado por término",
     *     operationId="searchCertificateByTerm",
     *     tags={"Solicitudes"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="term?",
     *         in="path",
     *         required=false,
     *         description="Término de búsqueda",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Respuesta exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="solicitudes", type="array", @OA\Items(ref="#/components/schemas/Solicitude"))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="No autorizado."),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Recurso no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="No se encontraron solicitudes."),
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
    public function searchCertificateByTerm($term = '')
    {
        try {
            $user = auth()->user();
                
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No autorizado.',
                ], 401);
            }

            $solicitudes = Solicitude::where('archived', false)
                ->whereHas('type_request_id', function ($query) {
                    $query->where('catalog_type', 'Tipo Solicitud')
                        ->where('catalog_value', 'Certificado');
                })
                ->where(function ($query) use ($term) {
                    $query->whereHas('created_by.person', function ($query) use ($term) {
                        $query->whereRaw('LOWER(names) like ?', ['%' . strtolower($term) . '%'])
                            ->orWhereRaw('LOWER(last_names) like ?', ['%' . strtolower($term) . '%'])
                            ->orWhereRaw('LOWER(identification) like ?', ['%' . strtolower($term) . '%']);
                    });
                })
                ->with('created_by.person', 'solicitudes_status_id', 'type_request_id')
                ->get();

            if ($solicitudes->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No se encontraron solicitudes.',
                    'data' => [
                        'solicitudes' => [],
                    ],
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => [
                    'solicitudes' => $solicitudes
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ocurrió un error en el servidor.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/solicitud/search/status/pendiente/{term?}",
     *     summary="Buscar solicitudes pendientes por término",
     *     operationId="searchPendienteByTerm",
     *     tags={"Solicitudes"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="term?",
     *         in="path",
     *         required=false,
     *         description="Término de búsqueda",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Respuesta exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="solicitudes", type="array", @OA\Items(ref="#/components/schemas/Solicitude"))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="No autorizado."),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Recurso no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="No se encontraron solicitudes."),
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
    public function searchPendienteByTerm($term = '')
    {
        try {
            $user = auth()->user();
                
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No autorizado.',
                ], 401);
            }

            $solicitudes = Solicitude::where('archived', false)
                ->whereHas('solicitudes_status_id', function ($query) {
                    $query->where('catalog_type', 'Estado Solicitud')
                        ->where('catalog_value', 'Pendiente');
                })
                ->where(function ($query) use ($term) {
                    $query->whereHas('created_by.person', function ($query) use ($term) {
                        $query->whereRaw('LOWER(names) like ?', ['%' . strtolower($term) . '%'])
                            ->orWhereRaw('LOWER(last_names) like ?', ['%' . strtolower($term) . '%'])
                            ->orWhereRaw('LOWER(identification) like ?', ['%' . strtolower($term) . '%']);
                    });
                })
                ->with('created_by.person', 'solicitudes_status_id', 'type_request_id')
                ->get();

            if ($solicitudes->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No se encontraron solicitudes.',
                    'data' => [
                        'solicitudes' => [],
                    ],
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => [
                    'solicitudes' => $solicitudes
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ocurrió un error en el servidor.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/solicitud/search/status/aprobado/{term?}",
     *     summary="Buscar solicitudes aprobadas por término",
     *     operationId="searchAprobadoByTerm",
     *     tags={"Solicitudes"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="term?",
     *         in="path",
     *         required=false,
     *         description="Término de búsqueda",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Respuesta exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="solicitudes", type="array", @OA\Items(ref="#/components/schemas/Solicitude"))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="No autorizado."),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Recurso no encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="No se encontraron solicitudes."),
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
    public function searchAprobadoByTerm($term = '')
    {
        try {
            $user = auth()->user();
                
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No autorizado.',
                ], 401);
            }

            $solicitudes = Solicitude::where('archived', false)
                ->whereHas('solicitudes_status_id', function ($query) {
                    $query->where('catalog_type', 'Estado Solicitud')
                        ->where('catalog_value', 'Aprobado');
                })
                ->where(function ($query) use ($term) {
                    $query->whereHas('created_by.person', function ($query) use ($term) {
                        $query->whereRaw('LOWER(names) like ?', ['%' . strtolower($term) . '%'])
                            ->orWhereRaw('LOWER(last_names) like ?', ['%' . strtolower($term) . '%'])
                            ->orWhereRaw('LOWER(identification) like ?', ['%' . strtolower($term) . '%']);
                    });
                })
                ->with('created_by.person', 'solicitudes_status_id', 'type_request_id')
                ->get();

            if ($solicitudes->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No se encontraron solicitudes.',
                    'data' => [
                        'solicitudes' => [],
                    ],
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => [
                    'solicitudes' => $solicitudes
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ocurrió un error en el servidor.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/solicitud/archive/{id}",
     *     summary="Archivar solicitud por ID",
     *     operationId="archiveSolicitud",
     *     tags={"Solicitudes"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la solicitud",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Respuesta exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Solicitud archivada correctamente"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="solicitudes", ref="#/components/schemas/Solicitude")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="No autorizado."),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Solicitud no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Solicitud no encontrada."),
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
    public function archiveSolicitud($id)
    {
        try {
            $user = auth()->user();
            
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No autorizado.',
                ], 401);
            }

            $solicitudes = Solicitude::findOrFail($id);

            $solicitudes->update([
                'archived' => true,
                'archived_at' => now(),
                'archived_by' => auth()->user()->id,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Solicitud archivada correctamente',
                'data' => [
                    'solicitudes' => $solicitudes,
                ],
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Solicitud no encontrada.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ocurrió un error en el servidor.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * @OA\Put(
     *     path="/api/solicitud/restore/{id}",
     *     summary="Restaurar solicitud por ID",
     *     operationId="restoreSolicitud",
     *     tags={"Solicitudes"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la solicitud",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Respuesta exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Solicitud restaurada correctamente"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="solicitudes", ref="#/components/schemas/Solicitude")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="No autorizado."),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Solicitud no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Solicitud no encontrada."),
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
    public function restoreSolicitud($id)
    {
        try {
            $user = auth()->user();
            
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No autorizado.',
                ], 401);
            }

            $solicitudes = Solicitude::findOrFail($id);

            $solicitudes->update([
                'archived' => false,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Solicitud restaurada correctamente',
                'data' => [
                    'solicitudes' => $solicitudes,
                ],
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Solicitud no encontrada.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ocurrió un error en el servidor.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * @OA\Put(
     *     path="/api/solicitud/assign/{id}",
     *     summary="Asignar solicitud a estudiante y proyecto",
     *     operationId="assignSolicitude",
     *     tags={"Solicitudes"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la solicitud",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos de la solicitud a asignar",
     *         @OA\JsonContent(ref="#/components/schemas/Solicitude")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Respuesta exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Solicitud asignada correctamente"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="solicitudes", ref="#/components/schemas/Solicitude")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="No autorizado."),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Solicitud no encontrada",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Solicitud no encontrada."),
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
    public function assignSolicitude(Request $request, $id)
    {
        try {
            $user = auth()->user();
            
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No autorizado.',
                ], 401);
            }

            $request->validate([
                'approval_date' => 'nullable|date',
                'project_id' => 'required',
            ]);

            DB::beginTransaction();

            $solicitudes = Solicitude::findOrFail($id);

            $solicitudes->update([
                'approval_date' => now(),
                'solicitudes_status_id' => 4,
                'project_id' => $request->project_id,
            ]);

            DB::commit();

            $solicitudes->load(['created_by', 'created_by.person', 'solicitudes_status_id', 'type_request_id', 'project_id']);

            return response()->json([
                'status' => 'success',
                'message' => 'Relación actualizada correctamente',
                'data' => [
                    'solicitudes' => $solicitudes,
                ],
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Solicitud no encontrada.',
            ], 404);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => 'error',
                'message' => 'Error al actualizar la relación: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/solicitud/aprovate-certificado/{id}",
     *     operationId="aprovateCertificado",
     *     tags={"Solicitudes"},
     *     security={{"bearer":{}}},
     *     summary="Aprobar Certificado",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID de la solicitud"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Certificado Aprobado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al actualizar el registro",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */
    public function aprovateCertificado($id)
    {
        try {
            // Obtén la solicitud por su ID
            $solicitud = Solicitude::findOrFail($id);
    
            // Verifica si el type_request_id es diferente de 'Vinculacion' y archived es false
            if ($solicitud->type_request_id !== 1 && !$solicitud->archived) {
                $solicitud->update([
                    'approval_date' => now(),
                    'solicitudes_status_id' => 4,
                ]);
    
                return response()->json(['message' => 'Certificado Aprobado']);
            } else {
                return response()->json(['error' => 'No se puede actualizar la solicitud'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ocurrió un error al actualizar el registro'], 500);
        }
    }
    

    /**
     * @OA\Put(
     *     path="/api/solicitud/disapprove-certificate/{id}",
     *     operationId="disapproveCertificate",
     *     tags={"Solicitudes"},
     *     security={{"bearer":{}}},
     *     summary="Desaprobar solicitud",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID de la solicitud"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Certificado desaprobado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error al actualizar el registro",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string")
     *         )
     *     )
     * )
     */
    public function disapproveCertificate($id)
    {
        try {
            // Obtén la solicitud por su ID
            $solicitud = Solicitude::findOrFail($id);
    
            // Verifica si el type_request_id es diferente de 'Vinculacion' y archived es false
            if ($solicitud->type_request_id !== 1 && !$solicitud->archived) {
                $solicitud->update([
                    'approval_date' => now(),
                    'solicitudes_status_id' => 3,
                ]);
    
                return response()->json(['message' => 'Certificado Desaprobado']);
            } else {
                return response()->json(['error' => 'No se puede actualizar la solicitud'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Ocurrió un error al actualizar el registro'], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/solicitud/create",
     *     summary="Crear una nueva solicitud",
     *     operationId="createSolicitude",
     *     tags={"Solicitudes"},
     *     security={{"bearer":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Solicitude")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Respuesta exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Solicitud creada correctamente"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="solicitud", ref="#/components/schemas/Solicitude")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Solicitud inválida",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Solicitud inválida."),
     *             @OA\Property(property="errors", type="object", example={"approval_date": {"El campo fecha de aprobación es obligatorio."}})
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="No autorizado."),
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
    public function createSolicitude(Request $request)
    {
        try {
            $request->validate([
                'approval_date' => 'nullable|date',
                'solicitudes_status_id' => 'required',
                'type_request_id' => 'required',
                'created_by' => 'required',
            ]);

            $solicitud = Solicitude::create([
                'approval_date' => $request->approval_date,
                'solicitudes_status_id' => $request->solicitudes_status_id,
                'type_request_id' => $request->type_request_id,
                'created_by' => $request->created_by,
                'archived' => false,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Solicitud creada correctamente',
                'data' => [
                    'solicitud' => $solicitud,
                ],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al crear la solicitud: ' . $e->getMessage(),
            ], 500);
        }
    }


    /**
     * @OA\Delete(
     *     path="/api/solicitud/delete/{id}",
     *     summary="Eliminar una solicitud",
     *     operationId="deleteSolicitud",
     *     tags={"Solicitudes"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la solicitud a eliminar",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Respuesta exitosa al eliminar la solicitud",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Solicitud eliminada correctamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Solicitud no encontrada")
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
     *             @OA\Property(property="message", type="string", example="Error al eliminar la solicitud")
     *         )
     *     )
     * )
     */
    public function deleteSolicitud($id)
    {
        try {
            DB::transaction(
                function () use ($id) {

                    $solicitudes = Solicitude::find($id);
                    if (!$solicitudes) {
                        return response()->json([
                            'message' => 'Solicitudes no encontrado'
                        ]);
                    }
                    $solicitudes->delete();
                }
            );
            return response()->json([
                'status' => 'success',
                'message' => 'Solicitud eliminada correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al eliminar la solicitud: ' . $e->getMessage()
            ], 500);
        }
    }
}