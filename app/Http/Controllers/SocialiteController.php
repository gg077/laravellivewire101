<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function authProviderRedirect(string $provider)
    {
        abort_unless(in_array($provider, ['google']), 404); // beveiligd: alleen toegestane providers

        return Socialite::driver($provider)->redirect();
    }

    public function socialAuthentication(string $provider)
    {
        try {
            abort_unless(in_array($provider, ['google']), 404); // zelfde check

            $socialUser = Socialite::driver($provider)->stateless()->user();

            $user = User::firstOrCreate(
                ['email' => $socialUser->email],
                [
                    'name' => $socialUser->name,
                    'password' => Hash::make(Str::random(24)), // veiliger dan 'Password@1234'
                    'auth_provider_id' => $socialUser->id,
                    'auth_provider' => $provider,
                ]
            );

            Auth::login($user);

            return redirect()->route('dashboard');
        } catch (Exception $e) {
            report($e);
            return redirect('/login')->withErrors(['login' => 'Authenticatie via ' . ucfirst($provider) . ' is mislukt.']);
        }
    }
}
