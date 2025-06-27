<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): JsonResponse
    {
        // 1) Validar datos
        $request->validate([
            'name'                  => ['required', 'string', 'max:255'],
            'email'                 => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'              => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 2) Crear usuario
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 3) Evento (opcional, si usas email verification)
        event(new Registered($user));

        // 4) Generar token de API
        $token = $user->createToken('api_token')->plainTextToken;

        // 5) Devolver JSON con token y código 201
        return response()->json([
            'token' => $token,
        ], 201);
    }
}
