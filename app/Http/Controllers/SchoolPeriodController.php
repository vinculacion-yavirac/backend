<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SchoolPeriod;
use Symfony\Component\HttpFoundation\JsonResponse;

class SchoolPeriodController extends Controller
{
   public function getSchoolPeriod()
     {
        $schoolPeriod = SchoolPeriod::all();
        return new JsonResponse([
            'status' => 'success',
            'data' => ['schoolPeriod' => $schoolPeriod]
        ], 200);
    }
}
