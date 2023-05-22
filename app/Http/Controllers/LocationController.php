<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function getLocation(){
        $location = Location:: all();
        return new JsonResponse([
            'status' => 'success',
            'data' => ['location' => $location],
        ], 200);
    }
}