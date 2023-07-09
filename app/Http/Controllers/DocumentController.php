<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Documents;
use App\Models\Briefcase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    /**
     * Summary of getDocuments
     * @return \Illuminate\Http\JsonResponse|mixed
     * Lista de documentos
     */
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

    /**
     * Summary of getArchivedDocument
     * @return \Illuminate\Http\JsonResponse|mixed
     * lista de archivados
     */
    public function getArchivedDocument()
    {
        $documents = Documents::with('responsible_id')
        ->where('archived', true)
        ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'documents' => $documents,
            ],
        ]);
    }


    /**
     * Summary of getDocumentsById
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse|mixed
     * Obtener los documentos por el id
     */
    public function getDocumentsById($id)
    {
        $documents = Documents::where('id', $id)
            ->where('archived', false)
            ->first();

        if (!$documents) {
            return response()->json([
                'message' => 'Documento no encontrada'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'documents' => $documents
            ],
        ]);
    }


    /**
     * Summary of archiveDocument
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse|mixed
     * Archivar documentos
     */
    public function archiveDocument($id)
    {
        $documents = Documents::findOrFail($id);

        $documents->update([
            'archived' => true,
            'archived_at' => now(),
            'archived_by' => auth()->user()->id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Documento archivada correctamente',
            'data' => [
                'documents' => $documents,
            ],
        ], 200);
    }

    /**
     * Summary of restoreSolicitud
     * @param mixed $id
     * @return JsonResponse
     * Restaura documento por id
     */
    public function restoreDocument($id)
    {
        $documents = Documents::findOrFail($id);

        $documents->update([
            'archived' => false,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Documento restaurado correctamente',
            'data' => [
                'documents' => $documents,
            ],
        ], 200);
    }


    /**
     * Summary of searchDocumentsArchivedByTerm
     * @param mixed $term
     * @return \Illuminate\Http\JsonResponse|mixed
     * Buscador de lista de archivados
     */
    public function searchDocumentsArchivedByTerm($term = '')
    {
        $documents = Documents::where('archived', true)
            ->where(function ($query) use ($term) {
                $lowerTerm = strtolower($term);
                $query->whereRaw('LOWER(name) like ?', ['%' . $lowerTerm . '%'])
                    ->orWhereHas('responsible_id', function ($query) use ($lowerTerm) {
                        $query->whereRaw('LOWER(name) like ?', ['%' . $lowerTerm . '%']);
                    });
            })
            ->with('responsible_id')
            ->get();
    
        return response()->json([
            'status' => 'success',
            'data' => [
                'documents' => $documents
            ],
        ]);
    }


    /**
     * Summary of searchDocumentsByTerm
     * @param mixed $term
     * @return \Illuminate\Http\JsonResponse|mixed
     * Buscador de lista
     */
    public function searchDocumentsByTerm($term = '')
    {
        $documents = Documents::where('archived', false)
            ->where(function ($query) use ($term) {
                $lowerTerm = strtolower($term);
                $query->whereRaw('LOWER(name) like ?', ['%' . $lowerTerm . '%'])
                    ->orWhereHas('responsible_id', function ($query) use ($lowerTerm) {
                        $query->whereRaw('LOWER(name) like ?', ['%' . $lowerTerm . '%']);
                    });
            })
            ->with('responsible_id')
            ->get();
    
        return response()->json([
            'status' => 'success',
            'data' => [
                'documents' => $documents
            ],
        ]);
    }


    /**
     * Summary of createDocuments
     * @param \Illuminate\Http\Request $request
     * @throws \Exception
     * @return \Illuminate\Http\JsonResponse|mixed
     * Crear Documentos
     */
    public function createDocuments(Request $request)
    {
        $request->validate([
            '*.name' => 'required|string',
            '*.template' => 'required|string',
            '*.state' => 'required|boolean',
            '*.order' => 'required|integer',
            '*.responsible_id' => 'integer'
        ]);

        try {
            $documentsData = $request->all();
            $userId = Auth::id();

            foreach ($documentsData as $documentData) {
                $documentData['created_by'] = $userId;
                $document = Documents::create($documentData);

                if (!$document) {
                    throw new \Exception("No se pudo crear el documento.");
                }

                // Establecer la relación con el portafolio si es necesario
                if (isset($documentData['briefcase_id'])) {
                    $briefcase = Briefcase::find($documentData['briefcase_id']);

                    if ($briefcase) {
                        $document->briefcases()->attach($briefcase->id);
                    }
                }
            }

            return response()->json([
                'message' => 'Documentos creados exitosamente.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }


    public function updateDocument(Request $request, $id)
    {
        $request->validate([
            'name' => 'string',
            'template' => 'string',
            'state' => 'boolean',
            'order' => 'integer',
            '*.responsible_id' => 'integer',
            'briefcase_id' => 'integer'
        ]);

        try {
            $userId = Auth::id();
            $documentData = $request->all();
            $documentData['created_by'] = $userId;

            $document = Documents::find($id);

            if (!$document) {
                throw new \Exception("Documento no encontrado.");
            }

            $document->update($documentData);

            // Actualizar la relación con el portafolio si es necesario
            if (isset($documentData['briefcase_id'])) {
                $briefcase = Briefcase::find($documentData['briefcase_id']);

                if ($briefcase) {
                    $document->briefcases()->sync([$briefcase->id]);
                }
            } else {
                $document->briefcases()->detach();
            }

            return response()->json([
                'message' => 'Documento actualizado exitosamente.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }
}