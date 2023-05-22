<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FoundationController extends Controller
{
    public function getFoundation(){
        $foundation = Foundation:: all();

        return new JsonResponse([
            'status' => 'success',
            'data' => ['foundatin' => $foundation],
        ],200);
    }
}
