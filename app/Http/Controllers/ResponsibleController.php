<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Responsible;
use Symfony\Component\HttpFoundation\JsonResponse;

class ResponsibleController extends Controller
{
   public function getResponsible()
     {
        $responsible = Responsible::where('charge_id', '=', 1)
        ->with('user_id', 'user_id.person')
        ->get();
        return new JsonResponse([
            'status' => 'success',
            'data' => ['responsible' => $responsible]
        ], 200);
    }
}
