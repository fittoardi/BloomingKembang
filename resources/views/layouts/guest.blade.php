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
    <body class="font-sans text-obsidian bg-storefront-ambient antialiased relative min-h-screen flex flex-col justify-center items-center py-8 px-4 selection:bg-blush-200 selection:text-blush-900">
        <!-- Ambient Decorative Glow Background Orbs -->
        <div class="fixed top-[-10%] left-[-10%] w-[28rem] h-[28rem] bg-blush-200/35 rounded-full blur-[100px] pointer-events-none -z-10"></div>
        <div class="fixed bottom-[-10%] right-[-10%] w-[28rem] h-[28rem] bg-sage-200/40 rounded-full blur-[100px] pointer-events-none -z-10"></div>

        <div class="mb-6 text-center">
            <a href="/" class="group inline-flex flex-col items-center gap-2">
                <x-application-logo class="w-14 h-14 group-hover:rotate-12 transition-transform duration-300 shadow-sm rounded-full" />
                <span class="inline-flex items-center gap-1 text-lg uppercase tracking-[0.18em]">
                    <span class="font-serif tracking-normal text-obsidian font-bold">Blooming</span>
                    <span class="font-serif italic font-normal text-bloom tracking-normal">Kembang</span>
                    <span class="w-1.5 h-1.5 rounded-full bg-botanic inline-block ml-0.5"></span>
                </span>
            </a>
        </div>

        <div class="w-full sm:max-w-md px-6 py-6 bg-white/95 backdrop-blur-md shadow-[0_8px_30px_rgba(78,103,78,0.08)] border border-blush-200/80 rounded-2xl">
            {{ $slot }}
        </div>
    </body>
</html>
