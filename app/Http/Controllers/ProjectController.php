<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    public function getProject(){
        $projects = Project::all();
        $projects -> load(['foundations','created_by']);
        return new JsonResponse([
            'status' => 'success',
            'data' => ['projects' => $projects]
        ],200);
    }
}
