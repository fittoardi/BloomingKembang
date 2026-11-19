<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('layouts.storefront.head')
    </head>
    <body class="bg-[#faf9f7] text-obsidian min-h-screen flex flex-col font-sans antialiased selection:bg-blush-200 selection:text-blush-900 relative overflow-x-hidden">
        <!-- Ambient Subtle Background Motif & Glow (Hijau Botanic & Pink Bloom) -->
        <div class="fixed inset-0 pointer-events-none -z-10 overflow-hidden" aria-hidden="true">
            <!-- Atmospheric Organic Diffuse Glows (Sage Green & Bloom Pink) -->
            <div class="absolute -top-[12%] -left-[10%] w-[55vw] h-[55vw] max-w-[700px] max-h-[700px] rounded-full bg-gradient-to-br from-blush-200/40 via-blush-100/20 to-transparent blur-3xl"></div>
            <div class="absolute top-[20%] -right-[12%] w-[60vw] h-[60vw] max-w-[750px] max-h-[750px] rounded-full bg-gradient-to-bl from-sage-200/45 via-sage-100/20 to-transparent blur-3xl"></div>
            <div class="absolute top-[55%] -left-[8%] w-[50vw] h-[50vw] max-w-[650px] max-h-[650px] rounded-full bg-gradient-to-tr from-blush-200/35 via-sage-100/25 to-transparent blur-3xl"></div>
            <div class="absolute -bottom-[8%] right-[5%] w-[55vw] h-[55vw] max-w-[700px] max-h-[700px] rounded-full bg-gradient-to-tl from-sage-200/50 via-blush-100/30 to-transparent blur-3xl"></div>

            <!-- Delicate Repeating Botanical Motif Pattern (Botanic Green & Bloom Pink) -->
            <div 
                class="absolute inset-0 bg-kembang-motif opacity-[0.55] mix-blend-multiply"
                style="background-image: url('{{ asset('images/patterns/kembang-motif.svg') }}'); background-repeat: repeat; background-size: 130px 130px;"
            ></div>
        </div>

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
