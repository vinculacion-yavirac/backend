<?php

namespace App\Http\Controllers;

use App\Models\Actividades;
use App\Models\Briefcase;
use Illuminate\Http\Request;
use App\Models\Comment;
use Symfony\Component\HttpFoundation\JsonResponse;

class ActividadesController extends Controller
{
   public function getAllActividades()
     {
        $avanze = Actividades::all();
        return new JsonResponse([
            'status' => 'success',
            'data' => ['avanzes' => $avanze],
/*             'total' => count(
                $briefcase) */
        ], 200);
    }

    public function getAllActividadesById($id)
    {
        $avanze = Actividades::where('id', $id)->get();


        return new JsonResponse([
            'status' => 'success',
            'data' => ['avanze' => $avanze]
        ], 200);
    }



    public function createActividades(Request $request)
    {
        try {
            // Crear registro del documento oficial
            $avanze = Actividades::create(array_merge(
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


    public function updateActividades(Request $request, $id)
    {


        $avanze = Actividades::find($id);
        if (!$avanze) {
            return response()->json([
                'message' => 'No se encontró el oficio especificado'
            ], 404);
        }

        $avanze->actividades = $request->actividades;
        $avanze->avance = $request->avance;
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

    public function deleteActividadesById($id)
    {
        $avanze = Actividades::find($id);

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
