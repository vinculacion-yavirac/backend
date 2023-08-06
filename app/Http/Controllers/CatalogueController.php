<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Catalogue;
use Illuminate\Http\Request;

/**
 * @OA\Schema(
 *     schema="Catalogo",
 *     title="Catálogo",
 *     description="Solicitud catalog model",
 *     @OA\Property(property="id", type="integer", format="int64"),
 *     @OA\Property(property="code", type="string"),
 *     @OA\Property(property="catalog_type", type="string"),
 *     @OA\Property(property="catalog_value", type="string"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 * )
 * \OpenApi\Annotations\SecurityScheme
 */
class CatalogueController extends Controller
{
       /**
     * @OA\Get(
     *     path="/api/catalogues",
     *     summary="Obtener todos los catálogos",
     *     operationId="getAllCatalogues",
     *     tags={"Catalogos"},
     *     security={{"bearer":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Respuesta exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="catalogues", type="array", @OA\Items(ref="#/components/schemas/Catalogo"))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autorizado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="No autorizado."),
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
    public function getAllCatalogues()
    {
        try {
            $catalogues = Catalogue::all();

            return response()->json([
                'status' => 'success',
                'data' => [
                    'catalogues' => $catalogues,
                ],
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Catálogos no encontrados.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener los catálogos: ' . $e->getMessage(),
            ], 500);
        }
    }

}
