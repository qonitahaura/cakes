<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Cakes' }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body
    class="min-h-screen bg-gradient-to-br from-primary-50 via-white to-accent-50 font-sans text-accent-900 antialiased"
    @isset($page) data-page="{{ $page }}" @endisset
    @yield('body-attrs')
>
    @yield('content')
    <x-toast />
    @stack('scripts')
</body>
</html>
