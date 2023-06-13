<?php

namespace App\Http\Controllers;

use App\Models\Briefcase;
use Illuminate\Http\Request;
use App\Models\Comment;
use Symfony\Component\HttpFoundation\JsonResponse;

class BriefcaseController extends Controller
{
   /**
    * Summary of getBriefcase
    * @return JsonResponse
    */
   public function getBriefcase()
     {
        $briefcases = Briefcase::all();

        $briefcases->load(['project_participant_id.participant_id.person']);

        return new JsonResponse([
            'status' => 'success',
            'data' => ['briefcases' => $briefcases],
/*             'total' => count(
                $briefcase) */
        ], 200);
    }

    public function getBriefcaseById($id)
    {
        $briefcases = Briefcase::where('id', $id)->get();

        $briefcases->load(['comments', 'files']);

        return new JsonResponse([
            'status' => 'success',
            'data' => ['briefcases#' . $id => $briefcases]
        ], 200);
    }

    public function searchBriefcaseByTerm($term = '')
    {
        $briefcases = briefcase::where('subject', 'like', '%' . $term . '%')->where('archived', false)->get();

        $briefcases->load(['comments', 'files', 'created_by', 'created_by.person']);

        return new JsonResponse([
            'status' => 'success',
            'data' => [
                'briefcases' => $briefcases,
            ],
        ], 200);
    }


    public function createBriefcase(Request $request)
    {
        $request->validate([
            'subject' => 'required|string',
            'description' => 'required|string',
        ]);

        try {
            // Crear registro del documento oficial
            $briefcases = Briefcase::create(array_merge(
                $request->except('foundations','solicitudes','briefcases'),
                ['created_by' => auth()->user()->id]
            ));

            // $briefcases = Briefcase::create(array_merge(
            //     $request->except('foundations','solicitudes','briefcases'),
            //     ['created_by' => auth()->user()->id]
            // ));

            // Guardar comentario
            if ($request->comment){
                Comment::create([
                    'comment' => $request->comment,
                    'briefcases' => $briefcases->id,
                    'created_by' => auth()->user()->id
                ]);
            }


            return response()->json([
                'status' => 'success',
                'data' => [
                    'briefcases' => $briefcases
                ],
                'message' => 'Portafolio creado con éxito'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear el Portafolio: ' . $e->getMessage()
            ]);
        }
    }


    public function updateBriefcase(Request $request, $id)
    {
        $request->validate([
            'asunto' => 'required|string',
            'estado' => 'required|boolean'
        ]);

        $briefcases = Briefcase::find($id);
        if (!$briefcases) {
            return response()->json([
                'message' => 'No se encontró el oficio especificado'
            ], 404);
        }

        $briefcases->asunto = $request->asunto;
        $briefcases->estado = $request->estado;

        try {
            $briefcases->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Portafolio actualizado con éxito'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar el Portafolio: ' . $e->getMessage()
            ]);
        }
    }

    public function archiveBriefcase($id)
    {
        $briefcases = Briefcase::find($id);

        if (!$briefcases) {
            return response()->json([
                'message' => 'Portafolio no encontrado'
            ]);
        }

        $briefcases->archived = true;
        $briefcases->archived_at = now();
        $briefcases->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Portafolio archivado correctamente'
        ]);
    }

    public function restoreBriefcase($id)
    {
        $briefcases = Briefcase::find($id);

        if (!$briefcases) {
            return response()->json([
                'message' => 'Portafolio no encontrado'
            ]);
        }

        $briefcases->archived = false;
        $briefcases->archived_at = null;
        $briefcases->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Portafolio restaurado correctamente'
        ]);
    }

    public function deleteBriefcase($id)
    {
        $briefcases = Briefcase::find($id);

        if (!$briefcases) {
            return response()->json([
                'message' => 'Portafolio no encontrado'
            ]);
        }

        $briefcases->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Portafolio eliminado correctamente'
        ]);
    }
}
