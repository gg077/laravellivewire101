# Inline Translation Editing with Livewire

## Overview

This README documents our implementation of an inline translation editing system using Laravel Livewire. The system allows administrators to edit multilingual content directly on the page without navigating to a separate admin interface.

## Features

- Direct on-page editing for administrators
- Language-specific content editing
- Real-time validation feedback
- Instant updates without page reloads
- Role-based access control

## Project Structure

Our implementation consists of two main files:
1. `App\Livewire\Posts\ShowPost.php` - The Livewire component controller
2. `livewire.posts.show-post.blade.php` - The view template

## Database Structure

The `posts` table is designed with language-specific columns for multilingual content:
- `title_en`, `title_nl`, etc. - Title fields for each supported language
- `content_en`, `content_nl`, etc. - Content fields for each supported language

## Implementation Details

### The Livewire Component (ShowPost.php)

```php
<?php

namespace App\Livewire\Posts;

use App\Models\Post;
use Livewire\Component;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class ShowPost extends Component
{
    public Post $post;                // The post being displayed/edited
    public $currentLanguage = 'en';   // Current active language
    public $editing = false;          // Toggle edit mode
    public $title = '';               // Editable title field
    public $content = '';             // Editable content field

    // Validation rules
    protected $rules = [
        'title' => 'required|min:3',
        'content' => 'required|min:10',
    ];

    // Custom validation messages
    protected $messages = [
        'title.required' => 'The title field is required.',
        'title.min' => 'The title must be at least 3 characters.',
        'content.required' => 'The content field is required.',
        'content.min' => 'The content must be at least 10 characters.',
    ];

    // Initialize component with post data
    public function mount(Post $post)
    {
        $this->currentLanguage = LaravelLocalization::getCurrentLocale();
        $this->post = $post;
        $this->resetFields();
    }

    // Reset form fields to current post data
    public function resetFields()
    {
        $this->title = $this->post['title_'.$this->currentLanguage];
        $this->content = $this->post['content_'.$this->currentLanguage];
        $this->resetErrorBag();
    }

    // Toggle editing mode
    public function toggleEdit()
    {
        $this->editing = !$this->editing;
        if (!$this->editing) {
            $this->resetFields();
        }
    }

    // Save updated content
    public function save()
    {
        $this->validate();

        $this->post->update([
            'title_'.$this->currentLanguage => $this->title,
            'content_'.$this->currentLanguage => $this->content,
        ]);

        $this->editing = false;
        session()->flash('message', __('Post successfully updated.'));
        session()->flash('message_type', 'success');
    }

    public function render()
    {
        return view('livewire.posts.show-post');
    }
}
```

### The Blade Template (show-post.blade.php)

```blade
<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    <!-- Flash message for success/errors -->
    @if (session()->has('message'))
        <x-ui.flash-message
            :message="session('message')"
            :type="session('message_type', 'success')"
        />
    @endif

    <!-- Title section with conditional editing -->
    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
        @if($editing)
            <div class="w-full">
                <input
                    type="text"
                    wire:model="title"
                    class="p-2 border rounded-md w-full bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white @error('title') border-red-500 @enderror"
                >
                @error('title')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        @else
            <span>{{ $post['title_'.$currentLanguage] }}</span>
        @endif

        <!-- Edit button for admins only -->
        @if(Auth::user()->hasRole("admin"))
            <button wire:click="toggleEdit" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a2.25 2.25 0 113.182 3.182L7.5 19.314l-4.5 1.5 1.5-4.5L16.862 3.487z" />
                </svg>
            </button>
        @endif
    </h1>

    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6 relative">
        <!-- Save/Cancel buttons -->
        @if($editing && Auth::user()->hasRole("admin"))
            <div class="flex justify-end mb-4">
                <button wire:click="save" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    {{ __('messages.Opslaan') }}
                </button>
                <button wire:click="toggleEdit" class="ml-2 px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                    {{ __('messages.Cancel') }}
                </button>
            </div>
        @endif

        <!-- Author info -->
        <div class="mb-4 text-sm">
            <span class="text-gray-500 dark:text-gray-400">{{ __('messages.Auteur') }}:</span>
            <span class="font-semibold text-gray-900 dark:text-white">{{ $post->author->name }}</span>
        </div>

        <!-- Last updated info -->
        <div class="mb-4 text-sm">
            <span class="text-gray-500 dark:text-gray-400">{{ __('messages.Laatst bijgewerkt op') }}:</span>
            <span class="text-gray-900 dark:text-white">{{ $post->updated_at->format('d-m-Y H:i') }}</span>
        </div>

        <!-- Content section with conditional editing -->
        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
            @if($editing)
                <div class="mb-4">
                    <textarea
                        wire:model="content"
                        rows="15"
                        class="p-2 border rounded-md w-full bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white @error('content') border-red-500 @enderror"
                    ></textarea>
                    @error('content')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            @else
                <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $post['content_'.$currentLanguage] }}</p>
            @endif
        </div>
    </div>

    <!-- Back link -->
    <div class="mt-6">
        <a href="{{ route('posts.index') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">
            {{ __('messages.Terug naar overzicht') }}
        </a>
    </div>
</div>
```

