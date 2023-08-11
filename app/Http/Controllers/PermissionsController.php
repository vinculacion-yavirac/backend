<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;


class PermissionsController extends Controller
{
    /**
     * Get all permissions.
     *
     * @OA\Get(
     *     path="/api/permissions",
     *     summary="Obtener todos los permisos",
     *     tags={"Permissions"},
     *     security={{"bearer":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="permissions", type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="LEER_USUARIO"),
     *                         @OA\Property(property="guard_name", type="string", example="acces"),
     *                         @OA\Property(property="created_at", type="timestamp without time zone", example="2023-07-22 19:24:17"),
     *                         @OA\Property(property="updated_at", type="timestamp without time zone", example="2023-07-22 19:24:17")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Error interno del servidor"),
     *             @OA\Property(property="file", type="string"),
     *             @OA\Property(property="line", type="integer"),
     *             @OA\Property(property="errors", type="array", @OA\Items(type="string"))
     *         )
     *     )
     * )
     */
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

    /**
     * Get permissions by role.
     *
     * @OA\Get(
     *     path="/api/permissions/role/{value}",
     *     summary="Obtener permisos por rol",
     *     tags={"Permissions"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="role",
     *         in="path",
     *         required=true,
     *         description="Nombre del rol",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="permissions", type="array",
     *                     @OA\Items(
     *                         type="object",
     *                         @OA\Property(property="id", type="integer", example=1),
     *                         @OA\Property(property="name", type="string", example="LEER_USUARIO"),
     *                         @OA\Property(property="guard_name", type="string", example="acces"),
     *                         @OA\Property(property="created_at", type="timestamp without time zone", example="2023-07-23T00:24:18.000000Z"),
     *                         @OA\Property(property="updated_at", type="timestamp without time zone", example="2023-07-23T00:24:18.000000Z"),
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Role not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Role not found"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Error interno del servidor"),
     *             @OA\Property(property="file", type="string"),
     *             @OA\Property(property="line", type="integer"),
     *             @OA\Property(property="errors", type="array", @OA\Items(type="string"))
     *         )
     *     )
     * )
     */
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
