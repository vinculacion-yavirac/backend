<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Career;
use Symfony\Component\HttpFoundation\JsonResponse;

class CareerController extends Controller
{
   public function getCareer()
     {
        $career = Career::all();
        return new JsonResponse([
            'status' => 'success',
            'data' => ['career' => $career]
        ], 200);
    }
}
