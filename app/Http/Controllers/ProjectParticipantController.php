<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProjectParticipant;

class ProjectParticipantController extends Controller
{

    public function create(Request $request)
    {
        // Validar los datos recibidos
        $validatedData = $request->validate([
            'project_id' => 'required|integer',
            'participant_id' => 'required|integer',
            'role' => 'required|string',
        ]);

        $projectId = $validatedData['project_id'];
        $participantId = $validatedData['participant_id'];
        $role = $validatedData['role'];

        // Crear una nueva instancia de ProjectParticipant
        $projectParticipant = new ProjectParticipant();
        $projectParticipant->project_id = $projectId;
        $projectParticipant->participant_id = $participantId;
        $projectParticipant->role = $role;
        $projectParticipant->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Usuario asignado exitosamente',
            'data' => [
                'projectParticipant' => $projectParticipant,
            ],
        ], 200);
    }



    public function getAllProjectParticipants()
    {
        $projectParticipants = ProjectParticipant::with('project_id.beneficiary_institution_id', 'participant_id.person')
        ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'projectParticipants' => $projectParticipants,
            ],
        ], 200);
    }

    public function update(Request $request, $id)
{
    // Validar los datos recibidos
    $validatedData = $request->validate([
        'project_id' => 'required|integer',
        'participant_id' => 'required|integer',
    ]);

    $projectId = $validatedData['project_id'];
    $participantId = $validatedData['participant_id'];

    // Verificar si el usuario ya está asignado a otro proyecto
    $existingParticipant = ProjectParticipant::where('participant_id', $participantId)
        ->where('id', '!=', $id) // Excluir la asignación que se está actualizando
        ->first();

    if ($existingParticipant) {
        return response()->json([
            'status' => 'error',
            'message' => 'El usuario ya está asignado a otro proyecto.',
        ], 200);
    }

    // Obtener la asignación existente y actualizarla
    $projectParticipant = ProjectParticipant::findOrFail($id);
    $projectParticipant->project_id = $projectId;
    $projectParticipant->participant_id = $participantId;
    $projectParticipant->save();

    return response()->json([
        'status' => 'success',
        'message' => 'Asignación actualizada exitosamente',
        'data' => [
            'projectParticipant' => $projectParticipant,
        ],
    ], 200);
}



    public function getByParticipantId($participantId)
    {
        $projectParticipant = ProjectParticipant::where('participant_id', $participantId)->first();

        if (!$projectParticipant) {
            return response()->json([
                'status' => 'error',
                'message' => 'El estudiante no esta asignado a una fundación',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'projectParticipant' => $projectParticipant,
            ],
        ], 200);
    }

    public function getById($id)
    {
        $projectParticipant = ProjectParticipant::find($id);

        if (!$projectParticipant) {
            return response()->json([
                'status' => 'error',
                'message' => 'ProjectParticipant not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'projectParticipant' => $projectParticipant,
            ],
        ], 200);
    }

    public function destroy($id)
    {
        try {
            $projectParticipant = ProjectParticipant::findOrFail($id);
            $projectParticipant->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Asignación restablecida con exitosamente',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'No se pudo restablecida',
            ], 500);
        }
    }

    public function exist(Request $request)
    {
        $participantId = $request->input('participant_id');

        $exists = ProjectParticipant::where('participant_id', (string) $participantId)->exists();

        return response()->json([
            'exists' => $exists,
        ], 200);
    }


    public function getProjectParticipantByProject($projectId)
    {
        $projectParticipants = ProjectParticipant::with(['project', 'level_id', 'catalogue_id', 'schedule_id', 'state_id', 'participant_id'])->where('project_id', $projectId)->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'projectParticipants' => $projectParticipants,
            ],
        ], 200);
    }

    public function getProjectParticipantByParticipant($participantId)
    {
        $projectParticipants = ProjectParticipant::with(['project', 'level_id', 'catalogue_id', 'schedule_id', 'state_id', 'participant_id'])->where('participant_id', $participantId)->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'projectParticipants' => $projectParticipants,
            ],
        ], 200);
    }


    public function getAllProjectParticipantsTutor()
    {
        $userId = auth()->user()->id;

        $projectIds = ProjectParticipant::where('participant_id', $userId)
            ->pluck('project_id');

        $projectParticipants = ProjectParticipant::whereIn('project_id', $projectIds)
            ->with('project_id.beneficiary_institution_id', 'participant_id.person')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'projectParticipants' => $projectParticipants,
            ],
        ], 200);
    }

}
