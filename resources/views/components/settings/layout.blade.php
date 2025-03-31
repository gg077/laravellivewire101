<div class="flex items-start max-md:flex-col">
    <div class="mr-10 w-full pb-4 md:w-[220px]">
        <flux:navlist>
            <flux:navlist.item :href="route('settings.profile')" wire:navigate>{{ __('profile') }}</flux:navlist.item> <!-- Profile Dynamisch Laden -->
            <flux:navlist.item :href="route('settings.password')" wire:navigate>{{ __('password') }}</flux:navlist.item> <!-- Password Dynamisch Laden -->
            <flux:navlist.item :href="route('settings.appearance')" wire:navigate>{{ __('appearance') }}</flux:navlist.item> <!-- Appearance Dynamisch Laden -->
        </flux:navlist>
    </div>

    <flux:separator class="md:hidden" />

    <div class="flex-1 self-stretch max-md:pt-6">
        <flux:heading>{{ $heading ?? '' }}</flux:heading>
        <flux:subheading>{{ $subheading ?? '' }}</flux:subheading>

        <div class="mt-5 w-full max-w-lg">
            {{ $slot }}
        </div>
    </div>
</div>
