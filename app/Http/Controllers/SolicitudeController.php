<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Person;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Models\Solicitude;

class SolicitudeController extends Controller
{
    public function getSolicitude()
    {
        $solicitudes = Solicitude::where('id', '!=', 0)->where('archived', false)->get();
        $solicitudes->load(['created_by', 'created_by.person']);
        return new JsonResponse([
            'status' => 'success',
            'data' => ['solicitudes' => $solicitudes],
        ], 200);
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
        $solicitudes = solicitude::where('type_of_request', 'like', '%' . $term . '%')->where('archived', false)->get();

        $solicitudes->load(['created_by', 'created_by.person']);

        return new JsonResponse([
            'status' =>'success',
            'data' =>['solicitudes' => $solicitudes]
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
}
