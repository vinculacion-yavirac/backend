<?php

namespace App\Http\Controllers;

use App\Models\Briefcase;
use Illuminate\Http\Request;
use App\Models\Comment;
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
            ->with('project_participant_id.participant_id.person','project_participant_id.project_id.beneficiary_institution_id')
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
    }
