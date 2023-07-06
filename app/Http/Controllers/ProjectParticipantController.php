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
        ]);

        $projectId = $validatedData['project_id'];
        $participantId = $validatedData['participant_id'];

        // Verificar si el usuario ya está asignado a un proyecto
        $existingParticipant = ProjectParticipant::where('participant_id', $participantId)->first();

        if ($existingParticipant) {
            return response()->json([
                'status' => 'error',
                'message' => 'El usuario ya está asignado a un proyecto.',
            ], 400);
        }

        // Crear una nueva instancia de ProjectParticipant
        $projectParticipant = new ProjectParticipant();
        $projectParticipant->project_id = $projectId;
        $projectParticipant->participant_id = $participantId;
        $projectParticipant->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Estuante asignado exitosamente',
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
            'message' => 'ProjectParticipant no encontrado',
        ], 404);
    }

    return response()->json([
        'status' => 'success',
        'data' => [
            'projectParticipant' => $projectParticipant,
        ],
    ], 200);
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
    //--------------------------------------------------

}
