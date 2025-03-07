<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginUserRequest;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    use ApiResponses;
    public function login(LoginUserRequest $request)
    {
        $request->validated($request->all());

        if(!Auth::attempt($request->only('email','password'))){
            return $this->error("Credenciales invalidas", 401);
        }

        $user = User::firstWhere('email',$request->email);

        return $this->ok(
            'Autenticado',
            [
                'token' => $user->createToken(
                    'API token for'. $user->email,
                    ['*'],
                    //now()->addMonth() TODO: Visto bueno 
                    // Creo que no queremos que se les cierre la sesion en la app
                    // En la configuracion de sanctum se puede colocar una expiracion default
                    )->plainTextToken,
                'id_user' => $user->id
            ]
            );
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->ok('');

    }
    // public function register()
    // {
    //     return $this->ok('register');
    // }
}
