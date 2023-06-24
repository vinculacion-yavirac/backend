<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Documents;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function getDocuments()
    {
    
        $documents = Documents::with('responsible_id')
            ->where('archived', false)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => ['documents' => $documents],
        ], 200);
    }


    

    /*
    public function createDocument(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'template' => 'required|string',,
            'state' => 'required|boolean',
            'order'=> 'required|integer',
        ]);

        try {
            // Crear registro del documento oficial
            $document = Documents::create(array_merge(
                $request->except('files', 'comments'),
                ['created_by' => auth()->user()->id]
            ));

            // Guardar comentario
            if ($request->comment) {
                Comment::create([
                    'comment' => $request->comment,
                    'official_document' => $officialDocument->id,
                    'created_by' => auth()->user()->id
                ]);
            }


            return response()->json([
                'status' => 'success',
                'data' => [
                    'official_document' => $officialDocument
                ],
                'message' => 'Oficio creado con éxito'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear el oficio: ' . $e->getMessage()
            ]);
        }
    }
    */
}
