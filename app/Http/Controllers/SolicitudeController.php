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

    //Obtener las solicitudes de vinculacion
    public function getSolicitude()
    {
       $solicitudes = Solicitude::where('id', '!=', 0)
           ->where('archived', false)
           ->with('created_by', 'created_by.person', 'solicitudes_status_id', 'type_request_id', 'who_made_request_id','who_made_request_id.project')
           ->get();

       return response()->json([
           'status' => 'success',
           'data' => ['solicitudes' => $solicitudes],
       ], 200);
    }


    //Obtener las Solicitudes por su id
     public function getSolicitudeById($id)
     {
       $solicitudes = Solicitude::where('id', $id)
           ->where('id', '!=', 0)
           ->where('archived', false)
           ->with('created_by', 'created_by.person', 'solicitudes_status_id', 'type_request_id', 'who_made_request_id','who_made_request_id.project')
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


    //Obtener lista de solicitudes archivadas
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


    //buscar por Vinculacion
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


    //buscar por Certificado
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


    //buscar por Pendiente
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


    //buscar por Pre Aprobado
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

    //Transacion para asignar al estudiante y proyectos
        public function assignSolicitude(Request $request, $id)
        {
            $request->validate([
                'approval_date' => 'nullable|date',
                'who_made_request_id' => 'required',
            ]);

            try {
                DB::beginTransaction();

                // Buscar la solicitud
                $solicitudes = Solicitude::findOrFail($id);

                // Actualizar el estado de la solicitud
                $solicitudes->approval_date = now();
                $solicitudes->save();

                // Asociar los proyectos a la solicitud
                $solicitudes->who_made_request_id = $request->who_made_request_id;
                $solicitudes->save();
                DB::commit();

                // Cargar los proyectos asociados para la respuesta
                $solicitudes->load(['created_by', 'created_by.person', 'solicitudes_status_id', 'type_request_id', 'who_made_request_id','who_made_request_id.project']);

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
