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
