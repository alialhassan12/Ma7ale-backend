<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\AbstractProvider;

class OAuthController extends Controller
{
    public function redirectToGoogle()
    {
        /** @var AbstractProvider $driver */
        $driver = Socialite::driver('google');
        $googleRedirect = $driver->stateless()->redirect()->getTargetUrl();

        return response()->json([
            'url' => $googleRedirect,
        ]);
    }

    public function callbackToGoogle()
    {
        try {
            /** @var AbstractProvider $driver */
            $driver = Socialite::driver('google');
            $googleUser = $driver->stateless()->user();

            $user=User::where('provider_id',$googleUser->getId())
                    ->orWhere('email',$googleUser->getEmail())
                    ->first();

            if ($user) {
                $user->update([
                    'name' => $user->name ?? $googleUser->getName(),
                    'provider' => 'google',
                    'provider_id' => $googleUser->getId(),
                    'avatar' => $user->avatar ?? $googleUser->getAvatar(),
                ]);
            } else {
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'provider' => 'google',
                    'provider_id' => $googleUser->getId()
                ]);
            }
            
            $token = $user->createToken('auth-token')->plainTextToken;
            
            return redirect(
                rtrim(config('app.frontend_url'), '/') . '/oauth/callback?token=' . urlencode($token)
            );
        } catch (Exception $exception) {
            Log::error('Google OAuth error: ' . $exception->getMessage());

            return redirect(
                rtrim(config('app.frontend_url'), '/') . '/login?oauth_error=google_authentication_failed'
            );
        }
    }
}
