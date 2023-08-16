<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Briefcase;
use Illuminate\Http\Request;
use App\Models\Comment;
use Symfony\Component\HttpFoundation\JsonResponse;

class ActivitiesController extends Controller
{
   public function getAllActividades()
     {
        $activity = Activity::with('goals_id')->get();

        return new JsonResponse([
            'status' => 'success',
            'data' => ['activity' => $activity],
/*             'total' => count(
                $briefcase) */
        ], 200);
    }

    public function getAllActividadesById($id)
    {
        $activity = Activity::where('id', $id)->get();


        return new JsonResponse([
            'status' => 'success',
            'data' => ['activity' => $activity]
        ], 200);
    }



    public function createActividades(Request $request)
    {
        try {
            // Crear registro del documento oficial
            $avanze = Activity::create(array_merge(
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


        $activity = Activity::find($id);
        if (!$activity) {
            return response()->json([
                'message' => 'No se encontró el oficio especificado'
            ], 404);
        }

        $activity->actividades = $request->actividades;
        $activity->avance = $request->avance;
        $activity->observacion = $request->observacion;
        $activity->created_at = $request->created_at;
        $activity->updated_at = $request->updated_at;
        try {
            $activity->save();
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
        $activity = Activity::find($id);

        if (!$activity) {
            return response()->json([
                'message' => 'Portafolio no encontrado'
            ]);
        }

        $activity->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Portafolio eliminado correctamente'
        ]);
    }
}
