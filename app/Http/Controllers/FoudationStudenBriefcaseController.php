<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Briefcase;
use App\Models\FoudationStudenBriefcase;
use App\Models\Foundation;
use App\Models\Project;
use App\Models\Solicitude;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\JsonResponse;

class FoudationStudenBriefcaseController extends Controller
{
    public function getFoundationSolicitud(){
        $foundationsSolicitudes = FoudationStudenBriefcase::all();

        $foundationsSolicitudes->load(['foundations','solicitudes','briefcases']);

        return new JsonResponse([
            'status' => 'success',
            'data' => ['foundationsSolicitudes' => $foundationsSolicitudes],
        ],200);
    }


    // public function createFoundationSolicitud(Request $request)
    // {
    //     $request->validate([
    //         'foundations' => 'required|integer',
    //         'solicitudes' => 'required|integer',
    //         'projects' => 'required|integer',
    //         'briefcases' => 'required|integer',
    //     ]);

    //     try {
    //         // Crear registro del documento oficial
    //         $foundationsSolicitudes = FoudationStudenBriefcase::create(array_merge(
    //             $request->('foundations'),
    //             // ['created_by' => auth()->user()->id]
    //         ));

    //         // Guardar comentario
    //         // if ($request->comment){
    //         //     Comment::create([
    //         //         'comment' => $request->comment,
    //         //         'foundations' => $foundations->id,
    //         //         'created_by' => auth()->user()->id
    //         //     ]);
    //         // }


    //         return response()->json([
    //             'status' => 'success',
    //             'data' => [
    //                 'foundationsSolicitudes' => $foundationsSolicitudes
    //             ],
    //             'message' => 'Fundación creada con éxito'
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'message' => 'Error al crear la Fundación: ' . $e->getMessage()
    //         ]);
    //     }
    // }


    public function createFoundationSolicitud(Request $request)
    {
        $request->validate([
            'foundations' => 'required',
            'solicitudes' => 'required',
            'projects' => 'required',
            'briefcases' => 'required',
        ]);

        try {
            DB::transaction(
                function () use ($request) {
                    $fundations = Foundation::create($request->fundations);
                    $solicitudes = Solicitude::create($request->solicitudes);
                    $projects =Project::create($request->projects);
                    $briefcases = Briefcase::create($request->briefcases);
                    $user = FoudationStudenBriefcase::create([
                        $request ->all(),
                        'foundations' => $fundations->id,
                        'solicitudes' => $solicitudes->id,
                        'projects' => $projects->id,
                        'briefcases' => $briefcases->id,
                    ]);
                    $user->save();
                }
            );
            return response()->json([
                'status' => 'success',
                'message' => 'Usuario creado con éxito'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear el usuario: ' . $e->getMessage()
            ]);
        }
    }
}
