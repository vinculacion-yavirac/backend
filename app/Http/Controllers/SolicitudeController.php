<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Person;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Models\Solicitude;
use Illuminate\Support\Facades\DB;

class SolicitudeController extends Controller
{

    
    /**
     * Summary of getSolicitude
     * @return \Illuminate\Http\JsonResponse
     * Obtener las solicitudes de vinculacion
     */
    public function getSolicitude()
    {
       $solicitudes = Solicitude::where('id', '!=', 0)
           ->where('archived', false)
           ->with('created_by', 'created_by.person', 'solicitudes_status_id', 'type_request_id', 'project_id')
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
       $solicitudes = Solicitude::where('id', $id)
           ->where('id', '!=', 0)
           ->where('archived', false)
           ->with('created_by', 'created_by.person', 'solicitudes_status_id', 'type_request_id', 'project_id')
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
      $solicitudes = Solicitude::where('id', '!=', 0)
          ->where('archived', true)
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
     * Buscarpor de solicitudes
     */
    public function searchSolicitudeByTerm($term = '')
    {
       $solicitudes = Solicitude::where('id', '!=', 0)
           ->where('archived', false)
           ->where(function ($query) use ($term) {
               $query->orWhereHas('created_by.person', function ($query) use ($term) {
                   $query->whereRaw('LOWER(names) like ?', ['%' . $term . '%'])
                       ->orWhereRaw('LOWER(last_names) like ?', ['%' . $term . '%'])
                       ->orWhereRaw('LOWER(identification) like ?', ['%' . $term . '%']);
               });
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
     * Summary of searchSolicitudeVinculacionByTerm
     * @param mixed $term
     * @return \Illuminate\Http\JsonResponse
     * Buscar solo por vinculacion
     */
    public function searchSolicitudeVinculacionByTerm($term = '')
    {
       $solicitudes = Solicitude::where('id', '!=', 0)
           ->where('archived', false)
           ->whereHas('type_request_id', function ($query) {
               $query->where('catalog_type', 'Tipo Solicitud')
                   ->where('catalog_value', 'Vinculación');
           })
           ->with('created_by', 'created_by.person', 'solicitudes_status_id', 'type_request_id')
           ->where(function ($query) use ($term) {
               $query->orWhereHas('created_by.person', function ($query) use ($term) {
                   $query->whereRaw('LOWER(names) like ?', ['%' . $term . '%'])
                       ->orWhereRaw('LOWER(last_names) like ?', ['%' . $term . '%'])
                       ->orWhereRaw('LOWER(identification) like ?', ['%' . $term . '%']);
               });
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
     * Summary of searchCertificateByTerm
     * @param mixed $term
     * @return \Illuminate\Http\JsonResponse
     * Buscar por Certificado
     */
    public function searchCertificateByTerm($term = '')
    {
       $solicitudes = Solicitude::where('id', '!=', 0)
           ->where('archived', false)
           ->whereHas('type_request_id', function ($query) {
               $query->where('catalog_type', 'Tipo Solicitud')
                   ->where('catalog_value', 'Certificado');
           })
           ->with('created_by', 'created_by.person', 'solicitudes_status_id', 'type_request_id')
           ->where(function ($query) use ($term) {
               $query->orWhereHas('created_by.person', function ($query) use ($term) {
                   $query->whereRaw('LOWER(names) like ?', ['%' . $term . '%'])
                       ->orWhereRaw('LOWER(last_names) like ?', ['%' . $term . '%'])
                       ->orWhereRaw('LOWER(identification) like ?', ['%' . $term . '%']);
               });
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
     * Summary of searchPendienteByTerm
     * @param mixed $term
     * @return \Illuminate\Http\JsonResponse
     * Buscar por Pendiente
     */
    public function searchPendienteByTerm($term = '')
    {
       $solicitudes = Solicitude::where('id', '!=', 0)
           ->where('archived', false)
           ->whereHas('solicitudes_status_id', function ($query) {
               $query->where('catalog_type', 'Estado Solicitud')
                   ->where('catalog_value', 'Pendiente');
           })
           ->with('created_by', 'created_by.person', 'solicitudes_status_id', 'type_request_id')
           ->where(function ($query) use ($term) {
               $query->orWhereHas('created_by.person', function ($query) use ($term) {
                   $query->whereRaw('LOWER(names) like ?', ['%' . $term . '%'])
                       ->orWhereRaw('LOWER(last_names) like ?', ['%' . $term . '%'])
                       ->orWhereRaw('LOWER(identification) like ?', ['%' . $term . '%']);
               });
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
     * Summary of searchPreAprobadoByTerm
     * @param mixed $term
     * @return \Illuminate\Http\JsonResponse
     * Buscar por Pre Aprobado
     */
    public function searchPreAprobadoByTerm($term = '')
    {
       $solicitudes = Solicitude::where('id', '!=', 0)
           ->where('archived', false)
           ->whereHas('solicitudes_status_id', function ($query) {
               $query->where('catalog_type', 'Estado Solicitud')
                   ->where('catalog_value', 'Pre Aprobado');
           })
           ->with('created_by', 'created_by.person', 'solicitudes_status_id', 'type_request_id')
           ->where(function ($query) use ($term) {
               $query->orWhereHas('created_by.person', function ($query) use ($term) {
                   $query->whereRaw('LOWER(names) like ?', ['%' . $term . '%'])
                       ->orWhereRaw('LOWER(last_names) like ?', ['%' . $term . '%'])
                       ->orWhereRaw('LOWER(identification) like ?', ['%' . $term . '%']);
               });
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
       $solicitudes = Solicitude::where('id', '!=', 0)
           ->where('archived', true)
           ->where(function ($query) use ($term) {
               $query->orWhereHas('created_by.person', function ($query) use ($term) {
                   $query->whereRaw('LOWER(names) like ?', ['%' . $term . '%'])
                       ->orWhereRaw('LOWER(last_names) like ?', ['%' . $term . '%'])
                       ->orWhereRaw('LOWER(identification) like ?', ['%' . $term . '%']);
               });
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
      $solicitudes = Solicitude::whereHas('type_request_id', function ($query) use ($value) {
          $query->where('catalog_type', 'Tipo Solicitud')
              ->where('catalog_value', $value);
      })
      ->with('created_by', 'created_by.person', 'solicitudes_status_id', 'type_request_id')
      ->get();

      return response()->json([
          'status' => 'success',
          'data' => [
              'solicitudes' => $solicitudes,
          ],
      ], 200);
    }


    /**
     * Summary of filterSolicitudeByStatus
     * @param mixed $status
     * @return \Illuminate\Http\JsonResponse
     * Filtro por estado
     */
    public function filterSolicitudeByStatus($status = '')
    {
      $solicitudes = Solicitude::whereHas('solicitudes_status_id', function ($query) use ($status) {
          $query->where('catalog_type', 'Estado Solicitud')
              ->where('catalog_value', $status);
      })
      ->with('created_by', 'created_by.person', 'solicitudes_status_id', 'type_request_id')
      ->get();

      return response()->json([
          'status' => 'success',
          'data' => [
              'solicitudes' => $solicitudes,
          ],
      ], 200);
    }

    /**
     * Summary of ArchiveSolicitud
     * @param mixed $id
     * @return JsonResponse
     * Archiva solicitudes por id
     */
    public function ArchiveSolicitud($id)
    {
      $solicitudes = Solicitude::findOrFail($id);

      $solicitudes->archived = true;
      $solicitudes->archived_at = now();
      $solicitudes->archived_by = auth()->user()->id;
      $solicitudes->save();

      return new JsonResponse([
          'status' => 'success',
          'message' => 'Solicitud archivada correctamente',
          'data' => [
              'solicitudes' => $solicitudes,
          ],
      ], 200);
    }

    /**
     * Summary of restaureSolicitud
     * @param mixed $id
     * @return JsonResponse
     * Restaura solicitudes por id
     */
    public function restaureSolicitud($id)
    {
      $solicitudes = Solicitude::findOrFail($id);

      $solicitudes->archived = false;
      $solicitudes->save();

      return new JsonResponse([
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
     * Transacion para asignar al estudiante y proyectos
     */
    public function assignSolicitude(Request $request, $id)
    {
        $request->validate([
            'approval_date' => 'nullable|date',
            'project_id' => 'required',
        ]);

        try {
            DB::beginTransaction();

            // Buscar la solicitud
            $solicitudes = Solicitude::findOrFail($id);

            // Actualizar el estado de la solicitud
            $solicitudes->approval_date = now();
            $solicitudes->save();

            // Asociar los proyectos a la solicitud
            $solicitudes->project_id = $request->project_id;
            $solicitudes->save();
            DB::commit();

            // Cargar los proyectos asociados para la respuesta
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

    public function getProjectByFoundation($project)
    {
        try {
            // Obtener el proyecto por su nombre
            //$project = Project::where('id', $project)->orWhere('name', $project)->first();

            if (is_numeric($project)) {
                // Buscar el proyecto por ID
                //$project = Project::find($project);
                // Buscar el proyecto por ID y cargar las fundaciones y sus campos
                $project = Project::with('foundations')->find($project);
            } else {
                // Buscar el proyecto por nombre
                //$project = Project::where('name', $project)->first();
                // Buscar el proyecto por nombre y cargar las fundaciones y sus campos
                $project = Project::with('foundations')->where('name', $project)->first();
            }

            if (!$project) {
                throw new \Exception('Project not found.');
            }

            // Obtener la fundación asociada al proyecto
            $foundations = $project->foundations;

            return response()->json([
                'status' => 'success',
                'data' => [
                    'project' => $project
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}
