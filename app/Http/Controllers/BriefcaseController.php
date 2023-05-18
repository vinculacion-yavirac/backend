<?php

namespace App\Http\Controllers;

use App\Models\Briefcase;
use Illuminate\Http\Request;
use App\Models\Comment;
use Symfony\Component\HttpFoundation\JsonResponse;

class BriefcaseController extends Controller
{
   public function getBriefcase()
     {
        $briefcases = Briefcase::all();

        $briefcases->load(['comments', 'files', 'created_by', 'created_by.person']);

        return new JsonResponse([
            'status' => 'success',
            'data' => ['briefcases' => $briefcases],
/*             'total' => count(
                $briefcase) */
        ], 200);
    }

    public function getBriefcaseById($id)
    {
        $briefcase = Briefcase::where('id', $id)->get();

        $briefcase->load(['comments', 'files']);

        return new JsonResponse([
            'status' => 'success',
            'data' => ['briefcase #' . $id => $briefcase]
        ], 200);
    }

    public function searchBriefcaseByTerm($term = '')
    {
        $briefcase = briefcase::where('subject', 'like', '%' . $term . '%')->where('archived', false)->get();

        $briefcase->load(['comments', 'files']);

        return new JsonResponse([
            'status' => 'success',
            'data' => [
                'briefcase' => $briefcase,
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
            $briefcase = Briefcase::create(array_merge(
                $request->except('files', 'comments'),
                ['created_by' => auth()->user()->id]
            ));

            // Guardar comentario
            if ($request->comment){
                Comment::create([
                    'comment' => $request->comment,
                    'briefcase' => $briefcase->id,
                    'created_by' => auth()->user()->id
                ]);
            }


            return response()->json([
                'status' => 'success',
                'data' => [
                    'briefcase' => $briefcase
                ],
                'message' => 'Oficio creado con éxito'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear el oficio: ' . $e->getMessage()
            ]);
        }
    }


    public function updateBriefcase(Request $request, $id)
    {
        $request->validate([
            'asunto' => 'required|string',
            'estado' => 'required|boolean'
        ]);

        $briefcase = Briefcase::find($id);
        if (!$briefcase) {
            return response()->json([
                'message' => 'No se encontró el oficio especificado'
            ], 404);
        }

        $briefcase->asunto = $request->asunto;
        $briefcase->estado = $request->estado;

        try {
            $briefcase->save();
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
        $briefcase = Briefcase::find($id);

        if (!$briefcase) {
            return response()->json([
                'message' => 'Portafolio no encontrado'
            ]);
        }

        $briefcase->archived = true;
        $briefcase->archived_at = now();
        $briefcase->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Portafolio archivado correctamente'
        ]);
    }

    public function restoreBriefcase($id)
    {
        $briefcase = Briefcase::find($id);

        if (!$briefcase) {
            return response()->json([
                'message' => 'Portafolio no encontrado'
            ]);
        }

        $briefcase->archived = false;
        $briefcase->archived_at = null;
        $briefcase->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Portafolio restaurado correctamente'
        ]);
    }

    public function deleteBriefcase($id)
    {
        $briefcase = Briefcase::find($id);

        if (!$briefcase) {
            return response()->json([
                'message' => 'Portafolio no encontrado'
            ]);
        }

        $briefcase->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Portafolio eliminado correctamente'
        ]);
    }
}
