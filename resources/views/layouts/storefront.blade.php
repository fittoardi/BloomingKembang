<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'BloomingKembang — Hijab Store' }}</title>

        <!-- Google Fonts: Inter -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500|jetbrains-mono:400" rel="stylesheet" />

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="bg-paper text-obsidian min-h-screen flex flex-col font-sans antialiased selection:bg-bone">
        <!-- Top Announcement Banner -->
        <div class="bg-eucalyptus text-obsidian py-2 px-4 text-center text-xs tracking-wider uppercase font-medium">
            Gratis Ongkir Seluruh Pulau Jawa untuk Pembelian di Atas Rp 150.000
        </div>

        <!-- Minimal Header Navigation -->
        <header class="border-b border-bone bg-paper sticky top-0 z-40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <!-- Left Nav Items -->
                <nav class="hidden md:flex items-center space-x-8 text-xs uppercase tracking-widest">
                    <a href="{{ url('/') }}" class="hover:text-neutral-500 transition-colors">Beranda</a>
                    <a href="{{ url('/koleksi') }}" class="hover:text-neutral-500 transition-colors">Koleksi</a>
                    <a href="{{ url('/tentang-kami') }}" class="hover:text-neutral-500 transition-colors">Tentang</a>
                </nav>

                <!-- Center Brand Colophon -->
                <div class="text-center">
                    <a href="{{ url('/') }}" class="text-base sm:text-lg font-normal tracking-[0.2em] uppercase">
                        BloomingKembang
                    </a>
                </div>

                <!-- Right Utility Links -->
                <div class="flex items-center space-x-6 text-xs uppercase tracking-widest">
                    @auth
                        @if(auth()->user()->hasAnyRole(['super_admin', 'staff']))
                            <a href="{{ url('/admin') }}" class="link-underlined">Panel Admin</a>
                        @endif
                        <a href="{{ route('account.dashboard') }}" class="hover:text-neutral-500 transition-colors">Akun Saya</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="hover:text-neutral-500 uppercase tracking-widest transition-colors">Keluar</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="hover:text-neutral-500 transition-colors">Masuk</a>
                        <a href="{{ route('register') }}" class="link-underlined">Daftar</a>
                    @endauth
                    <button 
                        type="button" 
                        @click="$dispatch('open-cart')" 
                        class="hover:text-neutral-500 transition-colors uppercase tracking-widest flex items-center space-x-1"
                    >
                        <span>Keranjang</span>
                        <span class="font-mono">({{ app(\App\Services\CartService::class)->getCount() }})</span>
                    </button>
                </div>
            </div>
        </header>

        <!-- Cart Drawer Livewire Component -->
        <livewire:cart-drawer />

        <!-- Main Content Area -->
        <main class="flex-grow">
            {{ $slot }}
        </main>

        <!-- Museum Minimal Colophon Footer -->
        <footer class="border-t border-bone bg-paper mt-24 py-16 text-xs text-obsidian">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-12">
                <div class="space-y-3">
                    <h4 class="text-xs tracking-widest uppercase font-medium">BloomingKembang</h4>
                    <p class="text-neutral-600 leading-relaxed text-xs">
                        Koleksi hijab bernapas lembut dan berkarakter elegan untuk muslimah bersahaja.
                    </p>
                </div>

                <div class="space-y-3">
                    <h4 class="text-xs tracking-widest uppercase font-medium">Koleksi</h4>
                    <ul class="space-y-2 text-neutral-600 text-xs">
                        <li><a href="{{ url('/koleksi/hijab-segi-empat') }}" class="hover:text-obsidian">Hijab Segi Empat</a></li>
                        <li><a href="{{ url('/koleksi/pashmina') }}" class="hover:text-obsidian">Pashmina</a></li>
                        <li><a href="{{ url('/koleksi/inner-dan-aksesoris') }}" class="hover:text-obsidian">Inner & Aksesoris</a></li>
                    </ul>
                </div>

                <div class="space-y-3">
                    <h4 class="text-xs tracking-widest uppercase font-medium">Bantuan & Informasi</h4>
                    <ul class="space-y-2 text-neutral-600 text-xs">
                        <li><a href="{{ url('/lacak-pesanan') }}" class="hover:text-obsidian font-medium text-obsidian">Lacak Pesanan</a></li>
                        <li><a href="{{ url('/faq') }}" class="hover:text-obsidian">FAQ</a></li>
                        <li><a href="{{ url('/kebijakan-retur') }}" class="hover:text-obsidian">Kebijakan Retur</a></li>
                        <li><a href="{{ url('/syarat-dan-ketentuan') }}" class="hover:text-obsidian">Syarat & Ketentuan</a></li>
                        <li><a href="{{ url('/kebijakan-privasi') }}" class="hover:text-obsidian">Kebijakan Privasi</a></li>
                    </ul>
                </div>

                <div class="space-y-3">
                    <h4 class="text-xs tracking-widest uppercase font-medium">Hubungi Kami</h4>
                    <p class="text-neutral-600 leading-relaxed text-xs">
                        WhatsApp: +62 812-3456-7890<br>
                        Email: cs@bloomingkembang.com<br>
                        Bandung, Jawa Barat
                    </p>
                </div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 pt-8 border-t border-bone flex flex-col sm:flex-row items-center justify-between text-[11px] text-neutral-500">
                <p>&copy; {{ date('Y') }} BloomingKembang. Hak cipta dilindungi.</p>
                <p class="mt-2 sm:mt-0 tracking-widest uppercase">Paper-White Minimalist Storefront</p>
            </div>
        </footer>

        @livewireScripts
    </body>
</html>
