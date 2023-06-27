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

            foreach ($documentsData as $documentData) {
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
    
}
