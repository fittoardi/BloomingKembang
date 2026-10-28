<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('layouts.storefront.head')
    </head>
    <body class="bg-storefront-ambient text-obsidian min-h-screen flex flex-col font-sans antialiased selection:bg-blush-200 selection:text-blush-900 relative overflow-x-hidden">
        <!-- Ambient Decorative Green & Pink Glow Background Orbs -->
        <div class="fixed top-[-10%] left-[-10%] w-[32rem] sm:w-[42rem] h-[32rem] sm:h-[42rem] bg-blush-200/35 rounded-full blur-[100px] pointer-events-none -z-10 transform-gpu"></div>
        <div class="fixed top-[5%] right-[-10%] w-[30rem] sm:w-[38rem] h-[30rem] sm:h-[38rem] bg-sage-200/40 rounded-full blur-[100px] pointer-events-none -z-10 transform-gpu"></div>
        <div class="fixed top-[45%] left-[10%] w-[28rem] sm:w-[36rem] h-[28rem] sm:h-[36rem] bg-sage-100/40 rounded-full blur-[110px] pointer-events-none -z-10 transform-gpu"></div>
        <div class="fixed bottom-[-10%] right-[-5%] w-[34rem] sm:w-[44rem] h-[34rem] sm:h-[44rem] bg-blush-200/30 rounded-full blur-[120px] pointer-events-none -z-10 transform-gpu"></div>

        <!-- Top Announcement & Category Bar (Gradient Hijau-Pink) -->
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
