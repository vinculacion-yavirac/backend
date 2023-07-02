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
    public function getBeneficiaryInstitution(){
        $beneficiaryInstitutions = BeneficiaryInstitution::all();
        $beneficiaryInstitutions->load(['addresses_id.father_code']);
        return new JsonResponse([
            'status' => 'success',
            'data' => [
                'beneficiaryInstitutions' => $beneficiaryInstitutions
            ],
        ],200);
    }
}
