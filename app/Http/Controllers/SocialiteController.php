<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function authProviderRedirect(string $provider)
    {
        abort_unless(in_array($provider, ['google', 'github']), 404); // beveiligd: alleen toegestane providers

        return Socialite::driver($provider)->redirect();
    }

    public function socialAuthentication(string $provider)
    {
        try {
            abort_unless(in_array($provider, ['google', 'github']), 404); // zelfde check

            $socialUser = Socialite::driver($provider)
                ->setHttpClient(new Client([
                    'verify' => false // SSL verification disabled
                ]))
                ->stateless()
                ->user();

            $user = User::firstOrCreate(
                ['email' => $socialUser->email],
                [
                    'name' => $socialUser->name,
                    'password' => Hash::make(Str::random(24)), // veiliger dan 'Password@1234'
                    'auth_provider_id' => $socialUser->id,
                    'auth_provider' => $provider,
                    'email_verified_at' => now(),
                ]
            );

            // Check if the user was just created or not
            if (!$user->wasRecentlyCreated) {
                // If user exists but hasn't verified email, redirect to login page with a warning
                if (!$user->hasVerifiedEmail()) {
                    return redirect()->route('login')->with('warning', 'Please verify your email address first');
                }

                // Update social provider details for existing user
                $user->update([
                    'auth_provider_id' => $socialUser->id,
                    'auth_provider' => $provider,
                ]);
            } else {
                $user->assignRole('viewer');
            }

            Auth::login($user);

            return redirect()->route('dashboard');
        } catch (Exception $e) {
            report($e);
            return redirect('/login')->withErrors(['login' => 'Authenticatie via ' . ucfirst($provider) . ' is mislukt.']);
        }
    }
}
