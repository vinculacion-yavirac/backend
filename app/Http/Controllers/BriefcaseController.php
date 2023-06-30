<?php

namespace App\Http\Controllers;

use App\Models\Briefcase;
use App\Models\Documents;
use App\Models\File;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


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
            ->with('project_participant_id.participant_id.person','project_participant_id.project_id.beneficiary_institution_id','files','documents')
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



    /**
     * Summary of createBriefcase
     * @param \Illuminate\Http\Request $request
     * @throws \Exception
     * @return \Illuminate\Http\JsonResponse
     * crear transaccion de documentos archivos y portafolio
     */
    public function createBriefcase(Request $request)
{
    try {
        DB::beginTransaction();

        // Obtener los datos del formulario
        $requestData = $request->all();

        // Crear el portafolio
        $briefcase = Briefcase::create([$requestData['briefcases']]);

        // Verificar si se creó correctamente el portafolio
        if (!$briefcase) {
            throw new \Exception("No se pudo crear el portafolio.");
        }

        // Crear los documentos
        $documents = $requestData['documents'];

        foreach ($documents as $documentData) {
            // Crear el documento
            $document = Documents::create([$documentData]);

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
                $file->name = $fileData['name'];
                $file->type = $fileData['type'];
                $file->content = $fileData['content'];
                $file->size = $fileData['size'];

                // Verificar si se asignaron correctamente los datos del archivo
                if (!$file->save()) {
                    throw new \Exception("No se pudo crear el archivo.");
                }

                // Asignar los IDs del portafolio y el documento al archivo
                $file->briefcase_id = $briefcase->id;
                $file->document_id = $document->id;
                $file->save();
            }

            // Establecer la relación entre el documento y el portafolio
            $document->briefcases()->attach($briefcase->id);
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


    /**
     * Summary of updateBriefcase
     * @param \Illuminate\Http\Request $request
     * @param mixed $id
     * @throws \Exception
     * @return \Illuminate\Http\JsonResponse
     * Actualizar transaccion de documentos archivos y portafolio
     */
    public function updateBriefcase(Request $request, $id)
    {
        try {
            DB::beginTransaction();
    
            // Obtener los datos del formulario
            $requestData = $request->all();
    
            // Obtener el portafolio existente
            $briefcase = Briefcase::findOrFail($id);
    
            // Actualizar los datos del portafolio
            $briefcase->update($requestData['briefcases']);
    
            // Verificar si se actualizó correctamente el portafolio
            if (!$briefcase) {
                throw new \Exception("No se pudo actualizar el portafolio.");
            }
    
            // Actualizar los documentos
            $documents = $requestData['documents'];
    
            foreach ($documents as $documentData) {
                // Obtener el documento existente o crear uno nuevo si no existe
                $document = Documents::updateOrCreate(['id' => $documentData['id']], $documentData);
    
                // Verificar si se actualizó correctamente el documento
                if (!$document) {
                    throw new \Exception("No se pudo actualizar el documento.");
                }
    
                // Obtener los archivos relacionados con el documento
                $files = $documentData['files'];
    
                foreach ($files as $fileData) {
                    // Obtener el archivo existente o crear uno nuevo si no existe
                    $file = File::updateOrCreate(['id' => $fileData['id']], $fileData);
    
                    // Verificar si se actualizó correctamente el archivo
                    if (!$file) {
                        throw new \Exception("No se pudo actualizar el archivo.");
                    }
    
                    // Establecer la relación entre el archivo y el documento
                    $file->briefcases()->associate($briefcase);
                    $file->documents()->associate($document);
                    $file->save();
                }
            }
    
            DB::commit();
    
            return response()->json([
                'message' => 'La relación entre el portafolio y los documentos se ha actualizado exitosamente.'
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();
    
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }







// prueba aaaaaaaaaaaaaaaaaaaa


    public function create(Request $request)
    {
        $data = $request->all();

        // if (!isset($data['observations'])) {
        //     $data['observations'] = '';
        //     $data['state'] = false;
        // }

        try {
            DB::beginTransaction();

            $user = Auth::user(); // Obtén el usuario autenticado actualmente
            $now = Carbon::now(); // Obtén la fecha y hora actual

            $briefcase = Briefcase::create([
                'observations' => $data['observations'],
                'state' => $data['state'] ? 1 : 0,
                'created_by' => $user->id, // Asigna el ID del usuario autenticado a 'created_by'
                'created_at' => $now, // No es necesario establecer manualmente el campo 'created_at'
            ]);

            $createdFiles = [];

            if ($request->hasFile('files')) {
                $uploadedFiles = is_array($request->file('files')) ? $request->file('files') : [$request->file('files')];

                foreach ($uploadedFiles as $index => $uploadedFile) {
                    if ($uploadedFile->isValid()) {
                        $fileName = $uploadedFile->getClientOriginalName();
                        $fileContent = base64_encode(file_get_contents($uploadedFile));
                        $fileSize = $uploadedFile->getSize();
                        $observation = $data['observation']; // Obtén el valor de la observación desde el front-end
                        $state = $data['state'] ? 1 : 0;
                        $documentId = $data['document_id'];

                        $newFile = File::create([
                            'name' => $fileName,
                            'type' => $uploadedFile->getClientOriginalExtension(),
                            'content' => $fileContent,
                            'size' => $fileSize,
                            'observation' => $observation,
                            'state' => $state,
                            'document_id' => $documentId,
                            'briefcase_id' => $briefcase->id,
                        ]);

                        $createdFiles[] = $newFile;
                    } else {
                        DB::rollback();
                        return response()->json([
                            'message' => 'Ocurrió un error al crear el portafolio y guardar los archivos.',
                            'error' => 'Uno o más archivos no son válidos.',
                        ], 500);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Portafolio creado exitosamente',
                'briefcase' => $briefcase,
                'files' => $createdFiles,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Ocurrió un error al crear el portafolio y guardar los archivos.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


}
