# Laravel Livewire Multilingual CMS

A complete multilingual content management system built with Laravel and Livewire that allows administrators to manage content in multiple languages (Dutch, French, English) and displays the appropriate language version to visitors, including multilingual URLs.

## Project Overview

This multilingual CMS allows you to:
- Manage content in multiple languages
- Create SEO-friendly multilingual URLs
- Switch between languages easily
- Edit translations inline
- Import/export translations
- Manage SEO metadata per language

## Core Models

- `Page` - Core content structure
- `Translation` - Language-specific content (optional per key or inline)
- `SeoMeta` - SEO metadata per language (optional)
- `User` - Administrator accounts

## Documentation

The project is divided into 8 modules, each with its own documentation:

1. [Laravel Multilingual Setup](https://github.com/gg077/laravellivewire101/blob/main/documentation/README-1.md) - Activating Laravel's multilingual capabilities
2. [Livewire CMS with Translated Fields](https://github.com/gg077/laravellivewire101/blob/main/documentation/README-2.md) - Creating and managing multilingual content
3. [Multilingual Routes Implementation](https://github.com/gg077/laravellivewire101/blob/main/documentation/README-3.md) - Setting up SEO-friendly multilingual URLs
4. [Language Switcher Component](https://github.com/gg077/laravellivewire101/blob/main/documentation/README-4.md) - Building a language selection dropdown/button
5. [Translation Export Command](https://github.com/gg077/laravellivewire101/blob/main/documentation/README-5.md) - Artisan command for exporting translations
6. [Inline Translation Editing](https://github.com/gg077/laravellivewire101/blob/main/documentation/README-6.md) - Advanced Livewire inline editing
7. [Excel Translation Import](https://github.com/gg077/laravellivewire101/blob/main/documentation/README-7.md) - Importing translations from Excel files
8. [Multilingual SEO Management](https://github.com/gg077/laravellivewire101/blob/main/documentation/README-8.md) - Managing SEO metadata per language
9. [AI integration](https://www.notion.so/OpenAI-GPT-integratie-voor-automatische-blogposts-1e9fd85f656480d98fcee15b79185b40) - Integrating AI text generation

Socialite / OpenAI Integration Follow This Link:
https://www.notion.so/Laravellivewire101-Socialite-GPT-1e9fd85f65648092ac20d4eaa3f217d3?pvs=4

## Installation

```bash
git clone https://github.com/gg077/laravellivewire101.git
cd laravellivewire101
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
npm run dev
```

## Technologies Used

- Laravel (core framework)
- Livewire (interactive UI components)
- Laravel Localization (native or mcamara/laravel-localization)
- Laravel Excel (for translation imports/exports)
- Custom middleware for language handling

## License

This project is open-sourced software licensed under the MIT license.

## Contributors

Add your contributors here.
