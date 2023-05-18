<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OfficialDocument;
use App\Models\File;
use App\Models\Comment;
use Symfony\Component\HttpFoundation\JsonResponse;

class OfficialDocumentsController extends Controller
{
    function getOfficialDocuments()
    {
        $officialDocuments = OfficialDocument::all();

        $officialDocuments->load(['comments', 'files', 'created_by', 'created_by.person']);

        return new JsonResponse([
            'status' => 'success',
            'data' => ['official_documents' => $officialDocuments],
/*             'total' => count(
                $officialDocuments) */
        ], 200);
    }

    public function getOfficialDocumentById($id)
    {
        $officialDocument = OfficialDocument::where('id', $id)->get();

        $officialDocument->load(['comments', 'files']);

        return new JsonResponse([
            'status' => 'success',
            'data' => ['official_document #' . $id => $officialDocument]
        ], 200);
    }

    public function searchOfficialDocumentsByTerm($term = '')
    {
        $officialDocuments = OfficialDocument::where('subject', 'like', '%' . $term . '%')->where('archived', false)->get();

        $officialDocuments->load(['comments', 'files']);

        return new JsonResponse([
            'status' => 'success',
            'data' => [
                'official_documents' => $officialDocuments,
            ],
        ], 200);
    }


    public function createOfficialDocument(Request $request)
    {
        $request->validate([
            'subject' => 'required|string',
            'description' => 'required|string',
        ]);

        try {
            // Crear registro del documento oficial
            $officialDocument = OfficialDocument::create(array_merge(
                $request->except('files', 'comments'),
                ['created_by' => auth()->user()->id]
            ));

            // Guardar comentario
            if ($request->comment){
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


    public function updateOfficialDocument(Request $request, $id)
    {
        $request->validate([
            'asunto' => 'required|string',
            'estado' => 'required|boolean'
        ]);

        $oficio = OfficialDocument::find($id);
        if (!$oficio) {
            return response()->json([
                'message' => 'No se encontró el oficio especificado'
            ], 404);
        }

        $oficio->asunto = $request->asunto;
        $oficio->estado = $request->estado;

        try {
            $oficio->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Oficio actualizado con éxito'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar el oficio: ' . $e->getMessage()
            ]);
        }
    }

    public function archiveOfficialDocument($id)
    {
        $oficio = OfficialDocument::find($id);

        if (!$oficio) {
            return response()->json([
                'message' => 'Oficio no encontrado'
            ]);
        }

        $oficio->archived = true;
        $oficio->archived_at = now();
        $oficio->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Oficio archivado correctamente'
        ]);
    }

    public function restoreOfficialDocument($id)
    {
        $oficio = OfficialDocument::find($id);

        if (!$oficio) {
            return response()->json([
                'message' => 'Oficio no encontrado'
            ]);
        }

        $oficio->archived = false;
        $oficio->archived_at = null;
        $oficio->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Oficio restaurado correctamente'
        ]);
    }

    public function deleteOfficialDocument($id)
    {
        $oficio = OfficialDocument::find($id);

        if (!$oficio) {
            return response()->json([
                'message' => 'Oficio no encontrado'
            ]);
        }

        $oficio->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Oficio eliminado correctamente'
        ]);
    }
}
