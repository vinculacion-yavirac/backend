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

/**
 * @OA\Schema(
 *     schema="Authorization",
 *     title="Auth",
 *     description="Auth model",
 *     @OA\Property(property="email", type="string"),
 *     @OA\Property(property="password", type="string"),
 * )
 */
class AuthController extends Controller
{


            /**
     * @OA\POST(
     *     path="/api/auth/login",
     *     tags={"Auth"},
     *     summary="Login",
     *     description="Login to system.",
     *     operationId="login",
     *     @OA\RequestBody(
     *         description="Pet object that needs to be added to the store",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *            @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="email",
     *                     description="User Email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     description="User password",
     *                     type="string"
     *                 ),
     *                 required={"email", "password"}
     *             )
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Inicio de sesión exitoso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="accessToken", type="string", example="your_access_token"),
     *                 @OA\Property(property="refreshToken", type="string", example="your_refresh_token")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Error de credenciales inválidas",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Credenciales inválidas")
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

        /**
     * @OA\Post(
     *     path="/api/auth/refresh",
     *     summary="Obtener un nuevo token de acceso mediante un token de refresco",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Token de refresco actual",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="refresh_token", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Respuesta exitosa al obtener un nuevo token de acceso",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="accessToken", type="string", example="your_new_access_token"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Error de token de refresco inválido",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Token de refresco inválido")
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


        /**
     * @OA\Get(
     *     path="/api/auth/profile",
     *     summary="Obtener el perfil del usuario autenticado",
     *     tags={"Auth"},
     *     security={{"bearer":{}}},
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
     *         description="Token no encontrado",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Token no encontrado")
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

        /**
     * @OA\Delete(
     *     path="/api/auth/logout",
     *     summary="Cerrar sesión del usuario autenticado",
     *     tags={"Auth"},
     *     security={{"bearer":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Sesión cerrada correctamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Sesión cerrada correctamente")
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
