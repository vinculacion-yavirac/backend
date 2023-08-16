<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Documents;
use App\Models\Briefcase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Schema(
 *     schema="Documento",
 *     title="Documento",
 *     description="Modelo de documento",
 *     @OA\Property(property="id", type="integer", format="int64"),
 *     @OA\Property(property="name", type="string", maxLength=100, description="Nombre del documento: Carta de compromiso, informe de inicio, control de asistencia, registro de asistencia, informe final, control de cumplimiento, certificado, encuesta de percepción, informe de control."),
 *     @OA\Property(property="template", type="string", maxLength=200, description="El documento está guardado en el servidor."),
 *     @OA\Property(property="state", type="boolean", description="Estado actual del documento."),
 *     @OA\Property(property="responsible_id", type="integer", nullable=true, description="ID del rol responsable de completar el documento."),
 *     @OA\Property(property="created_by", type="integer", nullable=true, description="ID del usuario que creó el documento."),
 *     @OA\Property(property="archived", type="boolean", default=false, description="Indica si el documento está archivado."),
 *     @OA\Property(property="archived_at", type="string", format="date-time", nullable=true, description="Marca de tiempo cuando se archivó el documento."),
 *     @OA\Property(property="archived_by", type="integer", nullable=true, description="ID del usuario que archivó el documento."),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Marca de tiempo cuando se creó el documento."),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Marca de tiempo cuando se actualizó por última vez el documento."),
 * )
 */
