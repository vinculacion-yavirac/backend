<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Person;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @OA\Schema(
 *     schema="Role",
 *     title="Roles",
 *     description="Role model",
 *     @OA\Property(property="id", type="integer", format="int64"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="guard_name", type="string"),
 *     @OA\Property(property="archived", type="boolean", default=false),
 *     @OA\Property(property="archived_at", type="string", format="date-time"),
 *     @OA\Property(property="archived_by", type="integer"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 *     @OA\Property(
 *         property="permissions",
 *         type="array",
 *         @OA\Items(
 *             type="object",
 *             @OA\Property(property="name", type="string")
 *         )
 *     )
 * )
 */
class RolesController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/roles",
     *     summary="Obtener roles",
     *     tags={"Roles"},
     *     operationId="getRoles",
     *     security={{"bearer":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Operación exitosa",
     *          @OA\JsonContent(ref="#/components/schemas/Role")
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Error interno del servidor",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Error al archivar el usuario: error interno del servidor"
     *              )
     *          )
     *      )
     * )
     */
    public function getRoles()
    {
        $roles = Role::where('id', '!=', 1)->where('archived', false)->get();

        $roles->load('permissions');

        return new JsonResponse([
            'status' => 'success',
            'data' => [
                'roles' => $roles,
            ],
        ], 200);
    }

    public function getRolesWithPermissions()
    {
        $roles = Role::all();

        $roles->load('permissions');

        return new JsonResponse([
            'status' => 'success',
            'data' => [
                'roles' => $roles,
            ],
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/roles/{id}",
     *     summary="Obtener roles por id",
     *     tags={"Roles"},
     *     operationId="getRoleById",
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del rol a obtener",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             example=123
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Operación exitosa",
     *          @OA\JsonContent(ref="#/components/schemas/Role")
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Error interno del servidor",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Error al archivar el usuario: error interno del servidor"
     *              )
     *          )
     *      )
     * )
     */
    public function getRoleById($id)
    {
        $role = Role::find($id);

        $role->load('permissions');

        return new JsonResponse([
            'status' => 'success',
            'data' => [
                'role' => $role,
            ],
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/roles/archived/list",
     *     summary="Obtener roles archivados",
     *     tags={"Roles"},
     *     operationId="getArchivedRoles",
     *     security={{"bearer":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Operación exitosa",
     *          @OA\JsonContent(ref="#/components/schemas/Role")
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Error interno del servidor",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Error al archivar el usuario: error interno del servidor"
     *              )
     *          )
     *      )
     * )
     */
    public function getArchivedRoles()
    {
        $roles = Role::where('archived', true)->get();

        foreach ($roles as $role) {
            $role->archived_by =
                Person::find($role->archived_by);
        }
        return new JsonResponse([
            'status' => 'success',
            'data' => [
                'roles' => $roles,
            ],
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/roles/search/term/{term?}",
     *     summary="Buscar roles por término",
     *     operationId="searchRolesByTerm",
     *     tags={"Roles"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="term?",
     *         in="path",
     *         required=false,
     *         description="Término de búsqueda",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Operación exitosa",
     *          @OA\JsonContent(ref="#/components/schemas/Role")
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Error interno del servidor",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Error al archivar el usuario: error interno del servidor"
     *              )
     *          )
     *      )
     * )
     */
    public function searchRolesByTerm($term = '')
    {
        $roles = Role::where('name', 'like', '%' . $term . '%')->where('archived', false)->get();

        $roles->load('permissions');

        return new JsonResponse([
            'status' => 'success',
            'data' => [
                'roles' => $roles,
            ],
        ], 200);
    }

    /**
     * @OA\Get(
     *     path="/api/roles/archived/search/term/{term?}",
     *     summary="Buscar roles archivados por término",
     *     operationId="searchRolesArchivedByTerm",
     *     tags={"Roles"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="term?",
     *         in="path",
     *         required=false,
     *         description="Término de búsqueda",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Operación exitosa",
     *          @OA\JsonContent(ref="#/components/schemas/Role")
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Error interno del servidor",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Error al archivar el usuario: error interno del servidor"
     *              )
     *          )
     *      )
     * )
     */
    public function searchRolesArchivedByTerm($term = '')
    {
        $roles = Role::where('name', 'like', '%' . $term . '%')->where('archived', true)->get();

        foreach ($roles as $role) {
            $role->archived_by =
                Person::find($role->archived_by);
        }
        return new JsonResponse([
            'status' => 'success',
            'data' => [
                'roles' => $roles,
            ],
        ], 200);
    }

    /**
     * @OA\Get(
     *      path="/api/roles/validate/name/{name?}/{id?}",
     *      operationId="checkRolNameIsAvailable",
     *      tags={"Roles"},
     *      security={{"bearer":{}}},
     *      summary="Verificar disponibilidad de nombre de rol",
     *      description="Verifica si un nombre de rol está disponible.",
     *      @OA\Parameter(
     *          name="name?",
     *          description="Nombre del rol a verificar",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="id?",
     *          description="ID del rol a excluir de la verificación (opcional)",
     *          required=false,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Operación exitosa",
     *          @OA\JsonContent(
     *              type="boolean",
     *              example=true,
     *              description="true si el nombre de rol está disponible, false en caso contrario"
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Error interno del servidor",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Error al verificar la disponibilidad del nombre de rol"
     *              )
     *          )
     *      )
     * )
     */
    public function checkRolNameIsAvailable($name, $id = null)
    {
        $query = Role::where('name', $name);

        if ($id) {
            $query->where('id', '!=', $id);
        }

        $role = $query->first();

        return json_encode(empty($role));
    }

    /**
     * @OA\Put(
     *      path="/api/roles/archive/{id}",
     *      operationId="archiveRole",
     *      tags={"Roles"},
     *      security={{"bearer":{}}},
     *      summary="Archivar roles",
     *      description="Archiva roles con el ID especificado.",
     *      @OA\Parameter(
     *          name="id",
     *          description="ID del rol a archivar",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Operación exitosa",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="status",
     *                  type="string",
     *                  example="success"
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Role archivado correctamente"
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Error interno del servidor",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Error al archivar el usuario: error interno del servidor"
     *              )
     *          )
     *      )
     * )
     */
    public function archiveRole($id)
    {
        $role = Role::where('id', $id)->where('name', '!=', 'admin')->first();

        if (!$role) {
            return response()->json(['message' => 'El rol no existe']);
        }

        $role->archived = true;
        $role->archived_at = now();
        $role->archived_by = auth()->user()->id;
        $role->save();

        return new JsonResponse([
            'status' => 'success',
            'message' => 'Rol archivado correctamente',
            'data' => [
                'role' => $role,
            ],
        ], 200);
    }

    /**
     * @OA\Put(
     *      path="/api/roles/restore/{id}",
     *      operationId="restoreRole",
     *      tags={"Roles"},
     *      security={{"bearer":{}}},
     *      summary="Archivar roles",
     *      description="Restaurar roles con el ID especificado.",
     *      @OA\Parameter(
     *          name="id",
     *          description="ID del rol a restaurar",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Operación exitosa",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="status",
     *                  type="string",
     *                  example="success"
     *              ),
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Roles restaurado correctamente"
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Error interno del servidor",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Error al archivar el usuario: error interno del servidor"
     *              )
     *          )
     *      )
     * )
    */
    public function restoreRole($id)
    {
        $role = Role::find($id);
        $role->archived = false;
        $role->save();

        return new JsonResponse([
            'status' => 'success',
            'message' => 'Rol restaurado correctamente',
            'data' => [
                'role' => $role,
            ],
        ], 200);
    }

    /**
     * @OA\Put(
     *      path="/api/roles/update/{id}",
     *      operationId="updateRole",
     *      tags={"Roles"},
     *      security={{"bearer":{}}},
     *      summary="Actualizar rol",
     *      description="Actualiza un rol existente con los datos proporcionados.",
     *      @OA\Parameter(
     *          name="id",
     *          description="ID del rol a actualizar",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos para actualizar el rol",
     *         @OA\JsonContent(ref="#/components/schemas/Role")
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Operación exitosa",
     *          @OA\JsonContent(ref="#/components/schemas/Role")
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description=" no encontrado",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Error al encontrar el rol"
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Error interno del servidor",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Error al archivar el usuario: error interno del servidor"
     *              )
     *          )
     *      )
     * )
     */
    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $role = Role::find($id);

        if (!$role) {
            return response()->json(['message' => 'El rol no existe']);
        }

        $roleExists = Role::where('name', $request->name)->first();
        if ($roleExists && $roleExists->id != $role->id) {
            return response()->json(['message' => 'Ya existe un rol con ese nombre']);
        }

        if (count($request->permissions) == 0) {
            return response()->json(['message' => 'Debe seleccionar al menos un permiso']);
        }

        try {
            $role->name = $request->name;

            $permissions = [];

            foreach ($request->permissions as $permission) {
                array_push($permissions, $permission['name']);
            }
            $role->syncPermissions($permissions);

            $role->save();

            return new JsonResponse([
                'status' => 'success',
                'message' => 'Rol actualizado con exito',
                'data' => [
                    'role' => $role,
                ],
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Error al actualizar el rol',
            ], 500);
        }
    }


    /**
     * @OA\Post(
     *      path="/api/roles/create",
     *      operationId="createRole",
     *      tags={"Roles"},
     *      security={{"bearer":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos para actualizar el rol",
     *         @OA\JsonContent(ref="#/components/schemas/Role")
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Operación exitosa",
     *          @OA\JsonContent(ref="#/components/schemas/Role")
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description=" no encontrado",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Error al encontrar el rol"
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Error interno del servidor",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Error al archivar el usuario: error interno del servidor"
     *              )
     *          )
     *      )
     * )
     **/
    public function createRole(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $role = Role::where('name', $request->name)->first();
        if ($role) {
            return response()->json(['message' => 'Ya existe un rol con ese nombre']);
        }

        if (count($request->permissions) == 0) {
            return response()->json(['message' => 'Debe seleccionar al menos un permiso']);
        }
        try {
            $role = new Role();

            $role->name = $request->name;

            $permissions = [];

            foreach ($request->permissions as $permission) {
                array_push($permissions, $permission['name']);
            }

            $role->syncPermissions($permissions);

            $role->save();
            return new JsonResponse([
                'status' => 'success',
                'message' => 'Rol creado con éxito',
                'data' => [
                    'role' => $role,
                ],
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Error al crear el rol',
            ], 500);
        }
    }

    public function assignRole(Request $request)
    {
        $user = User::find($request->user_id);
        $user->assignRole($request->role_id);

        return response()->json($user);
    }

    /**
     * @OA\Delete(
     *      path="/api/roles/delete/{id}",
     *      operationId="deleteRole",
     *      tags={"Roles"},
     *      security={{"bearer":{}}},
     *      summary="Eliminar un rol permanentemente",
     *      description="Eliminar rol",
     *      @OA\Parameter(
     *          name="id",
     *          description="ID del rol que se va a eliminar",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Operación exitosa",
     *          @OA\JsonContent(ref="#/components/schemas/Role")
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Error interno del servidor",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Error al eliminar un rol: error interno del servidor"
     *              )
     *          )
     *      )
     *  )
     * */
    public function deleteRole($id)
    {
        $role = Role::find($id);
        $role->delete();

        return new JsonResponse([
            'status' => 'success',
            'message' => 'Rol eliminado correctamente',
            'data' => [
                'role' => $role,
            ],
        ], 200);
    }
}
