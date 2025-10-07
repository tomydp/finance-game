<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class VerifyEmailController extends Controller
{
    public function verify(Request $request)
    {
        $user = User::findOrFail($request->route('id'));

        // valida el hash
        if (! hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            return redirect()->away(config('app.frontend_url').'/login?verified=0');
        }

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new Verified($user));
        }

        // SIEMPRE vuelve al SPA
        return redirect()->away(config('app.frontend_url').'/login?verified=1');
    }

    public function resend(Request $request)
    {
        $user = $request->user() ?: Auth::guard('sanctum')->user();

        if (! $user) {
            $validator = Validator::make($request->all(), [
                'email' => ['required', 'string', 'email'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'No pudimos procesar tu solicitud.',
                    'errors'  => $validator->errors()->all(),
                ], 422);
            }

            $user = User::where('email', $validator->validated()['email'])->first();

            if (! $user) {
                return response()->json([
                    'message' => 'Si la cuenta existe, vas a recibir un correo en breve.',
                ], 200);
            }
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Tu email ya está verificado.'], 200);
        }

        $cacheKey = 'email_verification_resend_'.($user->id ?? sha1($user->email));
        if (Cache::has($cacheKey)) {
            return response()->json([
                'message' => 'Esperá 30 segundos antes de solicitar otro correo.',
            ], 429);
        }

        Cache::put($cacheKey, true, now()->addSeconds(30));
        $user->sendEmailVerificationNotification();

        return response()->json(['message' => 'Te reenviamos el correo de verificación.'], 200);
    }
}
