<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\CredencialesMailable;
use stdClass;
use App\Models\User;
use App\Models\Person;
use Symfony\Component\HttpFoundation\JsonResponse;

class EmailsController extends Controller
{
    /**
     * Send user credentials via email.
     *
     * @OA\Post(
     *     path="/api/send-email",
     *     summary="Enviar credenciales por correo electrónico",
     *     tags={"Email"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", example="user@gmail.com"),
     *             @OA\Property(property="password", type="string", example="yavirac1810")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="email"),
     *             @OA\Property(property="message", type="string", example="Credenciales enviadas correctamente al correo electrónico")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="status", type="string", example="error"),
     *             @OA\Property(property="message", type="string", example="Error al enviar las credenciales al correo electrónico")
     *         )
     *     )
     * )
     */
    public function sendEmail(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        $user->person = Person::find($user->person);

        $data = new stdClass();
        $data->email = $user->email;
        $data->password = $request->password;
        $data->name = ucfirst(explode(' ', $user->person->names)[0]) . ' ' . strtoupper(explode(' ', $user->person->last_names)[0][0]) . '.';

        try {
            Mail::to($user->email)->queue(new CredencialesMailable($data));

            return new JsonResponse([
                'status' => 'email',
                'message' => 'Credenciales enviadas correctamente al correo electrónico'
            ], 200);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status' => 'error',
                'message' => 'Error al enviar las credenciales al correo electrónico'
            ], 500);
        }
    }
}
