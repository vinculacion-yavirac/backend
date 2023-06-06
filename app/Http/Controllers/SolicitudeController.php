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

    //Obtener todas las solicitudes
    public function getSolicitude()
    {
        $solicitudes = Solicitude::where('id', '!=', 0)
        ->where('archived', false)
        ->where('status','=','Pendiente')
        ->orWhere('status','=','Pre Aprobado')->get();
        $solicitudes->load(['created_by', 'created_by.person','projects','projects.foundations']);
        return new JsonResponse([
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
             ->first();

         if (!$solicitudes) {
             return response()->json([
                 'message' => 'Solicitud no encontrada'
             ]);
         }

         $solicitudes->load(['created_by', 'created_by.person','projects','projects.foundations']);
         $solicitudes->projects = $solicitudes->projects()->first();

         return response()->json([
             'status' => 'success',
             'data' => [
                 'solicitudes' => $solicitudes
             ],
         ]);
     }

    public function getArchivedSolicitude()
    {
        $solicitudes = Solicitude::where('id', '!=', 0)->where('archived', true)->get();
        $solicitudes->load(['created_by', 'created_by.person']);
        return new JsonResponse([
            'status' => 'success',
            'data' => [
                'solicitudes' => $solicitudes,
            ],
        ], 200);
    }

    public function searchSolicitudeByTerm($term = '')
    {

        $solicitudes = Solicitude::where('id', '!=', 0)
            ->where('archived', false)
            ->where(function ($query) use ($term) {
                $query->where('type_of_request', 'like', '%' . $term . '%')
                    ->orWhereHas('created_by', function ($query) use ($term) {
                        $query->where('person', 'like', '%' . $term . '%')
                         ->orWhere('email', 'like', '%' . $term . '%');
                    });
            })
            ->get();

        $solicitudes->load(['created_by', 'created_by.person']);

        return response()->json([
            'status' => 'success',
            'data' => [
                'solicitudes' => $solicitudes
            ],
        ]);
    }


    public function searchArchivedSolicitudeByTerm($term = '')
    {
        $solicitudes = solicitude::where('type_of_request', 'like', '%' . $term . '%')->where('archived', true)->get();

        $solicitudes->load(['created_by', 'created_by.person']);

        return new JsonResponse([
            'status' =>'success',
            'data' =>['solicitudes' => $solicitudes]
        ]);
    }

    public function ArchiveSolicitud($id)
    {
        $solicitudes = Solicitude::where('id', $id)->where('type_of_request', '!=', 'admin')->first();

        if (!$solicitudes) {
            return response()->json(['message' => 'La solicitud no existe']);
        }

        $solicitudes->archived = true;
        $solicitudes->archived_at = now();
        $solicitudes->archived_by = auth()->user()->id;
        $solicitudes->save();

        return new JsonResponse([
            'status' => 'success',
            'message' => 'Solicitud archivado correctamente',
            'data' => [
                'solicitudes' => $solicitudes,
            ],
        ], 200);
    }

    public function restaureSolicitud($id)
    {
        $solicitudes = Solicitude::find($id);
        $solicitudes->archived = false;
        $solicitudes->save();

        return new JsonResponse([
            'status' => 'success',
            'message' => 'Solicitud restaurada correctamente',
            'data' => [
                'rsolicitudes' => $solicitudes,
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
