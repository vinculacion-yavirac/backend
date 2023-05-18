<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Person;
use Symfony\Component\HttpFoundation\JsonResponse;

class RolesController extends Controller
{
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

    /**
     * Función para verificar si un nombre de rol está disponible.
     *
     * @param string $name El nombre del rol a verificar
     * @param int $id El ID del rol (opcional)
     * @return boolean true si el nombre de rol está disponible, false en caso contrario
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
}
