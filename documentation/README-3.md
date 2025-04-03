# Laravel Localization Implementation Guide

## Introduction

This guide documents how to implement multilingual routes in Laravel applications using the `mcamara/laravel-localization` package. This implementation enables SEO-friendly multilingual URLs (like `/en/dashboard`, `/fr/dashboard`, `/nl/dashboard`, etc.) and proper localization handling throughout the application.

## Installation and Setup

### 1. Install the Package

```bash
composer require mcamara/laravel-localization
```

### 2. Publish the Configuration

Publish the package configuration file to customize your localization settings:

```bash
php artisan vendor:publish --provider="Mcamara\LaravelLocalization\LaravelLocalizationServiceProvider"
```

This will create a `laravellocalization.php` configuration file in your `config` directory.

### 3. Configure Middleware

Register the necessary middleware aliases in `app.php`:

```php
$middleware->alias([
    'localize'                => LaravelLocalizationRoutes::class,
    'localizationRedirect'    => LaravelLocalizationRedirectFilter::class,
    'localeSessionRedirect'   => LocaleSessionRedirect::class,
    'localeCookieRedirect'    => LocaleCookieRedirect::class,
    'localeViewPath'          => LaravelLocalizationViewPath::class,
]);
```

### 4. Configure Supported Locales

Define your supported languages in the `laravellocalization.php` configuration file:

```php
'supportedLocales' => [
    'en' => ['name' => 'English', 'script' => 'Latn', 'native' => 'English', 'regional' => 'en_GB'],
    'es' => ['name' => 'Spanish', 'script' => 'Latn', 'native' => 'español', 'regional' => 'es_ES'],
    'fr' => ['name' => 'French', 'script' => 'Latn', 'native' => 'français', 'regional' => 'fr_FR'],
    'nl' => ['name' => 'Dutch', 'script' => 'Latn', 'native' => 'Nederlands', 'regional' => 'nl_NL'],
],
```

Add or remove languages according to your project requirements.

### 5. Language Files Location

Ensure that your language files are placed in the project root's `/lang` directory instead of the legacy `resources/lang` location:

```
/lang/en/messages.php
/lang/fr/messages.php
/lang/nl/messages.php
/lang/es/messages.php
```

If the `resources/lang` directory exists in your project, you should move any existing language files to the new location and then remove this directory to avoid conflicts:

```bash
# If you have existing language files
mv resources/lang/* lang/
rm -rf resources/lang
```

## Implementation

### 1. Wrap Routes with Localization Group

Wrap your routes in both `web.php` and `auth.php` with the localization group:

```php
Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath'],
], function() {
    // All routes go here
});
```

### 2. Configure Livewire (for Livewire applications)

If your application uses Livewire, make sure Livewire updates continue to work by adding this inside your localization route group:

```php
Livewire::setUpdateRoute(function ($handle) {
    return Route::post('/livewire/update', $handle);
});
```

This ensures Livewire's update requests work correctly with your localized routes.

### 3. Example Route Setup

Here's a complete example of `web.php` with localization and Livewire:

```php
<?php

use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => ['localeSessionRedirect', 'localizationRedirect', 'localeViewPath']
], function() {
    
    // Configure Livewire to work with localization
    Livewire::setUpdateRoute(function ($handle) {
        return Route::post('/livewire/update', $handle);
    });
    
    // Your routes go here
    Route::get('/', function () {
        return view('welcome');
    })->name('home');
    
    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');
        
        // More routes...
    });
    
    // Include authentication routes
    require __DIR__.'/auth.php';
});
```

## How It Works

1. **URL Prefix**: The `LaravelLocalization::setLocale()` method adds the current locale as a prefix to all URLs.

2. **Middleware Chain**:
    - `localeSessionRedirect`: Stores the locale in the session
    - `localizationRedirect`: Redirects to the proper localized URL
    - `localeViewPath`: Helps load localized views

3. **Route Definition**: All routes are defined inside the localization group, ensuring they receive the locale prefix.

## Usage

### Accessing Routes in Different Languages

Your application will automatically generate URLs with locale prefixes:

