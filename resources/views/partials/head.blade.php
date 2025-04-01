@php
    use Illuminate\Support\Facades\DB;
    use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

    $locale = LaravelLocalization::getCurrentLocale();

    $descriptions = DB::table('seos')->where('id', 1)->first();
    $description = $descriptions->$locale ?? 'A powerful and versatile Laravel application for efficient and modern web development.';

    $keywords = DB::table('seos')->where('id', 2)->first();
    $keyword = $keywords->$locale ?? 'Laravel, web application, PHP, MVC, routing, database, authentication, API, Laravel framework, backend, frontend, web development, RESTful, Eloquent ORM, Blade, security, session management, caching, queues';
@endphp

<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

{{-- SEO Meta Tags --}}
<meta name="description" content="{{ $description }}">
<meta name="keywords" content="{{ $keyword }}">
<meta name="robots" content="index, follow">
<link rel="canonical" href="{{ url()->current() }}" />

{{-- Language --}}
<meta http-equiv="Content-Language" content="{{ str_replace('_', '-', app()->getLocale()) }}">
<link rel="alternate" hreflang="x-default" href="{{ url()->current() }}">

<title>{{ $title ?? 'Laravel' }}</title>

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

@vite(['resources/css/app.css', 'resources/js/app.js'])
{{--@fluxAppearance--}}
