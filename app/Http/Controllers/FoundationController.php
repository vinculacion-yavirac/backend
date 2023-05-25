<?php

namespace App\Http\Controllers;

use App\Models\Foundation;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class FoundationController extends Controller
{
    public function getFoundation(){
        $foundation = Foundation:: all();

        return new JsonResponse([
            'status' => 'success',
            'data' => ['foundation' => $foundation],
        ],200);
    }
}
