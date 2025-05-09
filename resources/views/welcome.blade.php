<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
</head>

<body class="bg-gray-100 space-y-6 py-8">

<a href="{{ LaravelLocalization::localizeUrl('login') }}">{{ __('auth.login') }}</a>
<a href="{{ LaravelLocalization::localizeUrl('register') }}">{{ __('auth.register') }}</a>

<div class="relative inline-block text-left">
    <button id="dropdownButton" type="button" class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500" onclick="toggleDropdown()">
        {{ __('messages.language') }}
        <svg class="w-5 h-5 ml-2 -mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
    </button>
    <div id="dropdownMenu" class="hidden absolute left-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg z-10">
        <ul class="py-1 text-gray-700">
            @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                <li>
                    <a href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}" class="block px-4 py-2 text-sm hover:bg-gray-100">
                        {{ $properties['native'] }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</div>

<script>
    function toggleDropdown() {
        document.getElementById("dropdownMenu").classList.toggle("hidden");
    }

    // Close the dropdown when clicking outside
    document.addEventListener("click", function(event) {
        const dropdown = document.getElementById("dropdownMenu");
        const button = document.getElementById("dropdownButton");
        if (!button.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.classList.add("hidden");
        }
    });
</script>


{{-- Hello World Component --}}
<x-ui.card title="{{ __('pages.welcome.hello_world') }}">
    <livewire:hello-world />
</x-ui.card>

{{-- Counter Component --}}
<x-ui.card title="{{ __('pages.welcome.counter') }}">
    <livewire:counter />
</x-ui.card>

{{-- Clock Component --}}
<x-ui.card title="{{ __('pages.welcome.clock') }}">
    <livewire:clock />
</x-ui.card>

{{-- Name Form --}}
<x-ui.card title="{{ __('pages.welcome.name_form') }}">
    <livewire:name-form />
</x-ui.card>

{{-- Events: Parent Component --}}
<x-ui.card title="{{ __('pages.welcome.parent_child_event') }}">
    <livewire:parent-component />
</x-ui.card>

{{-- Events: Search Components --}}
<x-ui.card title="{{ __('pages.welcome.search_function') }}">
    <livewire:search-input />
    <livewire:search-results />
</x-ui.card>

{{-- JS Event Trigger --}}
<x-ui.card title="{{ __('pages.welcome.dom_event') }}">
    <livewire:dom-event-example />
</x-ui.card>

<x-ui.forms.group label="{{ __('pages.welcome.email') }}" for="email" error="email">
    <x-ui.forms.input type="email" name="email" id="email" wire:model.live="email" />
</x-ui.forms.group>

</body>
</html>
