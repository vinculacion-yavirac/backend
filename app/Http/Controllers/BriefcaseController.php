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
            ->with('project_participant_id.participant_id.person','created_by.person')
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
            ->with('project_participant_id.participant_id.person','created_by.person','project_participant_id.project_id.beneficiary_institution_id','files','documents')
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
            ->with('project_participant_id.participant_id.person','created_by.person')
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
        ->whereHas('created_by.person', function ($query) use ($term) {
            $query->whereRaw('LOWER(names) like ?', ['%' . $term . '%'])
                ->orWhereRaw('LOWER(last_names) like ?', ['%' . $term . '%'])
                ->orWhereRaw('LOWER(identification) like ?', ['%' . $term . '%']);
        })
        ->with('created_by.person')
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
        ->whereHas('created_by.person', function ($query) use ($term) {
            $query->whereRaw('LOWER(names) like ?', ['%' . $term . '%'])
                ->orWhereRaw('LOWER(last_names) like ?', ['%' . $term . '%'])
                ->orWhereRaw('LOWER(identification) like ?', ['%' . $term . '%']);
        })
        ->with('created_by.person')
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
            ->with('created_by.person')
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
            ->whereHas('created_by.person', function ($query) use ($term) {
                $query->where('names', 'ILIKE', '%' . $term . '%')
                    ->orWhere('last_names', 'ILIKE', '%' . $term . '%')
                    ->orWhere('identification', 'ILIKE', '%' . $term . '%');
            })
            ->with('created_by.person')
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
            ->whereHas('created_by.person', function ($query) use ($term) {
                $query->where('names', 'ILIKE', '%' . $term . '%')
                    ->orWhere('last_names', 'ILIKE', '%' . $term . '%')
                    ->orWhere('identification', 'ILIKE', '%' . $term . '%');
            })
            ->with('created_by.person')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'briefcases' => $briefcases
            ],
        ]);
    }

//----------------------------------------------------------------------------------

    /**
     * Summary of createBriefcase
     * @param \Illuminate\Http\Request $request
     * @throws \Exception
     * @return \Illuminate\Http\JsonResponse
     * crear transaccion de documentos archivos y portafolio
     */
    public function createBriefcaseWithFiles(Request $request)
{
    $request->validate([
        'observations' => 'required|string',
        'state' => 'required|boolean',
        'files' => 'required|array',
        'files.*.name' => 'required|string',
        'files.*.observation' => 'required|string',
        'files.*.state' => 'required|boolean',
        'files.*.document_id' => 'required|integer',
    ]);

    try {
        // Crear el briefcase
        $briefcaseData = $request->only(['observations', 'state']);
        $briefcaseData['created_by'] = Auth::id();
        $briefcase = Briefcase::create($briefcaseData);

        if (!$briefcase) {
            throw new \Exception("No se pudo crear el briefcase.");
        }

        // Crear los archivos y asociarlos al briefcase
        $filesData = $request->input('files');
        foreach ($filesData as $fileData) {
            $fileData['briefcase_id'] = $briefcase->id;
            File::create($fileData);
        }

        return response()->json([
            'message' => 'Briefcase y archivos creados exitosamente.',
            'briefcase' => $briefcase
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage()
        ], 400);
    }
}




//-----------------------------------------------------------------------------------------

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
        try {
            DB::beginTransaction();

            $user = Auth::user();
            $now = Carbon::now();

            $briefcase = Briefcase::create([
                'observations' => $request->input('observations'),
                'state' => $request->input('state', false),
                'created_by' => $user->id,
                'created_at' => $now,
                'project_participant_id' => $request->input('project_participant_id'),
            ]);

            // $createdFiles = [];

            // foreach ($request->input('files') as $fileData) {
            //     $file = new File();
            //     $file->name = $fileData['name'];
            //     $file->type = $fileData['type'];
            //     $file->content = base64_decode($fileData['content']); // Decodificar el contenido base64
            //     $file->size = $fileData['size'];
            //     $file->observation = $fileData['observation'] ?? '';
            //     $file->state = $fileData['state'] ? 1 : 0;
            //     $file->briefcase_id = $briefcase->id;
            //     $file->document_id = $fileData['document_id'];
            //     $file->save();

            //     $createdFiles[] = $file;
            // }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'data' => [
                    'briefcase' => $briefcase,
                    //'files' => $createdFiles,
                ],
                'message' => 'Portafolio creado exitosamente',
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Ocurrió un error al crear el portafolio y guardar los archivos.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


// public function create(Request $request)
// {
//     try {
//         DB::beginTransaction();

//         $user = Auth::user();
//         $now = Carbon::now();

//         $briefcase = Briefcase::create([
//             'observations' => $request->input('observations'),
//             'state' => $request->input('state', false),
//             'created_by' => $user->id,
//             'created_at' => $now,
//             'project_participant_id' => $request->input('project_participant_id'),
//         ]);

//         $files = $request->file('files');
//         $newFiles = [];
    
//         if (is_array($files)) {
//             foreach ($files as $file) {
//                 if ($file->isValid()) {
//                     $fileName = $file->getClientOriginalName();
//                     $fileContent = base64_encode(file_get_contents($file));
//                     $fileSize = $file->getSize();
//                     $observation = ''; // Agrega aquí el valor para el campo 'observation'
//                     $state = 0; // Agrega aquí el valor para el campo 'state'
    
//                     $newFile = File::create([
//                         'name' => $fileName,
//                         'type' => $file->getClientOriginalExtension(),
//                         'content' => $fileContent,
//                         'size' => $fileSize,
//                         'observation' => $observation,
//                         'state' => $state,
//                         'briefcase_id' => $briefcase->id,
//                         'document_id' => 1
//                     ]);
    
//                     $newFiles[] = $newFile;
//                 } else {
//                     $response['status'] = 'error';
//                     $response['message'] = 'Uno o más archivos no son válidos';
//                     return response()->json($response, 400);
//                 }
//             }
//         } elseif ($files instanceof \Illuminate\Http\UploadedFile) {
//             if ($files->isValid()) {
//                 $fileName = $files->getClientOriginalName();
//                 $fileContent = base64_encode(file_get_contents($files));
//                 $fileSize = $files->getSize();
//                 $observation = ''; // Agrega aquí el valor para el campo 'observation'
//                 $state = 0; // Agrega aquí el valor para el campo 'state'
    
//                 $newFile = File::create([
//                     'name' => $fileName,
//                     'type' => $files->getClientOriginalExtension(),
//                     'content' => $fileContent,
//                     'size' => $fileSize,
//                     'observation' => $observation,
//                     'state' => $state,
//                     'briefcase_id' => $briefcase->id,
//                     'document_id' => 1
//                 ]);
    
//                 $newFiles[] = $newFile;
//             } else {
//                 $response['status'] = 'error';
//                 $response['message'] = 'El archivo no es válido';
//                 return response()->json($response, 400);
//             }
//         } else {
//             $response['status'] = 'error';
//             $response['message'] = 'El formato de archivos no es válido';
//             return response()->json($response, 400);
//         }

//         DB::commit();

//         return response()->json([
//             'status' => 'success',
//             'data' => [
//                 'briefcase' => $briefcase,
//                  //'files' => $createdFiles,
//             ],
//             'message' => 'Portafolio creado exitosamente',
//         ]);
//     } catch (\Exception $e) {
//         DB::rollback();
//         return response()->json([
//             'message' => 'Ocurrió un error al crear el portafolio y guardar los archivos.',
//             'error' => $e->getMessage(),
//         ], 500);
//     }
// }


}
