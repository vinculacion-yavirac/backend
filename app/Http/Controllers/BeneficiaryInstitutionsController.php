<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BeneficiaryInstitution;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Schema(
 *     schema="Beneficiary Institutions",
 *     title="Beneficiary Institutions",
 *     description="beneficiary institutions model",
 *     @OA\Property(property="id", type="integer", format="int64"),
 *     @OA\Property(property="ruc", type="string", format="int15", nullable=true),
 *     @OA\Property(property="name", type="string", format="int100", nullable=true),
 *     @OA\Property(property="logo", type="string", format="int20", nullable=true),
 *     @OA\Property(property="state", type="boolean", default=false),
 *     @OA\Property(property="place_location", type="string", format="int200", nullable=true),
 *     @OA\Property(property="postal_code", type="string", format="int20", nullable=true),
 *     @OA\Property(property="parish_id", type="integer", format="int64"),
 *     @OA\Property(property="created_by", type="integer", format="int64", nullable=true),
 *     @OA\Property(property="archived", type="boolean", default=false),
 *     @OA\Property(property="archived_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="archived_by", type="integer", format="int64", nullable=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 * )
 * \OpenApi\Annotations\SecurityScheme
 */

class BeneficiaryInstitutionsController extends Controller
{

    /**
     * Obtener todas las Instituciones Beneficiarias.
     *
     * @OA\Get(
     *     path="/api/beneficiary-institution",
     *     summary="Obtener todas las Instituciones Beneficiarias",
     *     tags={"Instituciones Beneficiarias"},
     *     security={{"bearer":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="beneficiaryInstitutions", type="array",
     *                     @OA\Items(ref="#/components/schemas/Beneficiary Institutions")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
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
    public function getBeneficiaryInstitution()
    {
        $beneficiaryInstitutions = BeneficiaryInstitution::where('id', '!=', 0)
        ->where('archived', false)
        ->with(['parish_id.father_code'])
        ->get();
        return new JsonResponse([
            'status' => 'success',
            'data' => [
                'beneficiaryInstitutions' => $beneficiaryInstitutions
            ],
        ],200);
    }


     /**
     * @OA\Get(
     *     path="/api/beneficiary-institution/archived/list",
     *     summary="Obtener lista de Beneficiary Institutions archivadas",
     *     operationId="getArchivedBeneficiaryInstitution",
     *     tags={"Instituciones Beneficiarias"},
     *     security={{"bearer":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Respuesta exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="Beneficiary Institutions", type="array", @OA\Items(ref="#/components/schemas/Beneficiary Institutions"))
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
     *         description="BeneficiaryInstitutions no encontradas",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="No se encontraron Beneficiary Institutions archivadas."),
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
    public function getArchivedBeneficiaryInstitution()
    {
        try {
            $user = auth()->user();

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No autorizado.',
                ], 401);
            }

            $beneficiaryInstitutions = BeneficiaryInstitution::where('archived', true)
                ->with('parish_id.father_code','archived_by.person')
                ->get();

            if ($beneficiaryInstitutions->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No se encontraron Beneficiary Institutions archivadas.',
                    'data' => [
                        'beneficiaryInstitutions' => $beneficiaryInstitutions,
                    ],
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => [
                    'beneficiaryInstitutions' => $beneficiaryInstitutions,
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
     * Summary of createFoundation
     * @param mixed $id
     * @return JsonResponse
     * Creacion de nueva entidad benificiaria
     */

    public function createFoundation(Request $request)
    {
        try {
            // Crear registro del documento oficial
            $beneficiaryInstitutions = BeneficiaryInstitution::create(array_merge(
                $request->except('files', 'comments'),
                ['created_by' => auth()->user()->id]
            ));
            return response()->json([
                'status' => 'success',
                'data' => [
                    'beneficiaryInstitution' => $beneficiaryInstitutions
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
     * Obtener una Institución Beneficiaria por ID.
     *
     * @OA\Get(
     *     path="/api/beneficiary-institution/{id}",
     *     summary="Obtener una Institución Beneficiaria por ID",
     *     tags={"Instituciones Beneficiarias"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la Institución Beneficiaria",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="beneficiaryInstitutions", ref="#/components/schemas/Beneficiary Institutions")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Institución Beneficiaria no encontrada",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Institución Beneficiaria no encontrada"),
     *         )
     *     )
     * )
     */
    public function getBeneficiaryInstitutionById($id)
    {
       $beneficiaryInstitutions = BeneficiaryInstitution::where('id', $id)
           ->where('id', '!=', 0)
           ->where('archived', false)
           ->with('parish_id.father_code')
           ->first();

       if (!$beneficiaryInstitutions) {
           return response()->json([
               'message' => 'Institucion Beneficiaria no encontrada'
           ]);
       }

       return response()->json([
           'status' => 'success',
           'data' => [
               'beneficiaryInstitutions' => $beneficiaryInstitutions
           ],
       ]);
    }


    /**
     * Buscar Instituciones Beneficiarias por término.
     *
     * @OA\Get(
     *     path="/api/beneficiary-institution/search/term/{term?}",
     *     summary="Buscar Instituciones Beneficiarias por término",
     *     tags={"Instituciones Beneficiarias"},
     *     @OA\Parameter(
     *         name="term",
     *         in="path",
     *         required=false,
     *         description="Término de búsqueda",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="beneficiaryInstitutions", type="array",
     *                     @OA\Items(ref="#/components/schemas/Beneficiary Institutions")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
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
    public function searchBeneficiaryInstitutionByTerm($term = '')
    {
        $beneficiaryInstitutions = BeneficiaryInstitution::where('archived', false)
            ->where(function ($query) use ($term) {
                $query->whereRaw('LOWER(name) like ?', ['%' . strtolower($term) . '%']);
            })
            ->with('parish_id.father_code')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'beneficiaryInstitutions' => $beneficiaryInstitutions
            ],
        ]);
    }

    /**
     * Buscar Instituciones Beneficiarias por término.
     *
     * @OA\Get(
     *     path="/api/beneficiary-institution/archived/search/term/{term?}",
     *     summary="Buscar Instituciones Beneficiarias por término",
     *     operationId="searchBeneficiaryInstitutionArchivedByTerm",
     *     tags={"Instituciones Beneficiarias"},
     *     @OA\Parameter(
     *         name="term",
     *         in="path",
     *         required=false,
     *         description="Término de búsqueda",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="beneficiaryInstitutions", type="array",
     *                     @OA\Items(ref="#/components/schemas/Beneficiary Institutions")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
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
    public function searchBeneficiaryInstitutionArchivedByTerm($term = '')
    {
        $beneficiaryInstitutions = BeneficiaryInstitution::where('archived', true)
            ->where(function ($query) use ($term) {
                $query->whereRaw('LOWER(name) like ?', ['%' . strtolower($term) . '%']);
            })
            ->with('parish_id.father_code')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'beneficiaryInstitutions' => $beneficiaryInstitutions
            ],
        ]);
    }

    /**
     * Filtrar Instituciones Beneficiarias por estado.
     *
     * @OA\Get(
     *     path="/api/beneficiary-institution/filter/state/{state}",
     *     summary="Filtrar Instituciones Beneficiarias por estado",
     *     tags={"Instituciones Beneficiarias"},
     *     @OA\Parameter(
     *         name="state",
     *         in="path",
     *         required=true,
     *         description="Estado de las Instituciones Beneficiarias (activo o inactivo)",
     *         @OA\Schema(type="string", enum={"activo", "inactivo"})
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="beneficiaryInstitutions", type="array",
     *                     @OA\Items(ref="#/components/schemas/Beneficiary Institutions")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
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
    public function filterBeneficiaryInstitutionByStatus($state = '')
    {
      $beneficiaryInstitutions = BeneficiaryInstitution::where('id', '!=', 0)
      ->where('archived', false)
      ->where('state', $state)
      ->with('parish_id.father_code')
      ->get();

      return response()->json([
          'status' => 'success',
          'data' => [
            'beneficiaryInstitutions' => $beneficiaryInstitutions
          ],
      ], 200);
    }


    /**
     * Buscar Instituciones Beneficiarias activas por término.
     *
     * @OA\Get(
     *     path="/api/beneficiary-institution/search/state/activo/{term?}",
     *     summary="Buscar Instituciones Beneficiarias activas por término",
     *     tags={"Instituciones Beneficiarias"},
     *     @OA\Parameter(
     *         name="term",
     *         in="path",
     *         required=false,
     *         description="Término de búsqueda",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="beneficiaryInstitutions", type="array",
     *                     @OA\Items(ref="#/components/schemas/Beneficiary Institutions")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
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
    public function searchActivasByTerm($term = '')
    {
       $beneficiaryInstitutions = BeneficiaryInstitution::where('id', '!=', 0)
           ->where('archived', false)
           ->where('state', true)
           ->with('parish_id.father_code')
           ->where(function ($query) use ($term) {
                $query->whereRaw('LOWER(name) like ?', ['%' . strtolower($term) . '%']);
                })
        ->with('parish_id.father_code')
        ->get();

       return response()->json([
           'status' => 'success',
           'data' => [
               'beneficiaryInstitutions' => $beneficiaryInstitutions
           ],
       ]);
    }

    /**
     * Buscar Instituciones Beneficiarias inactivas por término.
     *
     * @OA\Get(
     *     path="/api/beneficiary-institution/search/state/inactivo/{term?}",
     *     summary="Buscar Instituciones Beneficiarias inactivas por término",
     *     tags={"Instituciones Beneficiarias"},
     *     @OA\Parameter(
     *         name="term",
     *         in="path",
     *         required=false,
     *         description="Término de búsqueda",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="beneficiaryInstitutions", type="array",
     *                     @OA\Items(ref="#/components/schemas/Beneficiary Institutions")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
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
    public function searchInactivaByTerm($term = '')
    {
       $beneficiaryInstitutions = BeneficiaryInstitution::where('id', '!=', 0)
           ->where('archived', false)
           ->where('state', false)
           ->with('parish_id.father_code')
           ->where(function ($query) use ($term) {
                $query->whereRaw('LOWER(name) like ?', ['%' . strtolower($term) . '%']);
                })
           ->with('parish_id.father_code')
           ->get();

       return response()->json([
           'status' => 'success',
           'data' => [
               'beneficiaryInstitutions' => $beneficiaryInstitutions
           ],
       ]);
    }


    /**
     * Restaurar una Institución Beneficiaria por ID.
     *
     * @OA\Put(
     *     path="/api/beneficiary-institution/archive/{id}",
     *     summary="Archivar una Institución Beneficiaria por ID",
     *     tags={"Instituciones Beneficiarias"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la Institución Beneficiaria",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Institución Beneficiaria archivar correctamente"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="beneficiaryInstitutions", ref="#/components/schemas/Beneficiary Institutions")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Institución Beneficiaria no encontrada",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Institución Beneficiaria no encontrada"),
     *         )
     *     )
     * )
     */
    public function archiveBeneficiaryInstitution($id)
    {
        $beneficiaryInstitutions =  BeneficiaryInstitution::findOrFail($id);

        $beneficiaryInstitutions->update([
            $beneficiaryInstitutions->archived = true,
            'archived_at' => now(),
            'archived_by' => auth()->user()->id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Institucion Beneficiaria archivada correctamente',
            'data' => [
                'beneficiaryInstitutions' => $beneficiaryInstitutions,
            ],
        ], 200);
    }


    /**
     * Restaurar una Institución Beneficiaria por ID.
     *
     * @OA\Put(
     *     path="/api/beneficiary-institution/restore/{id}",
     *     summary="Restaurar una Institución Beneficiaria por ID",
     *     tags={"Instituciones Beneficiarias"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la Institución Beneficiaria",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Operación exitosa",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Institución Beneficiaria restaurada correctamente"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="beneficiaryInstitutions", ref="#/components/schemas/Beneficiary Institutions")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Institución Beneficiaria no encontrada",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Institución Beneficiaria no encontrada"),
     *         )
     *     )
     * )
     */
    public function restaureBeneficiaryInstitution($id)
    {
      $beneficiaryInstitutions = BeneficiaryInstitution::findOrFail($id);

      $beneficiaryInstitutions->archived = false;
      $beneficiaryInstitutions->save();

      return new JsonResponse([
          'status' => 'success',
          'message' => 'Institucion Beneficiaria restaurada correctamente',
          'data' => [
              'beneficiaryInstitutions' => $beneficiaryInstitutions
          ],
      ], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/beneficiary-institution/delete/{id}",
     *     summary="Eliminar un Instituciones Beneficiarias",
     *     operationId="deleteBeneficiaryInstitution",
     *     tags={"Instituciones Beneficiarias"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del Instituciones Beneficiarias a eliminar",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Respuesta exitosa al eliminar el Instituciones Beneficiarias",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Instituciones Beneficiarias eliminado correctamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Portafolio no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Instituciones Beneficiarias no encontrada")
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
    public function deleteBeneficiaryInstitution($id)
    {
        try {
            DB::transaction(
                function () use ($id) {

                    $beneficiaryInstitutions =BeneficiaryInstitution::find($id);
                    if (!$beneficiaryInstitutions) {
                        return response()->json([
                            'message' => 'Institución no encontrado'
                        ]);
                    }
                    $beneficiaryInstitutions->delete();
                }
            );
            return response()->json([
                'status' => 'success',
                'message' => 'Institucion Beneficiaria eliminado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al eliminar la Institución: ' . $e->getMessage()
            ], 500);
        }
    }
}