## How It Works

### 1. Language Detection

We use the `LaravelLocalization` package to detect the current language:

```php
$this->currentLanguage = LaravelLocalization::getCurrentLocale();
```

This allows us to load and save content specific to the current language.

### 2. Toggle Edit Mode

The edit mode is controlled by the `$editing` property. When an admin clicks the edit button, the `toggleEdit()` method is called:

```php
public function toggleEdit()
{
    $this->editing = !$this->editing;
    if (!$this->editing) {
        $this->resetFields();
    }
}
```

### 3. Data Binding

Livewire handles data binding between the component properties and the form fields using the `wire:model` directive:

```blade
<input type="text" wire:model="title" class="...">
<textarea wire:model="content" rows="15" class="..."></textarea>
```

### 4. Validation

Form validation occurs in real-time and when saving:

```php
public function save()
{
    $this->validate();
    
    // Save data...
}
```

Error messages appear immediately below each field:

```blade
@error('title')
<p class="mt-1 text-sm text-red-600">{{ $message }}</p>
@enderror
```

### 5. Dynamic Language-Specific Field Updates

We update only the fields for the current language:

```php
$this->post->update([
    'title_'.$this->currentLanguage => $this->title,
    'content_'.$this->currentLanguage => $this->content,
]);
```

### 6. Role-Based Access Control

Only administrators can edit content:

```blade
@if(Auth::user()->hasRole("admin"))
    <button wire:click="toggleEdit" class="...">
        <!-- Edit icon -->
    </button>
@endif
```

### 7. User Feedback

After saving, we provide user feedback using flash messages:

```php
session()->flash('message', __('Post successfully updated.'));
session()->flash('message_type', 'success');
```

## Key Design Patterns

1. **Reactive Data Binding** - Livewire handles two-way data binding without custom JavaScript
2. **State Management** - The `$editing` property controls the UI state
3. **Dynamic Language-Specific Fields** - Component loads and saves to appropriate language fields
4. **Role-Based Access Control** - Edit functionality available only to admin users

## Benefits

1. **Improved Content Management Workflow**
    - Admins see changes in context
    - No navigation through admin panels
    - Immediate visual feedback

2. **Enhanced Developer Experience**
    - Clean separation of concerns
    - Reusable components
    - Reduced JavaScript overhead

3. **Faster Multilingual Content Updates**
    - Quick language switching
    - Consistent UI across languages
    - Built-in validation for each language version

## Requirements

- Laravel 8.x or higher
- Livewire 2.x
- Laravel-localization package

## Installation

1. Ensure you have the required packages installed:
```bash
composer require livewire/livewire
composer require mcamara/laravel-localization
```

2. Create the Livewire component:
```bash
php artisan make:livewire Posts/ShowPost
```

3. Copy the provided code to the respective files

4. Make sure your Post model has the required language-specific fields fillable:
```php
protected $fillable = [
    'title_en', 'title_nl', // Add all supported languages
    'content_en', 'content_nl', // Add all supported languages
];
```

## Conclusion

The inline translation editing system with Livewire provides an elegant solution for multilingual content management. The reactive nature of Livewire combined with Laravel's localization features creates a seamless editing experience that improves both admin efficiency and content quality.
