<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Person;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Schema(
 *     schema="User",
 *     title="User",
 *     description="User model",
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="email", type="string", example="user@example.com"),
 *     @OA\Property(property="password", type="string"),
 *     @OA\Property(property="person", type="fk_person", description="ID de la persona asociada"),
 *     @OA\Property(property="active", type="boolean", default=true, example=true),
 *     @OA\Property(property="archived", type="boolean", default=false, example=false),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="archived_by", type="fk_users", description="ID del usuario que lo archivó"),
 *     @OA\Property(property="updated_at", type="string", format="date-time"),
 * )
 */
class UsersController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/users",
     *     summary="Obtener usuarios",
     *     operationId="getUsers",
     *     tags={"Users"},
     *     security={{"bearer":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de usuarios",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="users", type="array", @OA\Items(ref="#/components/schemas/User"))
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
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
    public function getUsers()
    {
        $users = User::where('id', '!=', auth()->user()->id)
            ->where('id', '!=', 1)
            ->where('archived', false)
            ->get();

        $users->load(['person', 'roles']);
        foreach ($users as $user) {
            $user->role = $user->roles->first();
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'users' => $users
            ],
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     summary="Obtener información de un usuario por su ID",
     *     operationId="getUserById",
     *     tags={"Users"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del usuario a obtener",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             example=123
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Respuesta exitosa al obtener información del usuario",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="user", type="object",
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="email", type="string"),
     *                     @OA\Property(property="active", type="boolean"),
     *                     @OA\Property(property="person", type="object",
     *                         @OA\Property(property="id", type="integer"),
     *                         @OA\Property(property="name", type="string"),
     *                         @OA\Property(property="last_name", type="string"),
     *                         @OA\Property(property="email", type="string"),
     *                         @OA\Property(property="phone", type="string"),
     *                         @OA\Property(property="birth_date", type="string", format="date"),
     *                         @OA\Property(property="identification", type="string"),
     *                         @OA\Property(property="identification_type", type="string")
     *                     ),
     *                     @OA\Property(property="role", type="object",
     *                         @OA\Property(property="id", type="integer"),
     *                         @OA\Property(property="name", type="string")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Error de autenticación",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Usuario no encontrado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
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
    public function getUserById($id)
    {
        $user = User::where('id', $id)
            ->where('id', '!=', auth()->user()->id)
            ->where('id', '!=', 1)
            ->where('archived', false)
            ->first();

        if (!$user) {
            return response()->json([
                'message' => 'Usuario no encontrado'
            ]);
        }

        $user->load(['person', 'roles']);
        $user->role = $user->roles()->first();

        return response()->json([
            'status' => 'success',
            'data' => [
                'user' => $user
            ],
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/users/search/term/{term?}",
     *     summary="Buscar usuarios por término",
     *     operationId="searchUsuariosByTerm",
     *     tags={"Users"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="term",
     *         in="path",
     *         required=false,
     *         description="Término de búsqueda",
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Respuesta exitosa al buscar usuarios",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="users", type="array",
     *                     @OA\Items(
     *                         @OA\Property(property="id", type="integer"),
     *                         @OA\Property(property="email", type="string"),
     *                         @OA\Property(property="active", type="boolean"),
     *                         @OA\Property(property="person", type="object",
     *                             @OA\Property(property="id", type="integer"),
     *                             @OA\Property(property="names", type="string"),
     *                             @OA\Property(property="last_names", type="string"),
     *                             @OA\Property(property="email", type="string"),
     *                             @OA\Property(property="phone", type="string"),
     *                             @OA\Property(property="birth_date", type="string", format="date"),
     *                             @OA\Property(property="identification", type="string"),
     *                             @OA\Property(property="identification_type", type="string")
     *                         ),
     *                         @OA\Property(property="role", type="object",
     *                             @OA\Property(property="id", type="integer"),
     *                             @OA\Property(property="name", type="string")
     *                         )
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Error de autenticación",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
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
    public function searchUsuariosByTerm($term = '')
    {
        $users = User::where('id', '!=', auth()->user()->id)
            ->where('id', '!=', 1)
            ->where('archived', false)
            ->where(function ($query) use ($term) {
                $query->where('email', 'like', '%' . $term . '%')
                    ->orWhereHas('roles', function ($query) use ($term) {
                        $query->where('name', 'like', '%' . $term . '%');
                    })
                    ->orWhereHas('person', function ($query) use ($term) {
                        $query->where('names', 'like', '%' . $term . '%')
                            ->orWhere('last_names', 'like', '%' . $term . '%')
                            ->orWhere('identification', 'like', '%' . $term . '%')
                            ->orWhere('identification_type', 'like', '%' . $term . '%')
                            ->orWhereRaw("concat(names, ' ', last_names) like ?", ['%' . $term . '%']);
                    });
            })
            ->get();

        $users->load(['person', 'roles']);
        foreach ($users as $user) {
            $user->role = $user->roles->first();
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'users' => $users
            ],
        ]);
    }


    /**
     * @OA\Get(
     *      path="/api/users/validate/identification/{identification}/{id?}",
     *      operationId="checkIdentificationIsAvailable",
     *      tags={"Users"},
     *      security={{"bearer":{}}},
     *      summary="Validación identificación",
     *      description="Verificar si la identificación está en uso por otros usuarios.",
     *      @OA\Parameter(
     *          name="identification",
     *          description="Identificación del usuario a verificar",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="id?",
     *          description="ID del usuario actual (opcional)",
     *          required=false,
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
     *                  property="data",
     *                  type="object",
     *                  @OA\Property(
     *                      property="is_available",
     *                      type="boolean",
     *                      example=true,
     *                      description="Indica si la identificación está disponible"
     *                  )
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
     *                  example="Error con la identificación: error interno del servidor"
     *              )
     *          )
     *      )
     * )
     */
    public function checkIdentificationIsAvailable($identification, $id = null)
    {
        $query = User::whereHas('person', function ($query) use ($identification) {
            $query->where('identification', $identification);
        });

        if ($id) {
            $query->where('id', '!=', $id);
        }

        $user = $query->first();

        return json_encode(empty($user));
    }


    /**
     * @OA\Get(
     *      path="/api/users/validate/email/{email}/{id?}",
     *      operationId="checkEmailIsAvailable",
     *      tags={"Users"},
     *      security={{"bearer":{}}},
     *      summary="Verificar disponibilidad de email",
     *      description="Verifica si un email está disponible para su uso.",
     *      @OA\Parameter(
     *          name="email",
     *          description="Email del usuario a verificar",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string",
     *              format="email"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="id?",
     *          description="ID del usuario actual (opcional)",
     *          required=false,
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
     *                  property="data",
     *                  type="object",
     *                  @OA\Property(
     *                      property="is_available",
     *                      type="boolean",
     *                      example=true,
     *                      description="Indica si el email está disponible"
     *                  )
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
     *                  example="Error al verificar el email: error interno del servidor"
     *              )
     *          )
     *      )
     * )
     */
    public function checkEmailIsAvailable($email, $id = null)
    {
        $query = User::where('email', $email);

        if ($id) {
            $query->where('id', '!=', $id);
        }

        $user = $query->first();

        return json_encode(empty($user));
    }


    /**
     * @OA\Get(
     *      path="/api/users/validate/password/{password}/{id}",
     *      operationId="checkPasswordIsEqual",
     *      tags={"Users"},
     *      security={{"bearer":{}}},
     *      summary="Verificar igualdad de contraseña",
     *      description="Verifica si una contraseña es igual a la del usuario correspondiente al ID.",
     *      @OA\Parameter(
     *          name="password",
     *          description="Contraseña a verificar",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="id",
     *          description="ID del usuario",
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
     *                  property="data",
     *                  type="object",
     *                  @OA\Property(
     *                      property="is_equal",
     *                      type="boolean",
     *                      example=true,
     *                      description="Indica si la contraseña es igual a la del usuario"
     *                  )
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
     *                  example="Error al verificar la contraseña: error interno del servidor"
     *              )
     *          )
     *      )
     * )
     */
    public function checkPasswordIsEqual($password, $id)
    {
        $user = User::find($id);

        $isValid = $user && Hash::check($password, $user->password);

        return json_encode($isValid);
    }


    /**
     * @OA\Get(
     *      path="/api/users/search/role/{roleId}/term/{term?}",
     *      operationId="searchUsuariosByRoleTerm",
     *      tags={"Users"},
     *      security={{"bearer":{}}},
     *      summary="Buscar usuarios por el id del rol y término del usuario",
     *      description="Busca usuarios por el ID de rol y un término de búsqueda opcional.",
     *      @OA\Parameter(
     *          name="roleId",
     *          description="ID del rol para buscar usuarios",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Parameter(
     *          name="term?",
     *          description="Término de búsqueda opcional",
     *          required=false,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
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
     *                  property="data",
     *                  type="object",
     *                  @OA\Property(
     *                      property="users",
     *                      type="array",
     *                      @OA\Items(ref="#/components/schemas/User")
     *                  )
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
     *                  example="Error al buscar usuarios por rol y término: error interno del servidor"
     *              )
     *          )
     *      )
     * )
     */
    public function searchUsuariosByRoleTerm($roleId, $term = '')
    {
        $query = User::where('archived', false)
            ->whereHas('roles', function ($query) use ($roleId) {
                $query->where('id', $roleId);
            });

        $role = Role::find($roleId);

        if ($term) {
            $query->where(function ($query) use ($term) {
                $query->where('email', 'like', '%' . $term . '%')
                    ->orWhereHas('person', function ($query) use ($term) {
                        $query->where('names', 'like', '%' . $term . '%')
                            ->orWhere('last_names', 'like', '%' . $term . '%')
                            ->orWhere('identification', 'like', '%' . $term . '%')
                            ->orWhere('identification_type', 'like', '%' . $term . '%')
                            ->orWhereRaw("concat(names, ' ', last_names) like ?", ['%' . $term . '%']);
                    });
            });
        }

        $users = $query->get();

        $users->load(['person']);

        return response()->json([
            'status' => 'success',
            'data' => [
                'role' => $role,
                'users' => $users
            ],
        ]);
    }

    /**
     * @OA\Get(
     *      path="/api/users/archived/search/term/{term?}",
     *      operationId="searchUsuariosArchivedByTerm",
     *      tags={"Users"},
     *      security={{"bearer":{}}},
     *      summary="Buscar usuarios archivados por término",
     *      description="Busca usuarios archivados por un término de búsqueda.",
     *      @OA\Parameter(
     *          name="term?",
     *          description="Término de búsqueda opcional",
     *          required=false,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
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
     *                  property="data",
     *                  type="object",
     *                  @OA\Property(
     *                      property="users",
     *                      type="array",
     *                      @OA\Items(ref="#/components/schemas/User")
     *                  )
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
     *                  example="Error al buscar usuarios archivados por término: error interno del servidor"
     *              )
     *          )
     *      )
     * )
     */
    public function searchUsuariosArchivedByTerm($term = '')
    {
        $users = User::where('id', '!=', auth()->user()->id)
            ->where('archived', true)
            ->where(function ($query) use ($term) {
                $query->where('email', 'like', '%' . $term . '%')
                    ->orWhere('archived_at', 'like', '%' . $term . '%')
                    ->orWhereHas('person', function ($query) use ($term) {
                        $query->where('identification', 'like', '%' . $term . '%')
                            ->orWhere('names', 'like', '%' . $term . '%')
                            ->orWhere('last_names', 'like', '%' . $term . '%')
                            ->orWhereRaw("concat(names, ' ', last_names) like ?", ['%' . $term . '%']);
                    });
            })->get();
        foreach ($users as $user) {
            $user->archived_by = Person::find($user->archived_by);
        }
        return response()->json([
            'status' => 'success',
            'data' => [
                'users' => $users
            ]
        ]);
    }

    /**
     * @OA\Get(
     *      path="/api/users/archived/list",
     *      operationId="getArchivedUsers",
     *      tags={"Users"},
     *      security={{"bearer":{}}},
     *      summary="Obtener usuarios archivados",
     *      description="Obtiene una lista de usuarios archivados.",
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
     *                  property="data",
     *                  type="object",
     *                  @OA\Property(
     *                      property="users",
     *                      type="array",
     *                      @OA\Items(ref="#/components/schemas/User")
     *                  )
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
     *                  example="Error al obtener los usuarios archivados: error interno del servidor"
     *              )
     *          )
     *      )
     * )
     */
    public function getArchivedUsers()
    {
        $users = User::where('id', '!=', auth()->user()->id)
            ->where('archived', true)
            ->get();

        $users->load('archived_by');

        return response()->json([
            'status' => 'success',
            'data' => [
                'users' => $users
            ]
        ]);
    }


    /**
     * @OA\Put(
     *     path="/api/users/update/{id}",
     *     summary="Actualizar un usuario existente",
     *     operationId="updateUser",
     *     tags={"Users"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del usuario a actualizar",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos para actualizar el usuario",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="person", type="object",
     *                 @OA\Property(property="names", type="string"),
     *                 @OA\Property(property="last_names", type="string"),
     *                 @OA\Property(property="email", type="string"),
     *                 @OA\Property(property="phone", type="string"),
     *                 @OA\Property(property="birth_date", type="string", format="date"),
     *                 @OA\Property(property="identification", type="string"),
     *                 @OA\Property(property="identification_type", type="string")
     *             ),
     *             @OA\Property(property="role", type="string"),
     *             @OA\Property(property="active", type="boolean"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Respuesta exitosa al actualizar el usuario",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Usuario actualizado correctamente"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="user", type="object",
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="email", type="string"),
     *                     @OA\Property(property="person", type="object",
     *                         @OA\Property(property="id", type="integer"),
     *                         @OA\Property(property="names", type="string"),
     *                         @OA\Property(property="last_names", type="string"),
     *                         @OA\Property(property="email", type="string"),
     *                         @OA\Property(property="phone", type="string"),
     *                         @OA\Property(property="birth_date", type="string", format="date"),
     *                         @OA\Property(property="identification", type="string"),
     *                         @OA\Property(property="identification_type", type="string")
     *                     ),
     *                     @OA\Property(property="role", type="object",
     *                         @OA\Property(property="id", type="integer"),
     *                         @OA\Property(property="name", type="string")
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación de datos",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Error de autenticación",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validación de datos",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Error al actualizar el usuario")
     *         )
     *     )
     * )
     */
    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'email' => 'required|email',
            'person' => 'required',
            'role' => 'required'
        ]);

        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'message' => 'No existe el usuario'
            ], 422);
        }

        $user2 = User::where('email', $request->email)->first();
        if ($user2 && $user2->id != $user->id) {
            return response()->json([
                'message' => 'Ya existe un usuario con ese correo'
            ], 422);
        }

        if ($request->role == 0) {
            return response()->json([
                'message' => 'Debe seleccionar un rol'
            ], 422);
        }

        try {
            DB::transaction(
                function () use ($request, $user) {
                    $person = Person::find($user->person);
                    $person->fill($request->person);

                    $user->email = $request->email;
                    $user->active = $request->active;

                    $person->touch();
                    $user->touch();

                    $person->save();
                    $user->save();

                    $user->syncRoles($request->role);
                }
            );

            $user->load(['person']);
            $user->role = $user->roles()->first();

            return response()->json([
                'status' => 'success',
                'message' => 'Usuario actualizado correctamente',
                'data' => [
                    'user' => $user
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al actualizar el usuario: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * @OA\Put(
     *      path="/api/users/update-password/{id}",
     *      operationId="updatePassword",
     *      tags={"Users"},
     *      security={{"bearer":{}}},
     *      summary="Actualizar contraseña del usuario",
     *      description="Actualiza la contraseña del usuario.",
     *      @OA\Parameter(
     *          name="id",
     *          description="ID del usuario",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *         required=true,
     *         description="Nueva contraseña",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="password", type="string"),
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Contraseña actualizada correctamente",
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
     *                  example="Contraseña actualizada correctamente"
     *              ),
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Usuario no encontrado",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="No existe el usuario"
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validación fallida",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Error al actualizar contraseña: datos inválidos"
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
     *                  example="Error al actualizar contraseña: error interno del servidor"
     *              )
     *          )
     *      )
     * )
     */
    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required'
        ]);

        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'message' => 'No existe el usuario'
            ]);
        }

        try {
            $user->password = bcrypt($request->password);
            $user->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Contraseña actualizada correctamente',
                'data' => [
                    'user' => $user
                ]
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al actualizar contraseña',
                'error' => $th
            ]);
        }
    }

    /**
     * @OA\Put(
     *      path="/api/users/archive/{id}",
     *      operationId="archiveUser",
     *      tags={"Users"},
     *      security={{"bearer":{}}},
     *      summary="Archivar usuario",
     *      description="Archiva al usuario con el ID especificado.",
     *      @OA\Parameter(
     *          name="id",
     *          description="ID del usuario a archivar",
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
     *                  example="Usuario archivado correctamente"
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Usuario no encontrado",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Usuario no encontrado"
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
    public function archiveUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'Usuario no encontrado'
            ]);
        }
        $user->archived = true;
        $user->archived_at = now();
        $user->archived_by = auth()->user()->id;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Usuario archivado correctamente',
        ]);
    }

    /**
     * @OA\Put(
     *      path="/api/users/restore/{id}",
     *      operationId="restoreUser",
     *      tags={"Users"},
     *      security={{"bearer":{}}},
     *      summary="Restaurar usuario",
     *      description="Restaura al usuario con el ID especificado.",
     *      @OA\Parameter(
     *          name="id",
     *          description="ID del usuario a restaurar",
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
     *                  example="Usuario restaurado correctamente"
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Usuario no encontrado",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Usuario no encontrado"
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
     *                  example="Error al restaurar el usuario: error interno del servidor"
     *              )
     *          )
     *      )
     * )
     */
    public function restoreUser($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'message' => 'Usuario no encontrado'
            ]);
        }

        $user->archived = false;
        $user->archived_at = null;
        $user->archived_by = null;
        $user->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Usuario restaurado correctamente',
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/users/create",
     *     summary="Crear un nuevo usuario",
     *     operationId="createUser",
     *     tags={"Users"},
     *     security={{"bearer":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Datos del nuevo usuario",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="password", type="string"),
     *             @OA\Property(property="active", type="boolean", default=true),
     *             @OA\Property(property="person", type="object",
     *                 @OA\Property(property="names", type="string"),
     *                 @OA\Property(property="last_names", type="string"),
     *                 @OA\Property(property="email", type="string"),
     *                 @OA\Property(property="phone", type="string"),
     *                 @OA\Property(property="birth_date", type="string", format="date"),
     *                 @OA\Property(property="identification", type="string"),
     *                 @OA\Property(property="identification_type", type="string")
     *             ),
     *             @OA\Property(property="role", type="string"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Respuesta exitosa al crear un nuevo usuario",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Usuario creado con éxito")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Error de validación de datos",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Error de conflicto",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Ya existe un usuario con ese correo")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Error al crear el usuario")
     *         )
     *     )
     * )
     */
    public function createUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'person' => 'required',
            'role' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();
        if ($user) {
            return response()->json([
                'message' => 'Ya existe un usuario con ese correo'
            ]);
        }

        try {
            DB::transaction(
                function () use ($request) {
                    $person = Person::create($request->person);
                    $user = User::create([
                        'email' => $request->email,
                        'password' => bcrypt($request->password),
                        'active' => $request->active,
                        'person' => $person->id
                    ]);
                    $user->assignRole($request->role);
                }
            );
            return response()->json([
                'status' => 'success',
                'message' => 'Usuario creado con éxito'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear el usuario: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/users/delete/{id}",
     *     summary="Eliminar un usuario",
     *     operationId="deleteUser",
     *     tags={"Users"},
     *     security={{"bearer":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID del usuario a eliminar",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Respuesta exitosa al eliminar el usuario",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Usuario eliminado correctamente")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuario no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Usuario no encontrado")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Error de autenticación",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error interno del servidor",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Error al eliminar el usuario")
     *         )
     *     )
     * )
     */
    public function deleteUser($id)
    {
        try {
            DB::transaction(
                function () use ($id) {

                    $user = User::find($id);
                    if (!$user) {
                        return response()->json([
                            'message' => 'Usuario no encontrado'
                        ]);
                    }
                    $user->delete();
                    Person::find($user->person)->delete();
                }
            );
            return response()->json([
                'status' => 'success',
                'message' => 'Usuario eliminado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al eliminar el usuario: ' . $e->getMessage()
            ], 500);
        }
    }

}