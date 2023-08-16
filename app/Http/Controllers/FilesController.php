<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @OA\Schema(
 *     schema="File",
 *     title="File",
 *     description="File model",
 *     @OA\Property(property="id", type="integer", format="int64"),
 *     @OA\Property(property="name", type="string", maxLength=100),
 *     @OA\Property(property="type", type="string", maxLength=200),
 *     @OA\Property(property="content", type="string"),
 *     @OA\Property(property="observation", type="string", maxLength=200),
 *     @OA\Property(property="state", type="boolean", default=true),
 *     @OA\Property(property="size", type="integer"),
 *     @OA\Property(property="briefcase_id", type="integer", nullable=true),
 *     @OA\Property(property="document_id", type="integer", nullable=true),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 * )
 */
class FilesController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/files",
     *     summary="Obtener lista de todos los archivos",
     *     operationId="getFiles",
     *     tags={"Files"},
     *     security={{"bearer":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Respuesta exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="files", type="array", @OA\Items(ref="#/components/schemas/File"))
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
     *         description="No encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Recurso no encontrado.")
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
    public function getFiles()
    {
        $files = File::all();

        return new JsonResponse([
            'status' => 'success',
            'data' => [
                'files' => $files
            ]
        ], 200);
    }


    public function getFileById($id)
    {
        $file = File::where('id', $id)->get();

        return new JsonResponse([
            'status' => 'success',
            'data' => [
                'file' => $file
            ]
        ], 200);
    }


// prueba combinar

public function uploadFiles(Request $request, $idBriefcase)
{
    $response = [
        'status' => '',
        'message' => '',
        'files' => []
    ];

    if (!$request->hasFile('files')) {
        $response['status'] = 'error';
        $response['message'] = 'No se encontraron archivos en la solicitud';
        return response()->json($response, 400);
    }

    $files = $request->file('files');
    $names = $request->input('names');
    $types = $request->input('types');
    $observations = $request->input('observations');
    $states = $request->input('states');
    $documentIds = $request->input('document_ids');

    $newFiles = [];

    foreach ($files as $index => $file) {
        if ($file->isValid()) {
            $fileName = $names[$index];
            $fileContent = base64_encode(file_get_contents($file));
            $fileSize = $file->getSize();
            $observation = $observations[$index];
            $state = $states[$index];
            $document_id = $documentIds[$index];
            $name = $fileName;
            $fileType = $types[$index];

            $newFile = File::create([
                'name' => $name,
                'type' => $fileType,
                'content' => $fileContent,
                'size' => $fileSize,
                'observation' => $observation,
                'state' => $state,
                'briefcase_id' => $idBriefcase,
                'document_id' => $document_id,
            ]);

            $newFiles[] = $newFile;
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Uno o más archivos no son válidos';
            return response()->json($response, 400);
        }
    }

    $response['status'] = 'success';
    $response['message'] = 'Los archivos se subieron correctamente';
    $response['files'] = $newFiles;

    return response()->json($response, 200);
}




    public function downloadFile($portafolioId, $documentoId, $fileId)
{
    $file = File::find($fileId);

    if (!$file) {
        abort(404);
    }

    $fileContent = base64_decode($file->content);
    $fileName = $file->name;

    // Realiza las operaciones adicionales con los IDs de portafolio y documento si es necesario

    return response($fileContent, 200, [
        'Content-Type' => 'application/octet-stream',
        'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
    ]);
}
}
