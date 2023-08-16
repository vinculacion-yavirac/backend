<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Briefcase;
use Illuminate\Http\Request;
use App\Models\Comment;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * @OA\Schema(
 *     schema="Activity",
 *     title="Activity",
 *     description="Activity model",
 *     @OA\Property(property="id", type="integer", format="int64"),
 *     @OA\Property(property="activity_name", type="string"),
 *     @OA\Property(property="goals_id", type="string", format="int64", nullable=true),
 *     @OA\Property(property="created_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="updated_at", type="string", format="date-time", nullable=true),
 * )
 * \OpenApi\Annotations\SecurityScheme
 */

class ActivitiesController extends Controller
{

    /**
 * @OA\Get(
 *     path="/api/activities",
 *     summary="Obtener todas las actividades",
 *     tags={"Activities"},
 *     description="Obtiene todas las actividades.",
 *     security={ {"bearerAuth": {} } },
 *     @OA\Response(
 *         response=200,
 *         description="Operación exitosa",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="success"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="activity", type="array",
 *                     @OA\Items(ref="#/components/schemas/Activity")
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="No autorizado",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="No autorizado.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error interno del servidor",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="Ocurrió un error en el servidor."),
 *             @OA\Property(property="error", type="string", example="Mensaje de error detallado.")
 *         )
 *     )
 * )
 */


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


/**
 * @OA\Get(
 *     path="/api/activities/{id}",
 *     summary="Obtener actividad por ID",
 *     tags={"Activities"},
 *     description="Obtiene una actividad por su ID.",
 *     security={ {"bearerAuth": {} } },
 *     @OA\Parameter(
 *         name="id",
 *         description="ID de la actividad",
 *         required=true,
 *         in="path",
 *         @OA\Schema(type="integer", format="int64")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Operación exitosa",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="success"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="activity", ref="#/components/schemas/Activity")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="No autorizado",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="No autorizado.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Actividad no encontrada",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="Actividad no encontrada.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error interno del servidor",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="Ocurrió un error en el servidor."),
 *             @OA\Property(property="error", type="string", example="Mensaje de error detallado.")
 *         )
 *     )
 * )
 */


    public function getAllActividadesById($id)
    {
        $activity = Activity::where('id', $id)->get();


        return new JsonResponse([
            'status' => 'success',
            'data' => ['activity' => $activity]
        ], 200);
    }


    /**
 * @OA\Post(
 *     path="/api/activities/create",
 *     summary="Crear una nueva actividad",
 *     tags={"Activities"},
 *     description="Crea una nueva actividad.",
 *     security={ {"bearerAuth": {} } },
 *     @OA\RequestBody(
 *         required=true,
 *         description="Datos de la actividad a crear",
 *         @OA\JsonContent(ref="#/components/schemas/Activity")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Operación exitosa",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="success"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="avanze", ref="#/components/schemas/Activity")
 *             ),
 *             @OA\Property(property="message", type="string", example="Actividad creada con éxito")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Datos inválidos",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="Error en los datos proporcionados.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="No autorizado",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="No autorizado.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error interno del servidor",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="Ocurrió un error en el servidor."),
 *             @OA\Property(property="error", type="string", example="Mensaje de error detallado.")
 *         )
 *     )
 * )
 */

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


    /**
 * @OA\Put(
 *     path="/api/activities/update/{id}",
 *     summary="Actualizar una actividad existente",
 *     tags={"Activities"},
 *     description="Actualiza una actividad existente por su ID.",
 *     security={ {"bearerAuth": {} } },
 *     @OA\Parameter(
 *         name="id",
 *         description="ID de la actividad",
 *         required=true,
 *         in="path",
 *         @OA\Schema(type="integer", format="int64")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         description="Datos de la actividad a actualizar",
 *         @OA\JsonContent(ref="#/components/schemas/Activity")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Operación exitosa",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="success"),
 *             @OA\Property(property="message", type="string", example="Actividad actualizada con éxito")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Datos inválidos",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="Error en los datos proporcionados.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="No autorizado",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="No autorizado.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Actividad no encontrada",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Actividad no encontrada")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error interno del servidor",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="Ocurrió un error en el servidor."),
 *             @OA\Property(property="error", type="string", example="Mensaje de error detallado.")
 *         )
 *     )
 * )
 */

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


        /**
 * @OA\Put(
 *     path="/api/activities/briefcase/archive/{id}",
 *     summary="Archivar portafolio",
 *     tags={"Activities"},
 *     description="Archiva un portafolio existente.",
 *     security={ {"bearerAuth": {} } },
 *     @OA\Parameter(
 *         name="id",
 *         description="ID del portafolio",
 *         required=true,
 *         in="path",
 *         @OA\Schema(type="integer", format="int64")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Operación exitosa",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="success"),
 *             @OA\Property(property="message", type="string", example="Portafolio archivado correctamente")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="No autorizado",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="No autorizado.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Portafolio no encontrado",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="Portafolio no encontrado.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error interno del servidor",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="Ocurrió un error en el servidor."),
 *             @OA\Property(property="error", type="string", example="Mensaje de error detallado.")
 *         )
 *     )
 * )
 */

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

    /**
 * @OA\Put(
 *     path="/api/activities/briefcase/restore/{id}",
 *     summary="Restaurar portafolio",
 *     tags={"Activities"},
 *     description="Restaura un portafolio previamente archivado.",
 *     security={ {"bearerAuth": {} } },
 *     @OA\Parameter(
 *         name="id",
 *         description="ID del portafolio",
 *         required=true,
 *         in="path",
 *         @OA\Schema(type="integer", format="int64")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Operación exitosa",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="success"),
 *             @OA\Property(property="message", type="string", example="Portafolio restaurado correctamente")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="No autorizado",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="No autorizado.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Portafolio no encontrado",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="Portafolio no encontrado.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error interno del servidor",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="Ocurrió un error en el servidor."),
 *             @OA\Property(property="error", type="string", example="Mensaje de error detallado.")
 *         )
 *     )
 * )
 */


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

    /**
 * @OA\Delete(
 *     path="/api/activities/delete/{id}",
 *     summary="Eliminar actividad por ID",
 *     tags={"Activities"},
 *     description="Elimina una actividad por su ID.",
 *     security={ {"bearerAuth": {} } },
 *     @OA\Parameter(
 *         name="id",
 *         description="ID de la actividad",
 *         required=true,
 *         in="path",
 *         @OA\Schema(type="integer", format="int64")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Operación exitosa",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="success"),
 *             @OA\Property(property="message", type="string", example="Portafolio eliminado correctamente")
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="No autorizado",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="No autorizado.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Portafolio no encontrado",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Portafolio no encontrado")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error interno del servidor",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="Ocurrió un error en el servidor."),
 *             @OA\Property(property="error", type="string", example="Mensaje de error detallado.")
 *         )
 *     )
 * )
 */

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
