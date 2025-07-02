<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirect(string $driver)
    {
        return Socialite::driver($driver)->redirect();
    }

    /** Paso 2 · callback */
    public function callback(string $driver)
    {
        $social = Socialite::driver($driver)->stateless()->user();

        // Busca por email o lo crea
        $user = User::firstOrCreate(
            ['email' => $social->getEmail()],
            [
                'name'        => $social->getName() ?: $social->getNickname(),
                'password'    => Hash::make(Str::random(40)),   // contraseña dummy
                'provider'    => $driver,
                'provider_id' => $social->getId(),
            ]
        );

        // Si el usuario existía pero no tenía provider guardado, lo añadimos
        if (!$user->provider_id) {
            $user->update([
                'provider'    => $driver,
                'provider_id' => $social->getId(),
            ]);
        }

        Auth::login($user, remember: true);
        return redirect()->intended('/dashboard');  // o RouteServiceProvider::HOME
    }
}
