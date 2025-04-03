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
                {{ __('messages.Users') }}
            </flux:navlist.item>

            <flux:navlist.item
                icon="key"
                :href="route('roles.index')"
                :current="request()->routeIs('roles.index')"
                wire:navigate
            >
                {{ __('messages.Roles') }}
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
            <flux:navlist.item
                icon="document-magnifying-glass"
                :href="route('seos.index')"
                :current="request()->routeIs('seos.index')"
                wire:navigate
            >
                {{ __('Seo') }}
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
                <flux:dropdown>
                    <flux:button icon:trailing="chevron-down" class="w-24 justify-between">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m20.893 13.393-1.135-1.135a2.252 2.252 0 0 1-.421-.585l-1.08-2.16a.414.414 0 0 0-.663-.107.827.827 0 0 1-.812.21l-1.273-.363a.89.89 0 0 0-.738 1.595l.587.39c.59.395.674 1.23.172 1.732l-.2.2c-.212.212-.33.498-.33.796v.41c0 .409-.11.809-.32 1.158l-1.315 2.191a2.11 2.11 0 0 1-1.81 1.025 1.055 1.055 0 0 1-1.055-1.055v-1.172c0-.92-.56-1.747-1.414-2.089l-.655-.261a2.25 2.25 0 0 1-1.383-2.46l.007-.042a2.25 2.25 0 0 1 .29-.787l.09-.15a2.25 2.25 0 0 1 2.37-1.048l1.178.236a1.125 1.125 0 0 0 1.302-.795l.208-.73a1.125 1.125 0 0 0-.578-1.315l-.665-.332-.091.091a2.25 2.25 0 0 1-1.591.659h-.18c-.249 0-.487.1-.662.274a.931.931 0 0 1-1.458-1.137l1.411-2.353a2.25 2.25 0 0 0 .286-.76m11.928 9.869A9 9 0 0 0 8.965 3.525m11.928 9.868A9 9 0 1 1 8.965 3.525" />
                        </svg>

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



                <!-- User Menu -->
                <flux:dropdown position="bottom" align="end">
                    <flux:button icon:trailing="chevrons-up-down" class="w-24 justify-between">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>

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
