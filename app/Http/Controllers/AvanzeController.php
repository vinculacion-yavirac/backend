<?php

namespace App\Http\Controllers;

use App\Models\Avanze;
use App\Models\Briefcase;
use Illuminate\Http\Request;
use App\Models\Comment;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @OA\Schema(
 *     schema="Avanze",
 *     title="Avanze",
 *     description="Avanze model",
 *     @OA\Property(property="id", type="integer", format="int64"),
 *     @OA\Property(property="resumen", type="string"),
 *     @OA\Property(property="indicadores", type="string"),
 *     @OA\Property(property="medios", type="string"),
 *     @OA\Property(property="observacion", type="string"),
 *     @OA\Property(property="created_at", type="string", format="date-time", nullable=true),
 *     @OA\Property(property="updated_at", type="string", format="date-time", nullable=true),
 * )
 * \OpenApi\Annotations\SecurityScheme
 */

class AvanzeController extends Controller
{

/**
 * @OA\Get(
 *     path="/api/avanze",
 *     summary="Obtener todos los avances",
 *     tags={"Avances"},
 *     description="Obtiene todos los avances registrados.",
 *     security={ {"bearerAuth": {} } },
 *     @OA\Response(
 *         response=200,
 *         description="Operación exitosa",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="success"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="avanzes", type="array", @OA\Items(ref="#/components/schemas/Avanze"))
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


    /**
 * @OA\Get(
 *     path="/api/avanze/{id}",
 *     summary="Obtener avance por ID",
 *     tags={"Avances"},
 *     description="Obtiene un avance por su ID.",
 *     security={ {"bearerAuth": {} } },
 *     @OA\Parameter(
 *         name="id",
 *         description="ID del avance",
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
 *                 @OA\Property(property="avanze", type="array", @OA\Items(ref="#/components/schemas/Avanze"))
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
 *         description="Avance no encontrado",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="Avance no encontrado.")
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


    public function getAllAvanzesById($id)
    {
        $avanze = Avanze::where('id', $id)->get();


        return new JsonResponse([
            'status' => 'success',
            'data' => ['avanze' => $avanze]
        ], 200);
    }



    /**
 * @OA\Post(
 *     path="/api/avanze/create",
 *     summary="Crear avance",
 *     tags={"Avances"},
 *     description="Crea un nuevo avance.",
 *     security={ {"bearerAuth": {} } },
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(ref="#/components/schemas/Avanze")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Operación exitosa",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="success"),
 *             @OA\Property(property="data", type="object",
 *                 @OA\Property(property="avanze", ref="#/components/schemas/Avanze")
 *             ),
 *             @OA\Property(property="message", type="string", example="Oficio creado con éxito")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Solicitud inválida",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="Solicitud inválida.")
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


    /**
 * @OA\Put(
 *     path="/api/avanze/update/{id}",
 *     summary="Actualizar avance",
 *     tags={"Avances"},
 *     description="Actualiza un avance existente.",
 *     security={ {"bearerAuth": {} } },
 *     @OA\Parameter(
 *         name="id",
 *         description="ID del avance",
 *         required=true,
 *         in="path",
 *         @OA\Schema(type="integer", format="int64")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="resumen", type="string"),
 *             @OA\Property(property="indicadores", type="string"),
 *             @OA\Property(property="medios", type="string"),
 *             @OA\Property(property="observacion", type="string"),
 *             @OA\Property(property="created_at", type="string", format="date-time"),
 *             @OA\Property(property="updated_at", type="string", format="date-time")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Operación exitosa",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="success"),
 *             @OA\Property(property="message", type="string", example="Portafolio actualizado con éxito")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Solicitud inválida",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="Solicitud inválida.")
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
 *         description="Avance no encontrado",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="string", example="error"),
 *             @OA\Property(property="message", type="string", example="Avance no encontrado.")
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


    /**
 * @OA\Put(
 *     path="/api/avanze/briefcase/archive/{id}",
 *     summary="Archivar portafolio",
 *     tags={"Portafolios"},
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
 *     path="/api/avanze/briefcase/restore/{id}",
 *     summary="Restaurar portafolio",
 *     tags={"Portafolios"},
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
 *     path="/api/avanze/delete/{id}",
 *     summary="Eliminar portafolio por ID",
 *     tags={"Avanzes"},
 *     description="Elimina un portafolio por su ID.",
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
