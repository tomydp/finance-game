<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => ['required','string','max:255'],
            'email'    => ['required','string','email','max:255','unique:users,email'],
            'password' => ['required','string','min:8','same:password_confirmation'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 422);
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->sendEmailVerificationNotification();

        return response()->json([
            'message' => 'Registro ok. Verificá tu email.'
        ], 201);
    }

    public function login(Request $request)
    {
        $cred = $request->only('email', 'password');

        if (!Auth::attempt($cred)) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $token = $user->createToken('App')->plainTextToken;

        return response()->json([
            'name'  => $user->name,
            'email' => $user->email,
            'verified' => $user->hasVerifiedEmail(),
            'token' => $token,
        ]);
    }

    public function profile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name'     => ['required','string','max:255'],
            'email'    => ['required','email','max:255','unique:users,email,'.$user->id],
            'password' => ['nullable','string','min:8','same:password_confirmation'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 422);
        }

        $user->name  = $request->name;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return response()->json([
            'name'  => $user->name,
            'email' => $user->email,
        ]);
    }

    public function logout(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        if ($user && $user->currentAccessToken()) {
            $user->currentAccessToken()->delete();
        }
        return response()->json(['message' => 'Sesión cerrada']);
    }
}
