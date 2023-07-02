<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BeneficiaryInstitution;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BeneficiaryInstitutionsController extends Controller
{
    /**
     * Summary of getBeneficiaryInstitution
     * @return JsonResponse
     * Obtener todas las Instituciones Beneficiarias
     */
    public function getBeneficiaryInstitution()
    {
        $beneficiaryInstitutions = BeneficiaryInstitution::where('id', '!=', 0)
        ->where('archived', false)
        ->with(['addresses_id.father_code'])
        ->get();
        return new JsonResponse([
            'status' => 'success',
            'data' => [
                'beneficiaryInstitutions' => $beneficiaryInstitutions
            ],
        ],200);
    }

    /**
      * Summary of getArchivedBeneficiaryInstitution
      * @return JsonResponse
      * Obtener todas las Institucion Beneficiaria archivadas
      */
    public function getArchivedBeneficiaryInstitution()
    {
        $beneficiaryInstitutions = BeneficiaryInstitution::where('id', '!=', 0)
            ->where('archived', true)
            ->with('addresses_id.father_code')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'beneficiaryInstitutions' => $beneficiaryInstitutions,
            ],
        ]);
    }

    /**
     * Summary of getBeneficiaryInstitutionById
     * @param mixed $id
     * @return JsonResponse
     * Obtener por id una Institucion Beneficiaria
     */
    public function getBeneficiaryInstitutionById($id)
    {
       $beneficiaryInstitutions = BeneficiaryInstitution::where('id', $id)
           ->where('id', '!=', 0)
           ->where('archived', false)
           ->with('addresses_id.father_code')
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
     * Summary of ArchiveBeneficiaryInstitution
     * @param mixed $id
     * @return JsonResponse
     * Archivar una Institucion Beneficiaria
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
     * Summary of restaureBeneficiaryInstitution
     * @param mixed $id
     * @return JsonResponse
     * Restaurar una Institucion Beneficiaria
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
            ->with('addresses_id.father_code')
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
      ->with('addresses_id.father_code')
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
           ->with('addresses_id.father_code')
           ->where(function ($query) use ($term) {
                $query->whereRaw('LOWER(name) like ?', ['%' . strtolower($term) . '%']);
                })
        ->with('addresses_id.father_code')
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
           ->with('addresses_id.father_code')
           ->where(function ($query) use ($term) {
                $query->whereRaw('LOWER(name) like ?', ['%' . strtolower($term) . '%']);
                })
           ->with('addresses_id.father_code')
           ->get();

       return response()->json([
           'status' => 'success',
           'data' => [
               'beneficiaryInstitutions' => $beneficiaryInstitutions
           ],
       ]);
    }


}