class DocumentController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/document",
     *     summary="Obtener lista de todos los Documentos",
     *     operationId="getDocuments",
     *     tags={"Documentos"},
     *     security={{"bearer":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Respuesta exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="documents", type="array", @OA\Items(ref="#/components/schemas/Documento"))
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
     * @OA\Get(
     *     path="/api/document/{id}",
     *     summary="Obtener Documento por ID",
     *     operationId="getDocumentsById",
     *     tags={"Documentos"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del documento",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Respuesta exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="documents", ref="#/components/schemas/Documento")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Documento no encontrado.")
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
    public function getDocumentsById($id)
    {
        $documents = Documents::where('id', $id)
            ->where('archived', false)
            ->first();

        if (!$documents) {
            return response()->json([
                'message' => 'Documento no encontrada'
            ],404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'documents' => $documents
            ],
        ],200);
    }

    /**
     * @OA\Get(
     *     path="/api/document/archived/list",
     *     summary="Obtener lista de Documentos Archivados",
     *     operationId="getArchivedDocument",
     *     tags={"Documentos"},
     *     security={{"bearer":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Respuesta exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="documents", type="array", @OA\Items(ref="#/components/schemas/Documento"))
     *             )
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
    public function getArchivedDocument()
    {
        $documents = Documents::with('responsible_id','archived_by.person')
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
     * @OA\Get(
     *     path="/api/document/search/term/{term?}",
     *     summary="Buscar Documentos por término",
     *     operationId="searchDocumentsByTerm",
     *     tags={"Documentos"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="term?",
     *         in="path",
     *         required=false,
     *         description="Término de búsqueda",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Respuesta exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="documents", type="array", @OA\Items(ref="#/components/schemas/Documento"))
     *             )
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
     * @OA\Get(
     *     path="/api/document/search/archived/term/{term?}",
     *     summary="Buscar Documentos Archivados por término",
     *     operationId="searchDocumentsArchivedByTerm",
     *     tags={"Documentos"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="term?",
     *         in="path",
     *         required=false,
     *         description="Término de búsqueda",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Respuesta exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="documents", type="array", @OA\Items(ref="#/components/schemas/Documento"))
     *             )
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
     * @OA\Put(
     *     path="/api/document/archive/{id}",
     *     summary="Archivar Documento",
     *     operationId="archiveDocument",
     *     tags={"Documentos"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del documento",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Respuesta exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Documento archivado correctamente"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="documents", ref="#/components/schemas/Documento")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Documento no encontrado.")
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
     * @OA\Put(
     *     path="/api/document/restore/{id}",
     *     summary="Restaurar Documento",
     *     operationId="restoreDocument",
     *     tags={"Documentos"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del documento",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Respuesta exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Documento restaurado correctamente"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="documents", ref="#/components/schemas/Documento")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No encontrado",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Documento no encontrado.")
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
     * @OA\Put(
     *     path="/api/document/update/{id}",
     *     summary="Actualizar Documento",
     *     operationId="updateDocument",
     *     tags={"Documentos"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del documento",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos actualizados del documento",
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Nuevo nombre del documento"),
     *             @OA\Property(property="template", type="string", example="Nuevo template del documento"),
     *             @OA\Property(property="state", type="boolean", example=true),
     *             @OA\Property(property="responsible_id", type="integer", example=1),
     *             @OA\Property(property="briefcase_id", type="integer", example=1),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Respuesta exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Documento actualizado exitosamente.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de solicitud",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Mensaje de error detallado.")
     *         )
     *     )
     * )
     */
    public function updateDocument(Request $request, $id)
    {
        $request->validate([
            'name' => 'string',
            'template' => 'string',
            'state' => 'boolean',
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

    /**
     * @OA\Post(
     *     path="/api/document/create",
     *     summary="Crear Documentos",
     *     operationId="createDocuments",
     *     tags={"Documentos"},
     *     security={{"bearer":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos para crear documentos",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="name", type="string", example="Nombre del documento"),
     *                 @OA\Property(property="template", type="string", example="Template del documento"),
     *                 @OA\Property(property="state", type="boolean", example=true),
     *                 @OA\Property(property="responsible_id", type="integer", example=1),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Respuesta exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Documentos creados exitosamente.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de solicitud",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Mensaje de error detallado.")
     *         )
     *     )
     * )
     */
    public function createDocuments(Request $request)
    {
        $request->validate([
            '*.name' => 'required|string',
            '*.template' => 'required|string',
            '*.state' => 'required|boolean',
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

    /**
     * @OA\Get(
     *     path="/api/document/responsible/student",
     *     summary="Obtener Documentos por Rol Responsable ESTUDIANTE",
     *     operationId="getDocumentsByResponsibleStudent",
     *     tags={"Documentos"},
     *     security={{"bearer":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Respuesta exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="documents", type="array", @OA\Items(ref="#/components/schemas/Documento"))
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
    public function getDocumentsByResponsibleStudent()
    {
        $studentRoleId = 5;

        $documents = Documents::where('archived', false)
            ->where('responsible_id', $studentRoleId)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => ['documents' => $documents],
        ], 200);
    }

    /**
     * Obtener lista de Documentos filtrados por el rol con ID 3 (por ejemplo, ESTUDIANTE).
     *
     * @OA\Get(
     *     path="/api/document/responsible/responsible/tutor",
     *     summary="Obtener Documentos por rol responsable TUTOR",
     *     operationId="getDocumentsByResponsibleRole3",
     *     tags={"Documentos"},
     *     security={{"bearer":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Respuesta exitosa",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="documents", type="array", @OA\Items(ref="#/components/schemas/Documento"))
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
    public function getDocumentsByResponsibleTutor()
    {

        $studentRoleId = 3;

        $role3Documents = Documents::where('archived', false)
            ->where('responsible_id', $studentRoleId)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'documents' => $role3Documents,
            ],
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/document/delete/{id}",
     *     summary="Eliminar un documento",
     *     operationId="deleteDocument",
     *     tags={"Documentos"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del documento a eliminar",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Respuesta exitosa al eliminar el documento",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Documento eliminado correctamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Portafolio no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Documento no encontrada")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Error de autenticación",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Error al eliminar la solicitud")
     *         )
     *     )
     * )
     */
    public function deleteDocument($id)
    {
        try {
            DB::transaction(
                function () use ($id) {

                    $documents = Documents::find($id);
                    if (!$documents) {
                        return response()->json([
                            'message' => 'Documento no encontrado'
                        ]);
                    }
                    $documents->delete();
                }
            );
            return response()->json([
                'status' => 'success',
                'message' => 'Documento eliminado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al eliminar el docuemnto: ' . $e->getMessage()
            ], 500);
        }
    }
}
