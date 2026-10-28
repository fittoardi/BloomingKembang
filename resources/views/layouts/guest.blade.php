<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'BloomingKembang') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}?v=3">
        <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('favicon.png') }}?v=3">
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=3">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-obsidian bg-[#faf9f7] antialiased min-h-screen flex flex-col justify-center items-center py-8 px-4 selection:bg-blush-200 selection:text-blush-900">
        <div class="mb-6 text-center">
            <a href="/" class="group inline-flex flex-col items-center gap-2">
                <x-application-logo class="w-14 h-14 group-hover:rotate-6 transition-transform duration-300 shadow-xs rounded-full" />
                <span class="inline-flex items-center gap-1 text-lg uppercase tracking-[0.18em]">
                    <span class="font-serif tracking-normal text-obsidian font-bold">Blooming</span>
                    <span class="font-serif italic font-normal text-bloom tracking-normal">Kembang</span>
                    <span class="w-1.5 h-1.5 rounded-full bg-botanic inline-block ml-0.5"></span>
                </span>
            </a>
        </div>

        <div class="w-full sm:max-w-md px-6 py-8 bg-white border border-stone-200/90 rounded-2xl shadow-xs">
            {{ $slot }}
        </div>
    </body>
</html>
