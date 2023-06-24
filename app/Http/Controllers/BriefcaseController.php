<?php

namespace App\Http\Controllers;

use App\Models\Briefcase;
use App\Models\Documents;
use App\Models\File;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Support\Facades\DB;



class BriefcaseController extends Controller
{
   /**
    * Summary of getBriefcase
    * @return JsonResponse
    * Obtener todos los portafolios
    */
    public function getBriefcase()
    {
        $briefcases = Briefcase::where('id', '>', 0)
            ->where('archived', false)
            ->with('project_participant_id.participant_id.person')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => ['briefcases' => $briefcases],
        ]);
    }

    /**
     * Summary of getBriefcaseById
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     * Obtener por el id
     */
    
     public function getBriefcaseById($id)
    {
        $briefcases = Briefcase::where('id', $id)
            ->where('archived', false)
            ->with('project_participant_id.participant_id.person','project_participant_id.project_id.beneficiary_institution_id','file','file.document')
            ->first();

        if (!$briefcases) {
            return response()->json([
                'message' => 'Portafolio no encontrado'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'briefcases' => $briefcases
            ],
        ]);
    }






    /**
     * Summary of getArchivedPortafolio
     * @return \Illuminate\Http\JsonResponse
     * Obtener todas las que sean true en archived
     */
    public function getArchivedBriefcase()
    {
        $briefcases = Briefcase::where('archived', true)
            ->with('project_participant_id.participant_id.person')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'briefcases' => $briefcases,
            ],
        ]);
    }


    /**
     * Summary of archiveBriefcase
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     * Archivar el protafolio por el id
     */
    public function archiveBriefcase($id)
    {
        $briefcase = Briefcase::findOrFail($id);

        $briefcase->update([
            'archived' => true,
            'archived_at' => now(),
            'archived_by' => auth()->user()->id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Portafolio archivado correctamente',
            'data' => [
                'briefcase' => $briefcase,
            ],
        ], 200);
    }


    /**
     * Summary of restoreBriefcase
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     * Restaurar por el id
     */
    public function restoreBriefcase($id)
    {
        $briefcase = Briefcase::findOrFail($id);

        $briefcase->update([
            'archived' => false,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Solicitud restaurada correctamente',
            'data' => [
                'briefcase' => $briefcase,
            ],
        ], 200);
    }

        /**
     * Summary of searchBriefcaseByTerm
     * @param mixed $term
     * @return \Illuminate\Http\JsonResponse
     * Buscador del portafolio
     */
    public function searchBriefcaseByTerm($term = '')
    {
        $lowerTerm = strtolower($term);

        $briefcases = Briefcase::where('archived', false)
            ->whereHas('project_participant_id.participant_id.person', function ($query) use ($lowerTerm) {
                $query->whereRaw('LOWER(names) like ? or LOWER(last_names) like ? or LOWER(identification) like ?', ['%' . $lowerTerm . '%', '%' . $lowerTerm . '%', '%' . $lowerTerm . '%']);
            })
            ->with('project_participant_id.participant_id.person')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'briefcases' => $briefcases
            ],
        ]);
    }

    /**
     * Summary of searchArchivedBriefcaseByTerm
     * @param mixed $term
     * @return \Illuminate\Http\JsonResponse
     * Buscador de los archivados
     */
    public function searchArchivedBriefcaseByTerm($term = '')
    {
        $briefcases = Briefcase::where('archived', true)
            ->whereHas('project_participant_id.participant_id.person', function ($query) use ($term) {
                $query->where('names', 'ILIKE', '%' . $term . '%')
                    ->orWhere('last_names', 'ILIKE', '%' . $term . '%')
                    ->orWhere('identification', 'ILIKE', '%' . $term . '%');
            })
            ->with('project_participant_id.participant_id.person')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'briefcases' => $briefcases
            ],
        ]);
    }

    /**
     * Summary of filterBriefcaseByStatus
     * @param mixed $state
     * @return \Illuminate\Http\JsonResponse
     * Filtro para obtener el state
     */
    public function filterBriefcaseByStatus($state = '')
    {
        $briefcases = Briefcase::where('archived', false)
            ->where('state', $state)
            ->with('project_participant_id.participant_id.person')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'briefcases' => $briefcases
            ],
        ]);
    }


    /**
     * Summary of searchAprobadoByTerm
     * @param mixed $term
     * @return \Illuminate\Http\JsonResponse
     * Buscador para state tipo true
     */
    public function searchAprobadoByTerm($term = '')
    {
        $briefcases = Briefcase::where('archived', false)
            ->where('state', true)
            ->whereHas('project_participant_id.participant_id.person', function ($query) use ($term) {
                $query->where('names', 'ILIKE', '%' . $term . '%')
                    ->orWhere('last_names', 'ILIKE', '%' . $term . '%')
                    ->orWhere('identification', 'ILIKE', '%' . $term . '%');
            })
            ->with('project_participant_id.participant_id.person')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'briefcases' => $briefcases
            ],
        ]);
    }

    /**
     * Summary of searchPendienteByTerm
     * @param mixed $term
     * @return \Illuminate\Http\JsonResponse
     * buscar por state en false
     */
    public function searchPendienteByTerm($term = '')
    {
        $briefcases = Briefcase::where('archived', false)
            ->where('state', false)
            ->whereHas('project_participant_id.participant_id.person', function ($query) use ($term) {
                $query->where('names', 'ILIKE', '%' . $term . '%')
                    ->orWhere('last_names', 'ILIKE', '%' . $term . '%')
                    ->orWhere('identification', 'ILIKE', '%' . $term . '%');
            })
            ->with('project_participant_id.participant_id.person')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'briefcases' => $briefcases
            ],
        ]);
    }

    public function updateBriefcase(Request $request, $id)
    {
        $request->validate([
            'observations' => 'nullable',
            'state' => 'nullable',
            //'created_by' => 'required',
            //'archived' => 'required',
            //'archived_at' => 'nullable',
            //'archived_by' => 'nullable',
            'updated_at' => 'nullable|date'
            //'project_participant_id' => 'required|exists:project_participants,id',
        ]);

        try {
            DB::beginTransaction();

            $briefcases = Briefcase::findOrFail($id);

            $briefcases->update([
                'observations' => $request->observations,
                'updated_at' => now(),
                'state' => true,
                //'created_by' => $request->created_by,
                //'archived' => $request->archived,
                //'archived_at' => $request->archived_at,
                //'archived_by' => $request->archived_by,
                //'project_participant_id' => $request->project_participant_id,
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Portafolio actualizado correctamente',
                'data' => [
                    'briefcases' => $briefcases,
                ],
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => 'error',
                'message' => 'Error al actualizar el modelo: ' . $e->getMessage(),
            ], 500);
        }
    }

     /**
    * Summary of createBriefcase
    * @param \Illuminate\Http\Request $request
    * @return \Illuminate\Http\JsonResponse
    * Crear un nuevo portafolio
    */
    public function createBriefcase(Request $request)
    {
        $request->validate([
            'observations' => 'required',
            'state' => 'required',
            'created_by' => 'required|exists:users,id',
            'archived' => 'required',
            'project_participant_id' => 'required|exists:project_participants,id',
        ]);

        try {
            DB::beginTransaction();

            // Obtener el participante del proyecto
            

            $briefcase = new Briefcase([
                'observations' => $request->observations,
                'state' => $request->state,
                'created_by' => auth()->user()->id,
                'archived' => $request->archived ?? false,
                'archived_at' => null,
                'archived_by' => null,
                'project_participant_id' => $request->project_participant_id,
            ]);

            $briefcase->created_by = auth()->user()->id; // Asignar el ID del usuario al campo 'created_by'

            $briefcase->save();

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Portafolio creado correctamente',
                'data' => [
                    'briefcase' => $briefcase,
                ],
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => 'error',
                'message' => 'Error al crear el portafolio: ' . $e->getMessage(),
            ], 500);
        }
    }



    public function updateBriefcaseRelacion(Request $request, $id)
    {
        $request->validate([
            'briefcase.observations' => 'nullable|string',
            'briefcase.state' => 'nullable|boolean',
            'briefcase.created_by' => 'nullable|integer',
            'briefcase.archived' => 'nullable|boolean',
            'briefcase.archived_at' => 'nullable|date',
            'briefcase.archived_by' => 'nullable|integer',
            'document' => 'nullable|array',
            'document.*.name' => 'required|string',
            'document.*.template' => 'nullable|string',
            'document.*.state' => 'nullable|boolean',
            'document.*.order' => 'nullable|integer',
            'document.*.responsible_id' => 'nullable|integer',
            'file.*.name' => 'nullable|string',
            'file.*.type' => 'nullable|string',
            'file.*.content' => 'nullable|string',
            'file.*.observation' => 'nullable|string',
            'file.*.state' => 'nullable|boolean',
            'file.*.size' => 'nullable|integer',
        ]);

        // Iniciar la transacción
        DB::beginTransaction();

        try {
            // Actualizar el briefcase
            $briefcaseData = $request->input('briefcase');
            $briefcase = Briefcase::findOrFail($id);
            $briefcase->fill($briefcaseData);
            $briefcase->save();
            
            
            /*
            $briefcaseData = $request->input('briefcase');
            if (!empty($briefcaseData)) {
                $briefcase = Briefcase::findOrFail($id);
                $briefcase->fill($briefcaseData);
                $briefcase->save();
            }
            */
            // Actualizar los documentos
            
            $documentData = $request->input('document');
            foreach ($documentData as $documentItem) {
                $document = Documents::firstOrCreate(['id' => $documentItem['id']]);
                $document->fill($documentItem);
                $document->save();
            }
            

            /*
            $documentData = $request->input('document');
            if (!is_null($documentData)) {
                foreach ($documentData as $documentItem) {
                    if (isset($documentItem['id'])) {
                        $document = Documents::firstOrCreate(['id' => $documentItem['id']]);
                        $document->fill($documentItem);
                        $document->save();
                    }
                }
            }

            */
            // Actualizar los archivos
            $fileData = $request->input('file');
            foreach ($fileData as $fileItem) {
                $file = File::findOrFail($fileItem['id']);
                $file->fill($fileItem);
                $file->save();
            }

            // Confirmar la transacción
            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'El briefcase y sus relaciones han sido actualizados correctamente.',
            ], 200);
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollback();

            return response()->json([
                'status' => 'error',
                'message' => 'Error al actualizar el briefcase y sus relaciones: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function createRelation(Request $request)
    {
        try {
            DB::beginTransaction();
    
            // Obtener los datos del formulario
            $requestData = $request->all();
    
            // Crear el portafolio
            $briefcase = Briefcase::create($requestData['briefcases']);
    
            // Verificar si se creó correctamente el portafolio
            if (!$briefcase) {
                throw new \Exception("No se pudo crear el portafolio.");
            }
    
            // Crear los documentos
            $documents = $requestData['documents'];
    
            foreach ($documents as $documentData) {
                // Crear el documento
                $document = Documents::create($documentData);
    
                // Verificar si se creó correctamente el documento
                if (!$document) {
                    throw new \Exception("No se pudo crear el documento.");
                }
    
                // Obtener los archivos relacionados con el documento
                $files = $documentData['files'];
    
                foreach ($files as $fileData) {
                    // Crear el archivo
                    $file = new File();
    
                    // Asignar los datos del archivo
                    $file->fill($fileData);
    
                    // Verificar si se asignaron correctamente los datos del archivo
                    if (!$file->save()) {
                        throw new \Exception("No se pudo crear el archivo.");
                    }
    
                    // Establecer la relación entre el archivo y el documento
                    //$document->files()->attach($file->id);
                    $file->briefcases()->associate($briefcase);
                    $file->documents()->associate($document);
                }
            }
    
            DB::commit();
    
            return response()->json([
                'message' => 'La relación entre el portafolio y los documentos se ha creado exitosamente.'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
    
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }

}
