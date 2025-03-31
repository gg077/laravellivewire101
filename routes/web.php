<?php

use App\Http\Controllers\SocialiteController;
use App\Livewire\Users\ShowUsers;
use App\Livewire\Users\CreateUser;
use App\Livewire\Users\EditUser;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

Route::group(['prefix' => LaravelLocalization::setLocale(),'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]], function(){
    Route::get('/', function () {
        return view('welcome');
    })->name('home');

//strks in a href van googlelogin => {{ route('auth.google') }}
    Route::controller(SocialiteController::class)->group(function() {
        Route::get('auth/redirection/{provider}', 'authProviderRedirect')->name('auth.redirection');

        Route::get('auth/{provider}/callback', 'socialAuthentication')->name('auth.callback');
    });


    Route::view('dashboard', 'dashboard')
        ->middleware(['auth', 'verified'])
        ->name('dashboard');

    Route::middleware(['auth'])->group(function () {
        Route::redirect('settings', 'settings/profile');
        Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
        Volt::route('settings/password', 'settings.password')->name('settings.password');
        Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
        Route::get('/users', ShowUsers::class)->name('users.index');
        Route::get('/users/create', CreateUser::class)->name('users.create');
        Route::get('/users/{user}/edit', EditUser::class)->name('users.edit');

        // Role routes
        Route::get('/roles', \App\Livewire\Roles\ShowRoles::class)->name('roles.index');
        Route::get('/roles/create', \App\Livewire\Roles\CreateRole::class)->name('roles.create');
        Route::get('/roles/{role}/edit', \App\Livewire\Roles\EditRole::class)->name('roles.edit');

        // posts routes
        Route::get('/posts', \App\Livewire\Posts\ShowPosts::class)->name('posts.index');
        Route::get('/posts/create', \App\Livewire\Posts\CreatePosts::class)->name('posts.create');
        Route::get('/posts/{post}/edit', \App\Livewire\Posts\EditPosts::class)->name('posts.edit');


        // Category routes
        Route::get('/categories', \App\Livewire\Categories\ShowCategory::class)->name('categories.index');
        Route::get('/categories/create', \App\Livewire\Categories\CreateCategory::class)->name('categories.create');
        Route::get('/categories/{category}/edit', \App\Livewire\Categories\EditCategory::class)->name('categories.edit');
    });
});

require __DIR__.'/auth.php';
