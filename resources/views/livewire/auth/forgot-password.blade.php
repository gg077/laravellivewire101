<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        Password::sendResetLink($this->only('email'));

        session()->flash('status', __('messages.A reset link will be sent if the account exists.'));
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header
        :title="__('messages.Forgot password')"
        :description="__('messages.Enter your email to receive a password reset link')"
    />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="sendPasswordResetLink" class="flex flex-col gap-6">
        <!-- Email Address -->
        <flux:input
            wire:model="email"
            :label="__('messages.Email Address')"
            type="email"
            required
            autofocus
            placeholder="email@example.com"
        />

        <flux:button variant="primary" type="submit" class="w-full">
            {{ __('messages.Email password reset link') }}
        </flux:button>
    </form>

    <div class="space-x-1 text-center text-sm text-zinc-400">
        {{ __('messages.Or, return to') }}
        <flux:link :href="route('login')" wire:navigate>
            {{ __('messages.log in') }}
        </flux:link>
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
