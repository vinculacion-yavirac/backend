<?php

namespace App\Http\Controllers;

use App\Models\Foundation;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class FoundationController extends Controller
{
    public function getFoundation(){
        $foundations = Foundation::all();

        return new JsonResponse([
            'status' => 'success',
            'data' => ['foundations' => $foundations],
        ],200);
    }

    public function getFoundationById($id)
    {
        $foundations = Foundation::where('id', $id)->get();

        $foundations->load(['comments', 'files']);

        return new JsonResponse([
            'status' => 'success',
            'data' => ['foundations#' . $id => $foundations]
        ], 200);
    }

    public function searchFoundationByTerm($term = '')
    {
        $foundations = Foundation::where('name', 'like', '%' . $term . '%')->get();

        $foundations->load(['created_by', 'created_by.person']);

        return new JsonResponse([
            'status' => 'success',
            'data' => [
                'foundations' => $foundations,
            ],
        ], 200);
    }


    public function createFoundation(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'status' => 'required|boolean',
            'authorized_person' => 'required|string',
            'number_ruc' => 'required|string',
            'economic_activity' => 'required|string',
            'company_email' => 'required|string',
            'company_number' => 'required|string' ,
            'received_students'=> 'required|integer',
            'direct_benefit' => 'required|string',
            'indirect_benefits'=> 'required|string',
        ]);

        try {
            // Crear registro del documento oficial
            $foundations = Foundation::create(array_merge(
                $request->except('files', 'comments'),
                ['created_by' => auth()->user()->id]
            ));

            // Guardar comentario
            // if ($request->comment){
            //     Comment::create([
            //         'comment' => $request->comment,
            //         'foundations' => $foundations->id,
            //         'created_by' => auth()->user()->id
            //     ]);
            // }


            return response()->json([
                'status' => 'success',
                'data' => [
                    'foundations' => $foundations
                ],
                'message' => 'Fundación creada con éxito'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear la Fundación: ' . $e->getMessage()
            ]);
        }
    }



}
