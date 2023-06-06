<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Integrantes;
use App\Models\Solicitude;
use Symfony\Component\HttpFoundation\JsonResponse;

class IntegranteController extends Controller
{
    public function index()
    {
         $projects = Project::find($proyectoId);
         $solicitudes = Solicitude::whereIn('id', $solicitudIds)->get();

         return new JsonResponse([
            'status' => 'success',
            'data' =>[
                'integrantes' => $integrantes
            ],
         ],200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'solicitude_id' => 'required',
            'project_id' => 'required',
            // Validación adicional si es necesario
        ]);
        // Busca el proyecto y la solicitud
        $projects = Project::findOrFail($request->project_id);
        $solicitudes = Solicitude::findOrFail($request->solicitude_id);

        // Asigna la solicitud al proyecto
        $projects->solicitudes()->attach($solicitudes);

        return response()->json([
            'status' => 'success',
            'message' => 'Estudiante asignado'
         ]);
    }
}
