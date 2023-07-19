<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Person;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Models\Solicitude;
use App\Models\Catalogue;
use Illuminate\Support\Facades\DB;

class SolicitudeController extends Controller
{


    /**
     * Summary of getSolicitudes
     * @return \Illuminate\Http\JsonResponse
     * Obtener las solicitudes de vinculacion
     */
    public function getSolicitudes()
    {
        $solicitudes = Solicitude::with('created_by', 'created_by.person', 'solicitudes_status_id', 'type_request_id', 'project_id')
            ->where('archived', false)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => ['solicitudes' => $solicitudes],
        ], 200);
    }



     /**
     * Summary of getSolicitudeById
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     * Obtener las Solicitudes por su id
     */
    public function getSolicitudeById($id)
    {
        $solicitudes = Solicitude::with('created_by', 'created_by.person', 'solicitudes_status_id', 'type_request_id', 'project_id')
            ->where('id', $id)
            ->where('archived', false)
            ->first();

        if (!$solicitudes) {
            return response()->json([
                'message' => 'Solicitud no encontrada'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'solicitudes' => $solicitudes
            ],
        ]);
    }


     /**
     * Summary of getArchivedSolicitude
     * @return \Illuminate\Http\JsonResponse
     * Obtener lista de solicitudes archivadas
     */
    public function getArchivedSolicitude()
    {
        $solicitudes = Solicitude::where('archived', true)
            ->with('created_by', 'created_by.person', 'solicitudes_status_id', 'type_request_id')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'solicitudes' => $solicitudes,
            ],
        ]);
    }

     /**
     * Summary of searchSolicitudeByTerm
     * @param mixed $term
     * @return \Illuminate\Http\JsonResponse
     * Buscar por solicitudes
     */
    public function searchSolicitudeByTerm($term = '')
    {
        $term = strtolower($term);

        $solicitudes = Solicitude::where('archived', false)
            ->whereHas('created_by.person', function ($query) use ($term) {
                $query->whereRaw('LOWER(names) like ?', ['%' . $term . '%'])
                    ->orWhereRaw('LOWER(last_names) like ?', ['%' . $term . '%'])
                    ->orWhereRaw('LOWER(identification) like ?', ['%' . $term . '%']);
            })
            ->with('created_by', 'created_by.person', 'solicitudes_status_id', 'type_request_id')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'solicitudes' => $solicitudes
            ],
        ]);
    }


        /**
     * Summary of searchArchivedSolicitudeByTerm
     * @param mixed $term
     * @return \Illuminate\Http\JsonResponse
     * Busca por las solicitudes archivadas
     */
    public function searchArchivedSolicitudeByTerm($term = '')
    {
         $term = strtolower($term);

         $solicitudes = Solicitude::where('archived', true)
             ->whereHas('created_by.person', function ($query) use ($term) {
                 $query->whereRaw('LOWER(names) like ?', ['%' . $term . '%'])
                     ->orWhereRaw('LOWER(last_names) like ?', ['%' . $term . '%'])
                     ->orWhereRaw('LOWER(identification) like ?', ['%' . $term . '%']);
             })
             ->with('created_by', 'created_by.person', 'solicitudes_status_id', 'type_request_id')
             ->get();

         return response()->json([
             'status' => 'success',
             'data' => [
                 'solicitudes' => $solicitudes
             ],
         ]);
    }

        /**
     * Summary of filterSolicitudeByValue
     * @param mixed $value
     * @return \Illuminate\Http\JsonResponse
     * Filtro de tipo de solicitud
     */
    public function filterSolicitudeByValue($value = '')
    {
        $solicitudes = Solicitude::where('archived', false)
        ->whereHas('type_request_id', function ($query) use ($value) {
            $query->where('catalog_type', 'Tipo Solicitud')
                ->where('catalog_value', $value);
        })->with('created_by.person', 'solicitudes_status_id', 'type_request_id')->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'solicitudes' => $solicitudes,
            ],
        ]);
    }


    /**
     * Summary of filterSolicitudeByStatus
     * @param mixed $status
     * @return \Illuminate\Http\JsonResponse
     * Filtro por estado
     */
    public function filterSolicitudeByStatus($status = '')
    {
        $solicitudes = Solicitude::where('archived', false)
        ->whereHas('solicitudes_status_id', function ($query) use ($status) {
            $query->where('catalog_type', 'Estado Solicitud')
                ->where('catalog_value', $status);
        })->with('created_by.person', 'solicitudes_status_id', 'type_request_id')->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'solicitudes' => $solicitudes,
            ],
        ]);
    }


        /**
     * Summary of searchSolicitudeVinculacionByTerm
     * @param mixed $term
     * @return \Illuminate\Http\JsonResponse
     * Buscar solo por vinculacion
     */
    public function searchSolicitudeVinculacionByTerm($term = '')
    {
        $solicitudes = Solicitude::where('archived', false)
            ->whereHas('type_request_id', function ($query) {
                $query->where('catalog_type', 'Tipo Solicitud')
                    ->where('catalog_value', 'Vinculación');
            })
            ->where(function ($query) use ($term) {
                $query->whereHas('created_by.person', function ($query) use ($term) {
                    $query->whereRaw('LOWER(names) like ?', ['%' . strtolower($term) . '%'])
                        ->orWhereRaw('LOWER(last_names) like ?', ['%' . strtolower($term) . '%'])
                        ->orWhereRaw('LOWER(identification) like ?', ['%' . strtolower($term) . '%']);
                });
            })
            ->with('created_by.person', 'solicitudes_status_id', 'type_request_id')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'solicitudes' => $solicitudes
            ],
        ]);
    }


    /**
     * Summary of searchCertificateByTerm
     * @param mixed $term
     * @return \Illuminate\Http\JsonResponse
     * Buscar por Certificado
     */
    public function searchCertificateByTerm($term = '')
    {
        $solicitudes = Solicitude::where('archived', false)
            ->whereHas('type_request_id', function ($query) {
                $query->where('catalog_type', 'Tipo Solicitud')
                    ->where('catalog_value', 'Certificado');
            })
            ->where(function ($query) use ($term) {
                $query->whereHas('created_by.person', function ($query) use ($term) {
                    $query->whereRaw('LOWER(names) like ?', ['%' . strtolower($term) . '%'])
                        ->orWhereRaw('LOWER(last_names) like ?', ['%' . strtolower($term) . '%'])
                        ->orWhereRaw('LOWER(identification) like ?', ['%' . strtolower($term) . '%']);
                });
            })
            ->with('created_by.person', 'solicitudes_status_id', 'type_request_id')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'solicitudes' => $solicitudes
            ],
        ]);
    }


        /**
     * Summary of searchPendienteByTerm
     * @param mixed $term
     * @return \Illuminate\Http\JsonResponse
     * Buscar por Pendiente
     */
    public function searchPendienteByTerm($term = '')
    {
        $solicitudes = Solicitude::where('archived', false)
            ->whereHas('solicitudes_status_id', function ($query) {
                $query->where('catalog_type', 'Estado Solicitud')
                    ->where('catalog_value', 'Pendiente');
            })
            ->where(function ($query) use ($term) {
                $query->whereHas('created_by.person', function ($query) use ($term) {
                    $query->whereRaw('LOWER(names) like ?', ['%' . strtolower($term) . '%'])
                        ->orWhereRaw('LOWER(last_names) like ?', ['%' . strtolower($term) . '%'])
                        ->orWhereRaw('LOWER(identification) like ?', ['%' . strtolower($term) . '%']);
                });
            })
            ->with('created_by.person', 'solicitudes_status_id', 'type_request_id')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'solicitudes' => $solicitudes
            ],
        ]);
    }


    /**
     * Summary of searchAprobadoByTerm
     * @param mixed $term
     * @return \Illuminate\Http\JsonResponse
     * Buscar por Pre Aprobado
     */
    public function searchAprobadoByTerm($term = '')
    {
        $solicitudes = Solicitude::where('archived', false)
            ->whereHas('solicitudes_status_id', function ($query) {
                $query->where('catalog_type', 'Estado Solicitud')
                    ->where('catalog_value', 'Aprobado');
            })
            ->where(function ($query) use ($term) {
                $query->whereHas('created_by.person', function ($query) use ($term) {
                    $query->whereRaw('LOWER(names) like ?', ['%' . strtolower($term) . '%'])
                        ->orWhereRaw('LOWER(last_names) like ?', ['%' . strtolower($term) . '%'])
                        ->orWhereRaw('LOWER(identification) like ?', ['%' . strtolower($term) . '%']);
                });
            })
            ->with('created_by.person', 'solicitudes_status_id', 'type_request_id')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'solicitudes' => $solicitudes
            ],
        ]);
    }


    /**
     * Summary of archiveSolicitud
     * @param mixed $id
     * @return JsonResponse
     * Archiva solicitudes por id
     */
    public function archiveSolicitud($id)
    {
        $solicitudes = Solicitude::findOrFail($id);

        $solicitudes->update([
            'archived' => true,
            'archived_at' => now(),
            'archived_by' => auth()->user()->id,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Solicitud archivada correctamente',
            'data' => [
                'solicitudes' => $solicitudes,
            ],
        ], 200);
    }

    /**
     * Summary of restoreSolicitud
     * @param mixed $id
     * @return JsonResponse
     * Restaura solicitudes por id
     */
    public function restoreSolicitud($id)
    {
        $solicitudes = Solicitude::findOrFail($id);

        $solicitudes->update([
            'archived' => false,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Solicitud restaurada correctamente',
            'data' => [
                'solicitudes' => $solicitudes,
            ],
        ], 200);
    }

        /**
     * Summary of assignSolicitude
     * @param \Illuminate\Http\Request $request
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     * Transacción para asignar al estudiante y proyectos
     */
    public function assignSolicitude(Request $request, $id)
    {
        $request->validate([
            'approval_date' => 'nullable|date',
            'project_id' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $solicitudes = Solicitude::findOrFail($id);

            $solicitudes->update([
                'approval_date' => now(),
                'solicitudes_status_id' => 4,
                'project_id' => $request->project_id,
            ]);

            DB::commit();

            $solicitudes->load(['created_by', 'created_by.person', 'solicitudes_status_id', 'type_request_id', 'project_id']);

            return response()->json([
                'status' => 'success',
                'message' => 'Relación actualizada correctamente',
                'data' => [
                    'solicitudes' => $solicitudes,
                ],
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => 'error',
                'message' => 'Error al actualizar la relación: ' . $e->getMessage(),
            ], 500);
        }
    }

    /* 
    * Summary of createSolicitude
    * @param \Illuminate\Http\Request $request
    * @return \Illuminate\Http\JsonResponse
    * Crear una nueva solicitud
    */
   public function createSolicitude(Request $request)
   {
       $request->validate([
           'approval_date' => 'nullable|date',
           'solicitudes_status_id' => 'required',
           'type_request_id' => 'required',
           'created_by' => 'required',
       ]);

       try {
           $solicitud = Solicitude::create([
               'approval_date' => $request->approval_date,
               'solicitudes_status_id' => $request->solicitudes_status_id,
               'type_request_id' => $request->type_request_id,
               'created_by' => $request->created_by,
               'archived' => false,
           ]);

           return response()->json([
               'status' => 'success',
               'message' => 'Solicitud creada correctamente',
               'data' => [
                   'solicitud' => $solicitud,
               ],
           ], 201);
       } catch (\Exception $e) {
           return response()->json([
               'status' => 'error',
               'message' => 'Error al crear la solicitud: ' . $e->getMessage(),
           ], 500);
       }
   }
   public function getAllCatalogues()
    {
        try {
            $catalogues = Catalogue::all();

            return response()->json([
                'status' => 'success',
                'data' => [
                    'catalogues' => $catalogues,
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener los catálogos: ' . $e->getMessage(),
            ], 500);
        }
    }
}
