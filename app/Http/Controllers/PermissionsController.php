<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionsController extends Controller
{
    //funcion para obtener todos los permisos
    public function getPermissions()
    {
        //obtener permisos
        $permissions = Permission::all();
        //devolver permisos
        return response()->json([
            'status' => 'success',
            'data' => [
                'permissions' => $permissions
            ]
        ]);
    }

    //funcion para obtener permisos por rol
    public function getPermissionsByRole($role)
    {
        //obtener permisos por rol
        $permissions = Permission::role($role)->get();
        //devolver permisos
        return response()->json([
            'status' => 'success',
            'data' => [
                'permissions' => $permissions
            ]
        ]);
    }
}
