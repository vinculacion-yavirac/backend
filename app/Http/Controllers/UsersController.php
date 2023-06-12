<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Person;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
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


    //public function searchUsuariosByRoleTerm($roleId, $term = '')
    //{
       // $query = User::where('archived', false)
      //      ->whereHas('roles', function ($query) use ($roleId) {
      //          $query->where('id', $roleId);
      //      });

      //  if ($term) {
      //      $query->where(function ($query) use ($term) {
       //         $query->where('email', 'like', '%' . $term . '%')
       //             ->orWhereHas('person', function ($query) use ($term) {
       //                 $query->where('names', 'like', '%' . $term . '%')
       //                     ->orWhere('last_names', 'like', '%' . $term . '%')
       //                     ->orWhere('identification', 'like', '%' . $term . '%')
        //                    ->orWhere('identification_type', 'like', '%' . $term . '%')
        //                    ->orWhereRaw("concat(names, ' ', last_names) like ?", ['%' . $term . '%']);
         //           });
        //    });
      // }

       // $users = $query->get();

      //  $users->load(['person']);

      //  return response()->json([
       //     'status' => 'success',
       //     'data' => [
       //         'users' => $users
       //     ],
      //  ]);
   // }


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

    /**
     * Función para verificar si una identificación está disponible.
     *
     * @param string $identification La identificación a verificar
     * @param int $id El ID del usuario (opcional)
     * @return boolean true si la identificación está disponible, false en caso contrario
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
     * Función para verificar si un email está disponible.
     *
     * @param string $email El email a verificar
     * @param int $id El ID del usuario (opcional)
     * @return boolean true si el email está disponible, false en caso contrario
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
     * Función para verificar si una contraseña es igual a la del usuario correspondiente al ID.
     *
     * @param string $password La contraseña a verificar
     * @param int $id El ID del usuario
     * @return boolean true si la contraseña es igual a la del usuario, false en caso contrario
     */
    public function checkPasswordIsEqual($password, $id)
    {
        $user = User::find($id);

        $isValid = $user && Hash::check($password, $user->password);

        return json_encode($isValid);
    }


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






}