- English: `/en/dashboard`, `/en/settings/profile`
- Dutch: `/nl/dashboard`, `/nl/settings/profile`
- French: `/fr/dashboard`, `/fr/settings/profile`
- Spanish: `/es/dashboard`, `/es/settings/profile`

### Generating URLs in Code

To generate URLs with the proper locale prefix:

```php
route('dashboard'); // Automatically includes the current locale
```

To specify a different locale:

```php
LaravelLocalization::getLocalizedURL('fr', route('dashboard')); // Forces French locale
```

## Advanced Configuration

Important configuration options in `laravellocalization.php`:

### Hide Default Locale

```php
'hideDefaultLocaleInURL' => false,
```

When set to `true`, the default locale won't appear in URLs (e.g., `/about` instead of `/en/about` for English).

### Browser Language Detection

```php
'useAcceptLanguageHeader' => false,
```

When `true`, automatically determines locale from browser headers on first visit.

### Ignored URLs

```php
'urlsIgnored' => ['/skipped'],
```

URLs that should not be processed by the localization middleware.

### Ignored HTTP Methods

```php
'httpMethodsIgnored' => ['POST', 'PUT', 'PATCH', 'DELETE'],
```

HTTP methods that won't be affected by localization.

## Translation Files

### Create Translation Files

Create translation files for each language in the project root's `/lang` directory:

```
/lang/en/messages.php
/lang/fr/messages.php
/lang/nl/messages.php
/lang/es/messages.php
```

Example `messages.php`:

```php
return [
    'welcome' => 'Welcome to our application',
    'dashboard' => 'Dashboard',
    'settings' => 'Settings',
];
```

### Access Translations in Views

Use the translation helper in Blade templates:

```php
{{ __('messages.welcome') }}
```

Or with variables:

```php
{{ __('messages.hello', ['name' => $user->name]) }}
```

## Best Practices

1. **Keep all routes inside the localization group**: This ensures consistent URL structures across all languages.

2. **Use proper translation files**: Organize translations by feature or section to maintain clarity.

3. **Set appropriate locale in controllers**:
   ```php
   App::setLocale($locale);
   ```

4. **Test all locales**: Make sure your application works correctly in all supported languages.

5. **Consider SEO implications**: Implement proper SEO tags for multilingual sites in your layout:

   ```php
   {{-- Set canonical URL --}}
   <link rel="canonical" href="{{ LaravelLocalization::getNonLocalizedURL() }}">
   
   {{-- Add hreflang attributes for all supported languages --}}
   @foreach(LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
       <link rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
   @endforeach
   
   {{-- Add x-default hreflang attribute --}}
   <link rel="alternate" hreflang="x-default" href="{{ LaravelLocalization::getLocalizedURL(config('app.fallback_locale'), null, [], true) }}">
   
   {{-- Set content language meta tag --}}
   <meta http-equiv="Content-Language" content="{{ str_replace('_', '-', app()->getLocale()) }}">
   
   {{-- Localized meta descriptions and keywords --}}
   @php
       $locale = LaravelLocalization::getCurrentLocale();
       // Get localized descriptions from database
       $description = $descriptions->$locale ?? 'Default description';
       $keyword = $keywords->$locale ?? 'Default keywords';
   @endphp
   
   <meta name="description" content="{{ $description }}">
   <meta name="keywords" content="{{ $keyword }}">
   ```

   This implementation helps search engines understand your multilingual structure and serves the correct language version to users.

## Troubleshooting

### Routes Not Working with Localization

Make sure all routes are inside the localization group and you don't have conflicting route definitions outside the group.

### Livewire Updates Failing

Ensure you've set the Livewire update route inside the localization group:

```php
Livewire::setUpdateRoute(function ($handle) {
    return Route::post('/livewire/update', $handle);
});
```

### 404 Errors on Localized Routes

Check that you've properly registered the middleware aliases in `app.php`.

## Conclusion

By following this guide, you'll have a fully functional multilingual Laravel application with SEO-friendly URLs. The `mcamara/laravel-localization` package provides a robust foundation for creating applications that serve users across different languages and regions.
