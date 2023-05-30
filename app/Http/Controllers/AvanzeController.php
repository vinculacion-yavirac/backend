<?php

namespace App\Http\Controllers;

use App\Models\Avanze;
use App\Models\Briefcase;
use Illuminate\Http\Request;
use App\Models\Comment;
use Symfony\Component\HttpFoundation\JsonResponse;

class AvanzeController extends Controller
{
   public function getAllAvanzes()
     {
        $avanze = Avanze::all();
        return new JsonResponse([
            'status' => 'success',
            'data' => ['avanzes' => $avanze],
/*             'total' => count(
                $briefcase) */
        ], 200);
    }

    public function getAllAvanzesById($id)
    {
        $avanze = Avanze::where('id', $id)->get();


        return new JsonResponse([
            'status' => 'success',
            'data' => ['avanze' => $avanze]
        ], 200);
    }

    public function searchBriefcaseByTerm($term = '')
    {
        $briefcase = briefcase::where('subject', 'like', '%' . $term . '%')->where('archived', false)->get();

        $briefcase->load(['comments', 'files', 'created_by', 'created_by.person']);

        return new JsonResponse([
            'status' => 'success',
            'data' => [
                'briefcase' => $briefcase,
            ],
        ], 200);
    }


    public function createAvanzes(Request $request)
    {
        try {
            // Crear registro del documento oficial
            $avanze = Avanze::create(array_merge(
                $request->except('files', 'comments'),
                ['created_by' => auth()->user()->id]
            ));

       


            return response()->json([
                'status' => 'success',
                'data' => [
                    'avanze' => $avanze
                ],
                'message' => 'Oficio creado con éxito'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear el oficio: ' . $e->getMessage()
            ]);
        }
    }


    public function updateAvanzes(Request $request, $id)
    {


        $avanze = Avanze::find($id);
        if (!$avanze) {
            return response()->json([
                'message' => 'No se encontró el oficio especificado'
            ], 404);
        }

        $avanze->resumen = $request->resumen;
        $avanze->indicadores = $request->indicadores;
        $avanze->medios = $request->medios;
        $avanze->observacion = $request->observacion;
        $avanze->created_at = $request->created_at;
        $avanze->updated_at = $request->updated_at;
        try {
            $avanze->save();
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

    public function deleteAvanzeById($id)
    {
        $avanze = Avanze::find($id);

        if (!$avanze) {
            return response()->json([
                'message' => 'Portafolio no encontrado'
            ]);
        }

        $avanze->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Portafolio eliminado correctamente'
        ]);
    }
}
