<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);

        $this->redirectIntended(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header :title="__('messages.Create an account')" :description="__('messages.Enter your details below to create your account')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="register" class="flex flex-col gap-6">
        <!-- Name -->
        <flux:input
            wire:model="name"
            :label="__('messages.Name')"
            type="text"
            required
            autofocus
            autocomplete="name"
            :placeholder="__('messages.Full name')"
        />

        <!-- Email Address -->
        <flux:input
            wire:model="email"
            :label="__('messages.Email address')"
            type="email"
            required
            autocomplete="email"
            placeholder="email@example.com"
        />

        <!-- Password -->
        <flux:input
            wire:model="password"
            :label="__('messages.Password')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('messages.Password')"
        />

        <!-- Confirm Password -->
        <flux:input
            wire:model="password_confirmation"
            :label="__('messages.Confirm password')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('messages.Confirm password')"
        />

        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary" class="w-full">
                {{ __('messages.Create account') }}
            </flux:button>
        </div>
    </form>

    <div class="space-x-1 text-center text-sm text-zinc-600 dark:text-zinc-400">
        {{ __('messages.Already have an account?') }}
        <flux:link :href="route('login')" wire:navigate>{{ __('messages.Log in') }}</flux:link>
    </div>

    <!-- Language Switcher -->
    <flux:dropdown class="mx-auto mt-5">
        <flux:button icon:trailing="chevron-down" class="w-24 justify-between">
            {{ strtoupper(LaravelLocalization::getCurrentLocale()) }}
        </flux:button>
        <flux:menu>
            <flux:menu.radio.group>
                @foreach (LaravelLocalization::getSupportedLocales() as $code => $label)
                    <flux:menu.item
                        href="{{ LaravelLocalization::getLocalizedURL($code, null, [], true) }}"
                        :active="LaravelLocalization::getCurrentLocale() === $code"
                        :class="LaravelLocalization::getCurrentLocale() === $code ? 'font-bold' : ''"
                    >
                        {{ $label['native'] }}
                    </flux:menu.item>
                @endforeach
            </flux:menu.radio.group>
        </flux:menu>
    </flux:dropdown>
</div>
