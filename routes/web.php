<?php

use App\Http\Controllers\SocialiteController;
use App\Livewire\Categories\CreateCategory;
use App\Livewire\Categories\EditCategory;
use App\Livewire\Categories\ShowCategory;
use App\Livewire\Posts\CreatePost;
use App\Livewire\Posts\EditPost;
use App\Livewire\Posts\ShowPost;
use App\Livewire\Posts\ShowPosts;
use App\Livewire\Seos\ShowSeos;
use App\Livewire\Seos\EditSeo;
use App\Livewire\Roles\CreateRole;
use App\Livewire\Roles\EditRole;
use App\Livewire\Roles\ShowRoles;
use App\Livewire\Users\ShowUsers;
use App\Livewire\Users\CreateUser;
use App\Livewire\Users\EditUser;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::group([
    'prefix' => LaravelLocalization::setLocale(),
    'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]
], function()
{
    /** ADD ALL LOCALIZED ROUTES INSIDE THIS GROUP **/

    Livewire::setUpdateRoute(function ($handle) {
        return Route::post('/livewire/update', $handle);
    });

    Route::get('/', function () {
        return view('welcome');
    })->name('home');

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
        Route::get('/roles', ShowRoles::class)->name('roles.index');
        Route::get('/roles/create', CreateRole::class)->name('roles.create');
        Route::get('/roles/{role}/edit', EditRole::class)->name('roles.edit');

        // posts routes
        Route::get('/posts', ShowPosts::class)->name('posts.index');
        Route::get('/posts/create', CreatePost::class)->name('posts.create');
        Route::get('/posts/{post}/edit', EditPost::class)->name('posts.edit');
        Route::get('/posts/{post}', ShowPost::class)->name('post.show');

        // seo routes
        Route::get('/seos', ShowSeos::class)->name('seos.index');
        Route::get('/seos/{seo}/edit', EditSeo::class)->name('seos.edit');


        // Category routes
        Route::get('/categories', ShowCategory::class)->name('categories.index');
        Route::get('/categories/create', CreateCategory::class)->name('categories.create');
        Route::get('/categories/{category}/edit', EditCategory::class)->name('categories.edit');
    });

});

require __DIR__.'/auth.php';
