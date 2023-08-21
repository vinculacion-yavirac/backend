<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Symfony\Component\HttpFoundation\JsonResponse;

class AttendanceController extends Controller
{
    public function createAttendance(Request $request){
        try {
            $attendance = Attendance::create([
                'user_id' => $request->input('user_id'),
                'entry_time' => $request->input('entry_time'),
                'exit_time' => $request->input('exit_time'),
                'observation' => $request->input('observation'),
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $attendance,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear el oficio: ' . $e->getMessage()
            ]);
        }
    }

    public function getAllAttendances(Request $request){
        $user_id = $request->input('user_id');

        $attendances = Attendance::where('user_id', $user_id)->get();

        return response()->json([
            'status' => 'success',
            'data' => $attendances,
        ], 200);
    }
}
