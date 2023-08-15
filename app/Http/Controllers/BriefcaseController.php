<?php

namespace App\Http\Controllers;

use App\Models\Briefcase;
use App\Models\Documents;
use App\Models\File;
use App\Models\ProjectParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

/**
 * @OA\Schema(
 *     schema="Portafolio",
 *     title="Portafolio",
 *     description="Portafolio model",
 *     @OA\Property(property="id", type="integer", format="int64"),
 *     @OA\Property(property="observations", type="string"),
 *     @OA\Property(property="state", type="boolean", default=true),
 *     @OA\Property(property="archived", type="boolean", default=false),
 *     @OA\Property(property="archived_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="created_by", type="integer", format="int64", nullable=true),
 *     @OA\Property(property="archived_by", type="integer", format="int64", nullable=true),
 *     @OA\Property(property="project_participant_id", type="integer", format="int64", nullable=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 * )
 * \OpenApi\Annotations\SecurityScheme
 */

class BriefcaseController extends Controller
{
   

    /**
     * @OA\Get(
     *     path="/api/briefcase",
     *     summary="Obtener lista de todos los Portafolios",
     *     operationId="getBriefcase",
     *     tags={"Portafolio"},
     *     security={{"bearer":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Respuesta exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="briefcases", type="array", @OA\Items(ref="#/components/schemas/Portafolio"))
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
    public function getBriefcase()
    {
        $briefcases = Briefcase::where('id', '>', 0)
            ->where('archived', false)
            ->with('project_participant_id.participant_id.person','created_by.person')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => ['briefcases' => $briefcases],
        ]);
    }


    /**
     * @OA\Get(
     *     path="/api/briefcase/{id}",
     *     summary="Obtener Portafolio por su id",
     *     operationId="getBriefcaseById",
     *     tags={"Portafolio"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la solicitud (briefcase)",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Respuesta exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="briefcase", ref="#/components/schemas/Portafolio")
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
     public function getBriefcaseById($id)
    {
        $briefcases = Briefcase::where('id', $id)
            ->where('archived', false)
            ->with('project_participant_id.participant_id.person','created_by.person','project_participant_id.project_id.beneficiary_institution_id','files','documents','project_participant_id.project_id.career_id')
            ->first();

        if (!$briefcases) {
            return response()->json([
                'message' => 'Portafolio no encontrado'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'briefcases' => $briefcases
            ],
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/briefcase/archived/list",
     *     summary="Obtener lista de portafolios archivadas",
     *     operationId="getArchivedBriefcase",
     *     tags={"Portafolio"},
     *     security={{"bearer":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Respuesta exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="briefcases", type="array", @OA\Items(ref="#/components/schemas/Portafolio"))
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
    public function getArchivedBriefcase()
    {
        $briefcases = Briefcase::where('archived', true)
            ->with('project_participant_id.participant_id.person','created_by.person')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'briefcases' => $briefcases,
            ],
        ]);
    }


    /**
     * @OA\Get(
     *     path="/api/briefcase/search/term/{term?}",
     *     summary="Buscar Portafolio por término",
     *     operationId="searchBriefcaseByTerm",
     *     tags={"Portafolio"},
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
     *                 @OA\Property(property="briefcases", type="array", @OA\Items(ref="#/components/schemas/Portafolio"))
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
    public function searchBriefcaseByTerm($term = '')
    {
        $lowerTerm = strtolower($term);

        $briefcases = Briefcase::where('archived', false)
        ->whereHas('created_by.person', function ($query) use ($term) {
            $query->whereRaw('LOWER(names) like ?', ['%' . $term . '%'])
                ->orWhereRaw('LOWER(last_names) like ?', ['%' . $term . '%'])
                ->orWhereRaw('LOWER(identification) like ?', ['%' . $term . '%']);
        })
        ->with('created_by.person')
        ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'briefcases' => $briefcases
            ],
        ]);
    }


    /**
     * @OA\Get(
     *     path="/api/briefcase/search/archived/term/{term?}",
     *     summary="Buscar Portafolio archivado por término",
     *     operationId="searchArchivedBriefcaseByTerm",
     *     tags={"Portafolio"},
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
     *                 @OA\Property(property="briefcases", type="array", @OA\Items(ref="#/components/schemas/Portafolio"))
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
    public function searchArchivedBriefcaseByTerm($term = '')
    {
        $briefcases = Briefcase::where('archived', true)
        ->whereHas('created_by.person', function ($query) use ($term) {
            $query->whereRaw('LOWER(names) like ?', ['%' . $term . '%'])
                ->orWhereRaw('LOWER(last_names) like ?', ['%' . $term . '%'])
                ->orWhereRaw('LOWER(identification) like ?', ['%' . $term . '%']);
        })
        ->with('created_by.person')
        ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'briefcases' => $briefcases
            ],
        ]);
    }


    /**
     * @OA\Get(
     *     path="/api/briefcase/filter/state/{state}",
     *     summary="Filtrar Portafolio por estado",
     *     operationId="filterBriefcaseByStatus",
     *     tags={"Portafolio"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="state",
     *         in="path",
     *         required=false,
     *         description="Estado del Portafolio por true o false",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Respuesta exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="briefcases", type="array", @OA\Items(ref="#/components/schemas/Portafolio"))
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
    public function filterBriefcaseByStatus($state = '')
    {
        $briefcases = Briefcase::where('archived', false)
            ->where('state', $state)
            ->with('created_by.person')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'briefcases' => $briefcases
            ],
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/briefcase/search/state/aprobado/{term?}",
     *     summary="Buscar Portafolio aprobado por término",
     *     operationId="searchBriefcaseAprobadoByTerm",
     *     tags={"Portafolio"},
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
     *                 @OA\Property(property="briefcases", type="array", @OA\Items(ref="#/components/schemas/Portafolio"))
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
    public function searchBriefcaseAprobadoByTerm($term = '')
    {
        $briefcases = Briefcase::where('archived', false)
            ->where('state', true)
            ->whereHas('created_by.person', function ($query) use ($term) {
                $query->where('names', 'ILIKE', '%' . $term . '%')
                    ->orWhere('last_names', 'ILIKE', '%' . $term . '%')
                    ->orWhere('identification', 'ILIKE', '%' . $term . '%');
            })
            ->with('created_by.person')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'briefcases' => $briefcases
            ],
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/briefcase/search/state/pendiente/{term?}",
     *     summary="Buscar Portafolio pendiente por término",
     *     operationId="searchBriefcasePendienteByTerm",
     *     tags={"Portafolio"},
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
     *                 @OA\Property(property="briefcases", type="array", @OA\Items(ref="#/components/schemas/Portafolio"))
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
    public function searchBriefcasePendienteByTerm($term = '')
    {
        $briefcases = Briefcase::where('archived', false)
            ->where('state', false)
            ->whereHas('created_by.person', function ($query) use ($term) {
                $query->where('names', 'ILIKE', '%' . $term . '%')
                    ->orWhere('last_names', 'ILIKE', '%' . $term . '%')
                    ->orWhere('identification', 'ILIKE', '%' . $term . '%');
            })
            ->with('created_by.person')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'briefcases' => $briefcases
            ],
        ]);
    }


    /**
     * @OA\Put(
     *     path="/api/briefcase/archive/{id}",
     *     summary="Archivar Portafolio",
     *     operationId="archiveBriefcase",
     *     tags={"Portafolio"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del Portafolio a archivar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Respuesta exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Portafolio archivado correctamente"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="briefcase", ref="#/components/schemas/Portafolio")
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
    public function archiveBriefcase($id)
    {
        $briefcase = Briefcase::findOrFail($id);

        $briefcase->update([
            'archived' => true,
            'archived_at' => now(),
            'archived_by' => auth()->user()->id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Portafolio archivado correctamente',
            'data' => [
                'briefcase' => $briefcase,
            ],
        ], 200);
    }

    /**
     * @OA\Put(
     *     path="/api/briefcase/restore/{id}",
     *     summary="Restaurar Portafolio",
     *     operationId="restoreBriefcase",
     *     tags={"Portafolio"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del Portafolio a restaurar",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Respuesta exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Portafolio restaurado correctamente"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="briefcase", ref="#/components/schemas/Portafolio")
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
    public function restoreBriefcase($id)
    {
        $briefcase = Briefcase::findOrFail($id);

        $briefcase->update([
            'archived' => false,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Solicitud restaurada correctamente',
            'data' => [
                'briefcase' => $briefcase,
            ],
        ], 200);
    }




//-----------------------------------------------------------------------------------------

    /**
     * Summary of updateBriefcase
     * @param \Illuminate\Http\Request $request
     * @param mixed $id
     * @throws \Exception
     * @return \Illuminate\Http\JsonResponse
     * Actualizar transaccion de documentos archivos y portafolio
     */
    public function updateBriefcase(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            // Obtener los datos del formulario
            $requestData = $request->all();

            // Obtener el portafolio existente
            $briefcase = Briefcase::findOrFail($id);

            // Actualizar los datos del portafolio
            $briefcase->update($requestData['briefcases']);

            // Verificar si se actualizó correctamente el portafolio
            if (!$briefcase) {
                throw new \Exception("No se pudo actualizar el portafolio.");
            }

            // Actualizar los documentos
            $documents = $requestData['documents'];

            foreach ($documents as $documentData) {
                // Obtener el documento existente o crear uno nuevo si no existe
                $document = Documents::updateOrCreate(['id' => $documentData['id']], $documentData);

                // Verificar si se actualizó correctamente el documento
                if (!$document) {
                    throw new \Exception("No se pudo actualizar el documento.");
                }

                // Obtener los archivos relacionados con el documento
                $files = $documentData['files'];

                foreach ($files as $fileData) {
                    // Obtener el archivo existente o crear uno nuevo si no existe
                    $file = File::updateOrCreate(['id' => $fileData['id']], $fileData);

                    // Verificar si se actualizó correctamente el archivo
                    if (!$file) {
                        throw new \Exception("No se pudo actualizar el archivo.");
                    }

                    // Establecer la relación entre el archivo y el documento
                    $file->briefcases()->associate($briefcase);
                    $file->documents()->associate($document);
                    $file->save();
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'La relación entre el portafolio y los documentos se ha actualizado exitosamente.'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }


// prueba aaaaaaaaaaaaaaaaaaaa
/*
public function create(Request $request)
{
    try {
        DB::beginTransaction();

        $user = Auth::user();
        $now = Carbon::now();

        $projectParticipant = ProjectParticipant::where('participant_id', $user->id)
                            ->first();

        if (!$projectParticipant) {
            return response()->json([
                'message' => 'Aun no eres asignado a un proyecto.',
                'data' => $projectParticipant,
            ], 400);
        }

        // Check if the user already has an unarchived briefcase
        $existingBriefcase = Briefcase::where('created_by', $user->id)
            ->where('archived', false)
            ->first();

        if ($existingBriefcase) {
            return response()->json([
                'message' => 'Ya has creado un portafolio no archivado previamente.',
                'data' => $existingBriefcase,
            ], 400);
        }

        $briefcase = Briefcase::create([
            'observations' => $request->input('observations'),
            'state' => $request->input('state', false),
            'created_by' => $user->id,
            'created_at' => $now,
            'project_participant_id' => $projectParticipant->id,
        ]);

        DB::commit();

        return response()->json([
            'status' => 'success',
            'data' => [
                'briefcase' => $briefcase,
            ],
            'message' => 'Portafolio creado exitosamente',
        ]);
    } catch (\Exception $e) {
        DB::rollback();
        return response()->json([
            'message' => 'Ocurrió un error al crear el portafolio y guardar los archivos.',
            'error' => $e->getMessage(),
        ], 500);
    }
}
*/

public function create(Request $request)
{
    try {
        DB::beginTransaction(); // Start transaction

        $user = Auth::user();
        $now = Carbon::now();

        $projectParticipant = ProjectParticipant::where('participant_id', $user->id)
                            ->first();

        if (!$projectParticipant) {
            return response()->json([
                'message' => 'Aun no eres asignado a un proyecto.',
                'data' => $projectParticipant,
            ], 400);
        }

        // Check if the user already has an unarchived briefcase
        $existingBriefcase = Briefcase::where('created_by', $user->id)
            ->where('archived', false)
            ->first();

        if ($existingBriefcase) {
            return response()->json([
                'message' => 'Ya has creado un portafolio no archivado previamente.',
                'data' => $existingBriefcase,
            ], 400);
        }

        $briefcase = Briefcase::create([
            'observations' => $request->input('observations'),
            'state' => $request->input('state', false),
            'created_by' => $user->id,
            'created_at' => $now,
            'project_participant_id' => $projectParticipant->id,
        ]);

        // Call the file upload function
        $this->uploadFilesBriefcases($request, $briefcase->id);

        DB::commit(); // Commit the transaction

        return response()->json([
            'status' => 'success',
            'data' => [
                'briefcase' => $briefcase,
            ],
            'message' => 'Portafolio creado exitosamente',
        ]);
    } catch (\Exception $e) {
        DB::rollback(); // Rollback the transaction
        return response()->json([
            'message' => 'Ocurrió un error al crear el portafolio y guardar los archivos.',
            'error' => $e->getMessage(),
        ], 500);
    }
}





public function uploadFilesBriefcases(Request $request, $idBriefcase)
{
    $response = [
        'status' => '',
        'message' => '',
        'files' => []
    ];

    if (!$request->hasFile('files')) {
        $response['status'] = 'error';
        $response['message'] = 'No se encontraron archivos en la solicitud';
        return response()->json($response, 400);
    }

    $files = $request->file('files');
    $names = $request->input('names');
    $types = $request->input('types');
    $documentIds = $request->input('document_ids');

    $newFiles = [];

    foreach ($files as $index => $file) {
        if ($file->isValid()) {
            $fileName = $names[$index];
            $fileContent = base64_encode(file_get_contents($file));
            $fileSize = $file->getSize();
            $observation = ''; // Agrega aquí el valor para el campo 'observation'
            $state = 0; // Agrega aquí el valor para el campo 'state'
            $document_id = $documentIds[$index];
            $name = $fileName;
            $fileType = $types[$index];

            $newFile = File::create([
                'name' => $name,
                'type' => $fileType,
                'content' => $fileContent,
                'size' => $fileSize,
                'observation' => $observation,
                'state' => $state,
                'briefcase_id' => $idBriefcase,
                'document_id' => $document_id,
            ]);

            $newFiles[] = $newFile;
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Uno o más archivos no son válidos';
            return response()->json($response, 400);
        }
    }

    $response['status'] = 'success';
    $response['message'] = 'Los archivos se subieron correctamente';
    $response['files'] = $newFiles;

    return response()->json($response, 200);
}

}
