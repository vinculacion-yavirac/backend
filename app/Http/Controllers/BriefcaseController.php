<?php

namespace App\Http\Controllers;

use App\Models\Briefcase;
use Illuminate\Http\Request;
use App\Models\Comment;
use Symfony\Component\HttpFoundation\JsonResponse;

class BriefcaseController extends Controller
{
   /**
    * Summary of getBriefcase
    * @return JsonResponse
    * Obtener todos los portafolios
    */
   public function getBriefcase()
     {
        $briefcases =  Briefcase::where('id', '!=', 0)
           ->where('archived', false)
           ->with('project_participant_id.participant_id.person')
           ->get();

        return new JsonResponse([
            'status' => 'success',
            'data' => ['briefcases' => $briefcases],
/*             'total' => count(
                $briefcase) */
        ], 200);
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
          ->where('id', '!=', 0)
          ->where('archived', false)
          ->with('project_participant_id.participant_id.person')
          ->first();

      if (!$briefcases) {
          return response()->json([
              'message' => 'Portafolio no encontrada'
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
     * Summary of ArchiveBriefcase
     * @param mixed $id
     * @return JsonResponse
     * Archivar portafolio
     */
    public function ArchiveBriefcase($id)
    {
      $briefcases = Briefcase::findOrFail($id);

      $briefcases->archived = true;
      $briefcases->archived_at = now();
      $briefcases->archived_by = auth()->user()->id;
      $briefcases->save();

      return new JsonResponse([
          'status' => 'success',
          'message' => 'Portafolio archivada correctamente',
          'data' => [
              'briefcases' => $briefcases,
          ],
      ], 200);
    }

    /**
     * Summary of restaureBriefcase
     * @param mixed $id
     * @return JsonResponse
     * Restaurar un portafolio
     */
    public function restaureBriefcase($id)
    {
      $briefcases = Briefcase::findOrFail($id);

      $briefcases->archived = false;
      $briefcases->save();

      return new JsonResponse([
          'status' => 'success',
          'message' => 'Solicitud restaurada correctamente',
          'data' => [
              'briefcases' => $briefcases,
          ],
      ], 200);
    }

    /**
     * Summary of filterBriefcaseByStatus
     * @param mixed $state
     * @return \Illuminate\Http\JsonResponse
     * Filtro para obtener el state 
     */
    public function filterBriefcaseByStatus($state = '')
    {
      $briefcases = Briefcase::where('id', '!=', 0)
      ->where('archived', false)
      ->where('state', $state)
      ->with('project_participant_id.participant_id.person')
      ->get();

      return response()->json([
          'status' => 'success',
          'data' => [
            'briefcases' => $briefcases
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
       $briefcases = Briefcase::where('id', '!=', 0)
           ->where('archived', false)
           ->where(function ($query) use ($term) {
               $query->orWhereHas('project_participant_id.participant_id.person', function ($query) use ($term) {
                   $query->whereRaw('LOWER(names) like ?', ['%' . $term . '%'])
                       ->orWhereRaw('LOWER(last_names) like ?', ['%' . $term . '%'])
                       ->orWhereRaw('LOWER(identification) like ?', ['%' . $term . '%']);
               });
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
     * Summary of searchAprobadoByTerm
     * @param mixed $term
     * @return \Illuminate\Http\JsonResponse
     * Buscador para state tipo true
     */
    public function searchAprobadoByTerm($term = '')
    {
       $briefcases = Briefcase::where('id', '!=', 0)
           ->where('archived', false)
           ->where('state', true)
           ->with('project_participant_id.participant_id.person')
           ->where(function ($query) use ($term) {
               $query->orWhereHas('project_participant_id.participant_id.person', function ($query) use ($term) {
                   $query->whereRaw('LOWER(names) like ?', ['%' . $term . '%'])
                       ->orWhereRaw('LOWER(last_names) like ?', ['%' . $term . '%'])
                       ->orWhereRaw('LOWER(identification) like ?', ['%' . $term . '%']);
               });
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
       $briefcases = Briefcase::where('id', '!=', 0)
           ->where('archived', false)
           ->where('state', false)
           ->with('project_participant_id.participant_id.person')
           ->where(function ($query) use ($term) {
               $query->orWhereHas('project_participant_id.participant_id.person', function ($query) use ($term) {
                   $query->whereRaw('LOWER(names) like ?', ['%' . $term . '%'])
                       ->orWhereRaw('LOWER(last_names) like ?', ['%' . $term . '%'])
                       ->orWhereRaw('LOWER(identification) like ?', ['%' . $term . '%']);
               });
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
     * Summary of getArchivedPortafolio
     * @return \Illuminate\Http\JsonResponse
     * Obtener todas las que sean true en archived
     */
    public function getArchivedBriefcase()
    {
      $briefcases = Briefcase::where('id', '!=', 0)
          ->where('archived', true)
          ->with('project_participant_id.participant_id.person')
          ->get();

      return response()->json([
          'status' => 'success',
          'data' => [
              'briefcases' => $briefcases,
          ],
      ]);
    }

    public function searchArchivedBriefcaseByTerm($term = '')
    {
       $briefcases = Briefcase::where('id', '!=', 0)
           ->where('archived', true)
           ->where(function ($query) use ($term) {
               $query->orWhereHas('project_participant_id.participant_id.person', function ($query) use ($term) {
                   $query->whereRaw('LOWER(names) like ?', ['%' . $term . '%'])
                       ->orWhereRaw('LOWER(last_names) like ?', ['%' . $term . '%'])
                       ->orWhereRaw('LOWER(identification) like ?', ['%' . $term . '%']);
               });
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




    public function createBriefcase(Request $request)
    {
        $request->validate([
            'subject' => 'required|string',
            'description' => 'required|string',
        ]);

        try {
            // Crear registro del documento oficial
            $briefcases = Briefcase::create(array_merge(
                $request->except('foundations','solicitudes','briefcases'),
                ['created_by' => auth()->user()->id]
            ));

            // $briefcases = Briefcase::create(array_merge(
            //     $request->except('foundations','solicitudes','briefcases'),
            //     ['created_by' => auth()->user()->id]
            // ));

            // Guardar comentario
            if ($request->comment){
                Comment::create([
                    'comment' => $request->comment,
                    'briefcases' => $briefcases->id,
                    'created_by' => auth()->user()->id
                ]);
            }


            return response()->json([
                'status' => 'success',
                'data' => [
                    'briefcases' => $briefcases
                ],
                'message' => 'Portafolio creado con éxito'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear el Portafolio: ' . $e->getMessage()
            ]);
        }
    }


    public function updateBriefcase(Request $request, $id)
    {
        $request->validate([
            'asunto' => 'required|string',
            'estado' => 'required|boolean'
        ]);

        $briefcases = Briefcase::find($id);
        if (!$briefcases) {
            return response()->json([
                'message' => 'No se encontró el oficio especificado'
            ], 404);
        }

        $briefcases->asunto = $request->asunto;
        $briefcases->estado = $request->estado;

        try {
            $briefcases->save();
            return response()->json([
                'status' => 'success',
                'message' => 'Portafolio actualizado con éxito'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar el Portafolio: ' . $e->getMessage()
            ]);
        }
    }




    public function deleteBriefcase($id)
    {
        $briefcases = Briefcase::find($id);

        if (!$briefcases) {
            return response()->json([
                'message' => 'Portafolio no encontrado'
            ]);
        }

        $briefcases->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Portafolio eliminado correctamente'
        ]);
    }
}
