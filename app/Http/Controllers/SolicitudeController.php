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
           ->whereHas('type_request_id', function ($query) {
               $query->where('catalog_type', 'Tipo Solicitud')
                     ->where('catalog_value', 'Vinculación');
           })
           ->with('created_by', 'created_by.person', 'solicitudes_status_id', 'type_request_id')
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
           ->with('created_by', 'created_by.person', 'solicitudes_status_id', 'type_request_id')
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
          ->whereHas('type_request_id', function ($query) {
              $query->where('catalog_type', 'Tipo Solicitud')
                  ->where('catalog_value', 'Vinculación');
          })
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
                   $query->where('names', 'like', '%' . $term . '%')
                       ->orWhere('last_names', 'like', '%' . $term . '%')
                       ->orWhere('identification', 'like', '%' . $term . '%');
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
                   $query->where('names', 'like', '%' . $term . '%')
                       ->orWhere('last_names', 'like', '%' . $term . '%')
                       ->orWhere('identification', 'like', '%' . $term . '%');
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

    //Transacion para asignar al estudiante y proyectos
        public function assignSolicitude(Request $request, $id)
        {
            $request->validate([
                'status' => 'required',
                'projects' => 'required|array',
                'projects.*.id' => 'required|exists:projects,id',
            ]);

            try {
                DB::beginTransaction();

                // Buscar la solicitud
                $solicitudes = Solicitude::find($id);
                if (!$solicitudes) {
                    return response()->json([
                        'message' => 'No se encontró la solicitud',
                    ], 404);
                }

                // Actualizar el estado de la solicitud
                $solicitudes->status = $request->status;
                $solicitudes->save();

                // Asociar los proyectos a la solicitud
                $projectIds = collect($request->projects)->pluck('id');
                $solicitudes->projects()->sync($projectIds);

                DB::commit();

                // Cargar los proyectos asociados para la respuesta
                //$solicitudes->load('projects');
                $solicitudes->load(['created_by', 'created_by.person','projects','projects.foundations']);

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



}
