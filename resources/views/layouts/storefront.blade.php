<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'BloomingKembang — Koleksi Hijab Elegan & Bersahaja' }}</title>

        <!-- Canonical & SEO Meta Tags -->
        <link rel="canonical" href="{{ url()->current() }}" />
        <meta name="description" content="{{ $metaDescription ?? 'Koleksi hijab bernapas lembut dan berkarakter elegan untuk muslimah bersahaja. Ditenun dari serat voal ultrafine dan ceruty babydoll pilihan.' }}">
        <meta name="keywords" content="hijab, kerudung, pashmina, voal ultrafine, hijab segi empat, inner rajut, fashion muslimah, BloomingKembang">
        <meta name="author" content="BloomingKembang">

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url()->current() }}">
        <meta property="og:title" content="{{ $title ?? 'BloomingKembang — Koleksi Hijab Elegan & Bersahaja' }}">
        <meta property="og:description" content="{{ $metaDescription ?? 'Koleksi hijab bernapas lembut dan berkarakter elegan untuk muslimah bersahaja.' }}">
        <meta property="og:site_name" content="BloomingKembang">
        <meta property="og:image" content="{{ $ogImage ?? asset('images/og-bloomingkembang.jpg') }}">

        <!-- Twitter Card -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:url" content="{{ url()->current() }}">
        <meta name="twitter:title" content="{{ $title ?? 'BloomingKembang — Koleksi Hijab Elegan & Bersahaja' }}">
        <meta name="twitter:description" content="{{ $metaDescription ?? 'Koleksi hijab bernapas lembut dan berkarakter elegan untuk muslimah bersahaja.' }}">
        <meta name="twitter:image" content="{{ $ogImage ?? asset('images/og-bloomingkembang.jpg') }}">

        <!-- Organization & WebSite JSON-LD -->
        @php
            $defaultSchema = [
                '@context' => 'https://schema.org',
                '@graph' => [
                    [
                        '@type' => 'Organization',
                        '@id' => url('/') . '#organization',
                        'name' => 'BloomingKembang',
                        'url' => url('/'),
                        'logo' => asset('images/logo.png'),
                        'description' => 'Brand kurasi hijab elegan dan pakaian muslimah bersahaja berbahan serat voal dan ceruty premium.',
                        'address' => [
                            '@type' => 'PostalAddress',
                            'streetAddress' => 'Jl. Citarum No. 28',
                            'addressLocality' => 'Bandung',
                            'addressRegion' => 'Jawa Barat',
                            'postalCode' => '40115',
                            'addressCountry' => 'ID',
                        ],
                        'contactPoint' => [
                            '@type' => 'ContactPoint',
                            'telephone' => '+62-812-3456-7890',
                            'contactType' => 'customer service',
                            'areaServed' => 'ID',
                            'availableLanguage' => ['id', 'en'],
                        ],
                    ],
                    [
                        '@type' => 'WebSite',
                        '@id' => url('/') . '#website',
                        'url' => url('/'),
                        'name' => 'BloomingKembang',
                        'publisher' => [
                            '@id' => url('/') . '#organization',
                        ],
                        'potentialAction' => [
                            '@type' => 'SearchAction',
                            'target' => url('/koleksi') . '?search={search_term_string}',
                            'query-input' => 'required name=search_term_string',
                        ],
                    ],
                ],
            ];
        @endphp
        <script type="application/ld+json">
        {!! json_encode($defaultSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
        </script>

        <!-- Google Fonts: Inter & JetBrains Mono -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500|jetbrains-mono:400" rel="stylesheet" />

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles

        <!-- E-Commerce Analytics Script Helper (GA4 + Meta Pixel) -->
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}

            window.trackEcommerce = function(eventName, params) {
                const consent = localStorage.getItem('blooming_consent');
                if (consent !== 'granted') {
                    return; // Menghormati privasi jika belum disetujui
                }

                // Push to Google Analytics 4 (DataLayer)
                window.dataLayer.push({
                    event: eventName,
                    ecommerce: params
                });

                // Push to Meta Pixel if loaded
                if (typeof fbq === 'function') {
                    const metaEventMap = {
                        'view_item': 'ViewContent',
                        'add_to_cart': 'AddToCart',
                        'begin_checkout': 'InitiateCheckout',
                        'purchase': 'Purchase'
                    };
                    const metaEvent = metaEventMap[eventName] || eventName;
                    fbq('track', metaEvent, params);
                }

                console.debug('[Analytics Tracked]', eventName, params);
            };
        </script>
    </head>
    <body class="bg-paper text-obsidian min-h-screen flex flex-col font-sans antialiased selection:bg-bone relative">
        <!-- Top Announcement Banner -->
        <div class="bg-eucalyptus text-obsidian py-2 px-4 text-center text-xs tracking-wider uppercase font-medium">
            Gratis Ongkir Seluruh Pulau Jawa untuk Pembelian di Atas Rp 150.000
        </div>

        <!-- Minimal Header Navigation -->
        <header class="border-b border-bone bg-paper sticky top-0 z-40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <!-- Left Nav Items -->
                <nav class="hidden md:flex items-center space-x-8 text-xs uppercase tracking-widest">
                    <a href="{{ url('/') }}" class="hover:text-neutral-500 transition-colors {{ request()->is('/') ? 'text-obsidian font-semibold' : '' }}">Beranda</a>
                    <a href="{{ url('/koleksi') }}" class="hover:text-neutral-500 transition-colors {{ request()->is('koleksi*') ? 'text-obsidian font-semibold' : '' }}">Koleksi</a>
                    <a href="{{ url('/tentang-kami') }}" class="hover:text-neutral-500 transition-colors {{ request()->is('tentang-kami') ? 'text-obsidian font-semibold' : '' }}">Tentang</a>
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
                        @if(auth()->user()->hasAnyRole(['super_admin', 'admin', 'warehouse_staff']))
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

        <!-- Floating WhatsApp Contact Button -->
        <div class="fixed bottom-6 right-6 z-40 group">
            <a 
                href="https://wa.me/6281234567890?text=Halo%20BloomingKembang,%20saya%20ingin%20bertanya%20seputar%20produk%20hijab." 
                target="_blank" 
                rel="noopener noreferrer"
                class="flex items-center gap-2 bg-[#25D366] text-white px-4 py-3 rounded-full shadow-lg hover:bg-[#20ba5a] hover:scale-105 transition-all duration-300"
                aria-label="Hubungi kami via WhatsApp"
            >
                <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                </svg>
                <span class="text-xs font-medium tracking-wide">Bantuan WhatsApp</span>
            </a>
        </div>

        <!-- Privacy & Analytics Cookie Consent Banner -->
        <div 
            x-data="{ 
                showConsent: !localStorage.getItem('blooming_consent'),
                accept() {
                    localStorage.setItem('blooming_consent', 'granted');
                    this.showConsent = false;
                },
                decline() {
                    localStorage.setItem('blooming_consent', 'denied');
                    this.showConsent = false;
                }
            }"
            x-show="showConsent"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-4"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-cloak
            class="fixed bottom-0 inset-x-0 z-50 p-4 sm:p-6"
        >
            <div class="max-w-4xl mx-auto bg-obsidian text-paper p-4 sm:p-5 border border-bone/20 shadow-2xl flex flex-col sm:flex-row items-center justify-between gap-4 text-xs">
                <div class="space-y-1">
                    <p class="font-medium tracking-wide">Pemberitahuan Cookie & Pengalaman Belanja</p>
                    <p class="text-[11px] text-neutral-400 leading-relaxed">
                        Kami menggunakan cookie dan analitik anonim (GA4 & Meta Pixel) untuk meningkatkan kenyamanan navigasi dan rekomendasi produk pilihan.
                        Baca <a href="{{ url('/kebijakan-privasi') }}" class="underline hover:text-white">Kebijakan Privasi</a> kami.
                    </p>
                </div>
                <div class="flex items-center gap-3 shrink-0">
                    <button 
                        type="button" 
                        @click="decline()" 
                        class="px-4 py-2 border border-neutral-600 text-neutral-300 hover:text-white text-[11px] uppercase tracking-wider transition-colors"
                    >
                        Tolak
                    </button>
                    <button 
                        type="button" 
                        @click="accept()" 
                        class="px-5 py-2 bg-paper text-obsidian font-semibold text-[11px] uppercase tracking-wider hover:bg-neutral-200 transition-colors shadow-sm"
                    >
                        Setujui
                    </button>
                </div>
            </div>
        </div>

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
