<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="border-r border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="mr-5 flex items-center space-x-2" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Platform')" class="grid">
                    <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>{{ __('messages.Dashboard') }}</flux:navlist.item>
                </flux:navlist.group>
            </flux:navlist>
            <flux:navlist.item
                icon="users"
                :href="route('users.index')"
                :current="request()->routeIs('users.index')"
                wire:navigate
            >
                {{ __('messages.Gebruikers') }}
            </flux:navlist.item>

            <flux:navlist.item
                icon="key"
                :href="route('roles.index')"
                :current="request()->routeIs('roles.index')"
                wire:navigate
            >
                {{ __('messages.Rollen') }}
            </flux:navlist.item>

            <flux:navlist.item
                icon="tag"
                :href="route('categories.index')"
                :current="request()->routeIs('categories.index')"
                wire:navigate
            >
                {{ __('messages.Categories') }}
            </flux:navlist.item>
            <flux:navlist.item
                icon="book-open-text"
                :href="route('posts.index')"
                :current="request()->routeIs('posts.index')"
                wire:navigate
            >
                {{ __('messages.Posts') }}
            </flux:navlist.item>

            <flux:spacer />

            <flux:navlist variant="outline">
                <flux:navlist.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                {{ __('messages.Repository') }}
                </flux:navlist.item>

                <flux:navlist.item icon="book-open-text" href="https://laravel.com/docs/starter-kits" target="_blank">
                {{ __('messages.Documentation') }}
                </flux:navlist.item>
            </flux:navlist>

            <!-- Language & User Dropdowns: side-by-side, equal, centered, small spacing -->
            <div class="flex justify-evenly items-center gap-2 px-4 py-4">
                <!-- Language Switcher -->
                @php($languages = [
           'en' => 'English',
           'nl' => 'Nederlands',
           'fr' => 'Français',
           'es' => 'Español'
       ])

                <flux:dropdown>
                    <flux:button icon:trailing="chevron-down" class="w-24 justify-between">
                        {{ strtoupper(app()->getLocale()) }}
                    </flux:button>
                    <flux:menu>
                        <flux:menu.radio.group>
                            @foreach ($languages as $code => $label)
                                <flux:menu.item
                                    :href="route('lang.change', ['lang' => $code])"
                                    :active="app()->getLocale() === $code"
                                    :class="app()->getLocale() === $code ? 'font-bold' : ''"
                                >
                                    {{ $label }}
                                </flux:menu.item>
                            @endforeach
                        </flux:menu.radio.group>
                    </flux:menu>
                </flux:dropdown>



                <!-- User Menu -->
                <flux:dropdown position="bottom" align="end">
                    <flux:button icon:trailing="chevrons-up-down" class="w-24 justify-between">
                        {{ auth()->user()->initials() }}
                    </flux:button>
                    <flux:menu class="w-56">
                        <flux:menu.radio.group>
                            <div class="p-0 text-sm font-normal">
                                <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                        <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                            <span
                                class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                            >
                                {{ auth()->user()->initials() }}
                            </span>
                        </span>
                                    <div class="grid flex-1 text-left text-sm leading-tight">
                                        <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                        <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                    </div>
                                </div>
                            </div>
                        </flux:menu.radio.group>

                        <flux:menu.separator />

                        <flux:menu.radio.group>
                            <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('messages.Settings') }}</flux:menu.item>
                        </flux:menu.radio.group>

                        <flux:menu.separator />

                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                                {{ __('messages.Log Out') }}
                            </flux:menu.item>
                        </form>
                    </flux:menu>
                </flux:dropdown>
            </div>

        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>
