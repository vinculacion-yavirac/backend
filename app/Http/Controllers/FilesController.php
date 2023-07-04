<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Support\Facades\Response;
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


    // public function uploadFiles(Request $request, $id, $ids)
    // {
    //     $files = $request->file('archivos');
    
    //     foreach ($files as $file) {
    //         $newFile = new File();
    //         $newFile->name = $file->getClientOriginalName();
    //         $newFile->type = $file->getClientOriginalExtension();
    //         $newFile->content = base64_encode(file_get_contents($file)); // Codificar el contenido a base64
    //         $newFile->size = $file->getSize();
    //         $newFile->briefcase_id = $id;
    //         $newFile->document_id = $ids;
    //         $newFile->save();
    //     }
    
    //     return new JsonResponse([
    //         'status' => 'success',
    //         'message' => 'Los archivos se subieron correctamente'
    //     ],
    //         200
    //     );
    // }






    // public function uploadFiles(Request $request,$idBriefcase)
    // {
    //     $response = [
    //         'status' => '',
    //         'message' => '',
    //         'files' => []
    //     ];

    //     $documentId = $request->input('document_id');

    //     if (!$request->hasFile('files')) {
    //         $response['status'] = 'error';
    //         $response['message'] = 'No se encontraron archivos en la solicitud';
    //         return response()->json($response, 400);
    //     }
    
    //     $files = $request->file('files');
    //     $newFiles = [];
    
    //     if (is_array($files)) {
    //         foreach ($files as $file) {
    //             if ($file->isValid()) {
    //                 $fileName = $file->getClientOriginalName();
    //                 $fileContent = base64_encode(file_get_contents($file));
    //                 $fileSize = $file->getSize();
    //                 $observation = ''; // Agrega aquí el valor para el campo 'observation'
    //                 $state = 0; // Agrega aquí el valor para el campo 'state'
    //                 $document_id = $documentId;
    
    //                 $newFile = File::create([
    //                     'name' => $fileName,
    //                     'type' => $file->getClientOriginalExtension(),
    //                     'content' => $fileContent,
    //                     'size' => $fileSize,
    //                     'observation' => $observation,
    //                     'state' => $state,
    //                     'briefcase_id' => $idBriefcase,
    //                     'document_id' => $document_id,
    //                 ]);
    
    //                 $newFiles[] = $newFile;
    //             } else {
    //                 $response['status'] = 'error';
    //                 $response['message'] = 'Uno o más archivos no son válidos';
    //                 return response()->json($response, 400);
    //             }
    //         }
    //     } elseif ($files instanceof \Illuminate\Http\UploadedFile) {
    //         if ($files->isValid()) {
    //             $fileName = $files->getClientOriginalName();
    //             $fileContent = base64_encode(file_get_contents($files));
    //             $fileSize = $files->getSize();
    //             $observation = ''; // Agrega aquí el valor para el campo 'observation'
    //             $state = 0; // Agrega aquí el valor para el campo 'state'
    //             $document_id = $documentId;
    
    //             $newFile = File::create([
    //                 'name' => $fileName,
    //                 'type' => $files->getClientOriginalExtension(),
    //                 'content' => $fileContent,
    //                 'size' => $fileSize,
    //                 'observation' => $observation,
    //                 'state' => $state,
    //                 'briefcase_id' => $idBriefcase,
    //                 'document_id' => $document_id,
    //             ]);
    
    //             $newFiles[] = $newFile;
    //         } else {
    //             $response['status'] = 'error';
    //             $response['message'] = 'El archivo no es válido';
    //             return response()->json($response, 400);
    //         }
    //     } else {
    //         $response['status'] = 'error';
    //         $response['message'] = 'El formato de archivos no es válido';
    //         return response()->json($response, 400);
    //     }
    
    //     $response['status'] = 'success';
    //     $response['message'] = 'Los archivos se subieron correctamente';
    //     $response['files'] = $newFiles;
    
    //     return response()->json($response, 200);
    // }


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











    // public function downloadFile($id)
    // {
    //     $file = File::find($id);

    //     if (!$file) {
    //         return new JsonResponse([
    //             'status' => 'error',
    //             'message' => 'Error al descargar el archivo ',
    //         ], 404);
    //     }

    //     $content = base64_decode($file->content);
    //     $headers = [
    //         'Content-Type' => $file->type,
    //         'Content-Disposition' => 'attachment; filename=' . $file->name,
    //     ];

    //     return response($content, 200, $headers);
    // }




//funciona con un solo documento
    // public function download($fileId,$id)
    // {
    //     $file = File::find($fileId);

    //     if (!$file) {
    //         abort(404);
    //     }

    //     $fileContent = base64_decode($file->content);
    //     $fileName = $file->name;

    //     return Response::make($fileContent, 200, [
    //         'Content-Type' => 'application/octet-stream',
    //         'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
    //     ]);
    // }



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
