<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Institute;
use Symfony\Component\HttpFoundation\JsonResponse;

class InstituteController extends Controller
{
   public function getInstitute()
     {
        $institute = Institute::all();
        return new JsonResponse([
            'status' => 'success',
            'data' => ['institute' => $institute]
        ], 200);
    }
}
