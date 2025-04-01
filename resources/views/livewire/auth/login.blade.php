<?php

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header :title="__('messages.Log in to your account')" :description="__('messages.Enter your email and password below to log in')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="login" class="flex flex-col gap-6">
        <!-- Email Address -->
        <flux:input
            wire:model="email"
            :label="__('messages.Email address')"
            type="email"
            required
            autofocus
            autocomplete="email"
            placeholder="email@example.com"
        />

        <!-- Password -->
        <div class="relative">
            <flux:input
                wire:model="password"
                :label="__('messages.Password')"
                type="password"
                required
                autocomplete="current-password"
                :placeholder="__('messages.Password')"
            />

            @if (Route::has('password.request'))
                <flux:link class="absolute right-0 top-0 text-sm" :href="route('password.request')" wire:navigate>
                    {{ __('messages.Forgot your password?') }}
                </flux:link>
            @endif
        </div>

        <!-- Remember Me -->
        <flux:checkbox wire:model="remember" :label="__('Remember me')" />

        <div class="flex items-center justify-end">
            <flux:button variant="primary" type="submit" class="w-full">
                {{ __('messages.Log in') }}
            </flux:button>
        </div>
    </form>

    <!-- Divider -->
    <div class="relative text-center text-sm text-zinc-500 dark:text-zinc-400 my-4">
        <span class="bg-white px-2 dark:bg-zinc-900">of</span>
        <div class="absolute top-1/2 left-0 right-0 h-px bg-zinc-300 dark:bg-zinc-700 transform -translate-y-1/2"></div>
    </div>

    <!-- Google Login Button -->
    <div>
        <a href="{{ route('auth.redirection', 'google') }}" class="w-full">
            <flux:button variant="primary" class="w-full flex items-center justify-center gap-2">
                <!-- Google icon -->
                <svg class="w-5 h-5" viewBox="0 0 488 512" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path d="..."></path>
                </svg>
                {{ __('messages.Log in with Google') }}
            </flux:button>

        </a>
    </div>

    @if (Route::has('register'))
        <div class="space-x-1 text-center text-sm text-zinc-600 dark:text-zinc-400">
            {{ __('messages.Don\'t have an account?') }}
            <flux:link :href="route('register')" wire:navigate>{{ __('messages.Sign up') }}</flux:link>
        </div>
    @endif
</div>

