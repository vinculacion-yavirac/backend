<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocationPeopleController extends Controller
{
    public function getlocationPeople(){
        $locationpeople = LocationPeople:: all();

        return new JsonResponse([
            'status' => 'success',
            'data' => ['locationpeople' => $locationpeople],
        ],200);
    }
}
