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
        $solicitudes = Solicitude:: all();
        $solicitudes->load(['created_by', 'created_by.person']);
        return new JsonResponse([
            'status' => 'success',
            'data' => ['solicitudes' => $solicitudes],
        ], 200);
    }
}
