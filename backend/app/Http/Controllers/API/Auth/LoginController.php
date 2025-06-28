<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function store(Request $request)
    {
        $credentials = $request->validate([
          'email'    => 'required|email',
          'password' => 'required|string',
        ]);

        if (! Auth::attempt($credentials)) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }

        $user  = $request->user();
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json([
          'user'  => $user,
          'token' => $token,
        ], 200);
    }
}
