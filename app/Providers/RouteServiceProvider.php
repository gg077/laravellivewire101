<?php

use App\Models\Post;
use Illuminate\Support\Facades\Route;

Route::bind('post', function ($slug) {
    return Post::where('slug_en', $slug)
        ->orWhere('slug_nl', $slug)
        ->orWhere('slug_fr', $slug)
        ->orWhere('slug_es', $slug)
        ->firstOrFail();
});

