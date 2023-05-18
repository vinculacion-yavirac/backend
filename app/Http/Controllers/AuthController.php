<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    public function login(LoginRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->where('archived', false)->firstOrFail();
            if (!$user->active) {
                throw new \Exception('Este usuario esta deshabilitado');
            }
            if (!Hash::check($request->password, $user->password)) {
                throw new \Exception('Credenciales inválidas');
            }

            $user->load(['person', 'roles']);
            $user->role = $user->roles()->first();

            $accessToken = Auth::guard('access')->claims(['exp' => time() + 900])->login($user);
            $refreshToken = Auth::guard('refresh')->claims(['exp' => time() + 86400])->login($user);

            return new JsonResponse([
                'status' => 'success',
                'data' => [
                    'accessToken' => $accessToken,
                    'refreshToken' => $refreshToken
                ],
                'message' => 'Inicio de sesión exitoso'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Credenciales inválidas',
            ], 404);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'errors' => []
            ], 401);
        }
    }

    public function refresh(Request $request)
    {
        try {
            $refreshToken = $request->header('refresh_token');

            if (!$refreshToken) {
                throw new \Exception('No se ha proporcionado un token de refresco');
            }

            if (!Auth::guard('refresh')->setToken($refreshToken)->check()) {
                throw new \Exception('El token de refresco no es válido');
            }

            $user = Auth::guard('refresh')->setToken($refreshToken)->user();

            $user->load(['person', 'roles']);
            $user->role = $user->roles()->first();

            $accessToken = Auth::guard('access')->claims(['exp' => time() + 900])->login($user);

            return new JsonResponse([
                'status' => 'success',
                'data' => [
                    'accessToken' => $accessToken
                ]
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'errors' => []
            ], 401);
        }
    }

    public function getProfile()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }

        try {

            $user->load(['person'])->only(['id', 'email', 'active', 'person.id', 'person.name', 'person.last_name', 'person.email', 'person.phone', 'person.birth_date', 'person.identification', 'person.identification_type']);

            $user->role = $user->roles->first();
            $user->role = $user->role->only(['id', 'name']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
        return new JsonResponse([
            'status' => 'success',
            'data' => [
                'user' => $user
            ],
        ], 200);
    }

    public function logout(Request $request)
    {
        try {
            $token = $request->header('Authorization');
            JWTAuth::invalidate($token);
            return new JsonResponse([
                'status' => 'success',
                'message' => 'Sesión cerrada correctamente'

            ], 200);
        } catch (JWTException $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'No se pudo cerrar la sesión, intente nuevamente'

            ], 500);
        }
    }
}
