<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Models\Solicitude;

class SolicitudeController extends Controller
{
    public function getSolicitude()
    {
        $solicitude = Solicitude:: all();
        
        return new JsonResponse([
            'status' => 'success',
            'data' => ['solicitude' => $solicitude],
        ], 200);
    }
}
