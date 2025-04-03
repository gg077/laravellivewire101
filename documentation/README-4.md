# Language Switcher Implementation

## Overview

This document explains the implementation of a language switcher dropdown in the sidebar of our application. The language switcher allows users to seamlessly switch between different supported languages, enhancing the overall user experience.

## Implementation Details

We've implemented a dropdown language selector in `sidebar.blade.php` using Flux UI components and the `mcamara/laravel-localization` package.

### Components Used

- **Flux UI Components**: Utilized for the dropdown interface
    - `flux:dropdown`: Container for the dropdown
    - `flux:button`: Trigger button that displays the current language code
    - `flux:menu`: Dropdown menu container
    - `flux:menu.radio.group`: Groups the language options
    - `flux:menu.item`: Individual language options

### Functionality

- The current language is displayed in the button as an uppercase code (e.g., "EN", "NL")
- When clicked, it shows a dropdown with all supported languages in their native names
- The currently active language is highlighted with bold text
- Clicking a language automatically redirects to the localized version of the current page

### Technical Implementation

```blade
<!-- Language Switcher -->
<flux:dropdown>
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
```

## Key Features

1. **User-Friendly Interface**: Clean dropdown design with clear language options
2. **Persistent Language Selection**: Uses Laravel Localization to maintain language choice across page navigation
3. **Visual Feedback**: Highlights the active language for better user orientation
4. **Native Language Names**: Displays each language in its native form for better recognition
5. Language auto-detection based on browser settings

## Dependencies

- [mcamara/laravel-localization](https://github.com/mcamara/laravel-localization): Provides the core localization functionality
- Flux UI component library: Provides the UI components for the dropdown interface

