<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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


    public function uploadFiles(Request $request, $id)
    {
        $files = $request->file('archivos');

        foreach ($files as $file) {
            $newFile = new File();
            $newFile->name = $file->getClientOriginalName();
            $newFile->type = $file->getClientOriginalExtension();
            $newFile->content = base64_encode(file_get_contents($file));
            $newFile->size = $file->getSize();
            $newFile->briefcase = $id;
            $newFile->save();
        }

        return new JsonResponse([
            'status' => 'success',
            'message' => 'Los archivos se subieron correctamente'
        ],
            200
        );
    }


    public function downloadFile($id)
    {
        $file = File::find($id);

        if (!$file) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Error al descargar el archivo ',
            ], 404);
        }

        $content = base64_decode($file->content);
        $headers = [
            'Content-Type' => $file->type,
            'Content-Disposition' => 'attachment; filename=' . $file->name,
        ];

        return response($content, 200, $headers);
    }
}
