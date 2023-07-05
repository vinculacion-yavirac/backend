<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
class FilesController extends Controller
{


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
    $documentIds = $request->input('document_ids');

    $newFiles = [];

    foreach ($files as $index => $file) {
        if ($file->isValid()) {
            $fileName = $names[$index];
            $fileContent = base64_encode(file_get_contents($file));
            $fileSize = $file->getSize();
            $observation = ''; // Agrega aquí el valor para el campo 'observation'
            $state = 0; // Agrega aquí el valor para el campo 'state'
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
