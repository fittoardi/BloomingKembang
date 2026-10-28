<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('layouts.storefront.head')
    </head>
    <body class="bg-[#faf9f7] text-obsidian min-h-screen flex flex-col font-sans antialiased selection:bg-blush-200 selection:text-blush-900 relative overflow-x-hidden">
        <!-- Top Announcement & Category Bar -->
        @include('layouts.storefront.top-bar')

        <!-- Sticky Header Navigation -->
        @include('layouts.storefront.header')

        <!-- Cart Drawer Livewire Component -->
        <livewire:cart-drawer />

        <!-- Main Content Slot -->
        <main class="flex-grow">
            {{ $slot }}
        </main>

        <!-- Floating WhatsApp Contact Button -->
        @include('layouts.storefront.whatsapp-button')

        <!-- Privacy & Analytics Cookie Consent Banner -->
        @include('layouts.storefront.cookie-consent')

        <!-- Colophon Footer -->
        @include('layouts.storefront.footer')

        @livewireScripts
    </body>
</html>
