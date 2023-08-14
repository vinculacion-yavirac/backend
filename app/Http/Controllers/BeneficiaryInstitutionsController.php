<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BeneficiaryInstitution;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *     schema="Beneficiary Institutions",
 *     title="Beneficiary Institutions",
 *     description="Beneficiary Institutions model",
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
     * Obtener todas las Instituciones Beneficiarias archivadas.
     *
     * @OA\Get(
     *     path="/api/beneficiary-institution/archived",
     *     summary="Obtener todas las Instituciones Beneficiarias archivadas",
     *     tags={"Instituciones Beneficiarias"},
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
    public function getArchivedBeneficiaryInstitution()
    {
        $beneficiaryInstitutions = BeneficiaryInstitution::where('id', '!=', 0)
            ->where('archived', true)
            ->with('parish_id.father_code')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'beneficiaryInstitutions' => $beneficiaryInstitutions,
            ],
        ]);
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
     * Archivar una Institución Beneficiaria por ID.
     *
     * @OA\Put(
     *     path="/api/beneficiary-institution/archive/{id}",
     *     summary="Archivar una Institución Beneficiaria por ID",
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
     *             @OA\Property(property="message", type="string", example="Institución Beneficiaria archivada correctamente"),
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
      $beneficiaryInstitutions = BeneficiaryInstitution::findOrFail($id);

      $beneficiaryInstitutions->archived = true;
      $beneficiaryInstitutions->archived_at = now();
      $beneficiaryInstitutions->archived_by = auth()->user()->id;
      $beneficiaryInstitutions->save();

      return new JsonResponse([
          'status' => 'success',
          'message' => 'Institucion Beneficiaria archivada correctamente',
          'data' => [
              'beneficiaryInstitutions' => $beneficiaryInstitutions
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
     * Summary of searchBeneficiaryInstitutionByTerm
     * @param mixed $term
     * @return JsonResponse
     * Buscador
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
     * Summary of searchActivasByTerm
     * @param mixed $term
     * @return JsonResponse
     * Buscar totas las instituciones activas
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


}
