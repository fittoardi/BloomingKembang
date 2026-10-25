<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'BloomingKembang — Koleksi Hijab Elegan & Bersahaja' }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}?v=3">
        <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('favicon.png') }}?v=3">
        <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=3">

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
    <body class="bg-storefront-ambient text-obsidian min-h-screen flex flex-col font-sans antialiased selection:bg-blush-200 selection:text-blush-900 relative overflow-x-hidden">
        <!-- Ambient Decorative Green & Pink Glow Background Orbs -->
        <div class="fixed top-[-10%] left-[-10%] w-[32rem] sm:w-[42rem] h-[32rem] sm:h-[42rem] bg-blush-200/35 rounded-full blur-[100px] pointer-events-none -z-10 transform-gpu"></div>
        <div class="fixed top-[5%] right-[-10%] w-[30rem] sm:w-[38rem] h-[30rem] sm:h-[38rem] bg-sage-200/40 rounded-full blur-[100px] pointer-events-none -z-10 transform-gpu"></div>
        <div class="fixed top-[45%] left-[10%] w-[28rem] sm:w-[36rem] h-[28rem] sm:h-[36rem] bg-sage-100/40 rounded-full blur-[110px] pointer-events-none -z-10 transform-gpu"></div>
        <div class="fixed bottom-[-10%] right-[-5%] w-[34rem] sm:w-[44rem] h-[34rem] sm:h-[44rem] bg-blush-200/30 rounded-full blur-[120px] pointer-events-none -z-10 transform-gpu"></div>

        <!-- Top Announcement Banner -->
        @php
            $globalTopBanner = \App\Models\Banner::where('is_active', true)
                ->where('position', 'top_bar')
                ->where(fn($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
                ->where(fn($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>=', now()))
                ->orderBy('sort_order')
                ->first();
        @endphp
        @if($globalTopBanner)
            <div class="bg-gradient-to-r from-sage-900 via-stone-900 to-blush-900 text-paper py-2 px-4 text-center text-xs tracking-wider uppercase font-medium border-b border-sage-800/40">
                @if($globalTopBanner->link_url)
                    <a href="{{ $globalTopBanner->link_url }}" class="hover:underline flex items-center justify-center gap-2">
                        <span class="inline-block w-1.5 h-1.5 rounded-full bg-sage-300"></span>
                        <span>{{ $globalTopBanner->title }}</span>
                        @if($globalTopBanner->subtitle) <span class="opacity-80 hidden sm:inline">&bull; {{ $globalTopBanner->subtitle }}</span> @endif
                        <span class="text-blush-200">&rarr;</span>
                    </a>
                @else
                    <span class="flex items-center justify-center gap-2">
                        <span class="inline-block w-1.5 h-1.5 rounded-full bg-sage-300"></span>
                        <span>{{ $globalTopBanner->title }}</span>
                        @if($globalTopBanner->subtitle) <span class="opacity-80 hidden sm:inline">&bull; {{ $globalTopBanner->subtitle }}</span> @endif
                    </span>
                @endif
            </div>
        @else
            <div class="bg-gradient-to-r from-sage-900 via-stone-900 to-blush-900 text-stone-100 py-2 px-4 text-center text-xs tracking-wider uppercase font-medium border-b border-sage-800/40">
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-sage-300 mr-2"></span>
                Gratis Ongkir Seluruh Pulau Jawa untuk Pembelian di Atas Rp 150.000
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-blush-300 ml-2"></span>
            </div>
        @endif

        <!-- Minimal Header Navigation -->
        <header x-data class="border-b border-blush-200/60 bg-white/85 backdrop-blur-md sticky top-0 z-40 transition-colors shadow-[0_2px_12px_rgba(78,103,78,0.03)]">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <!-- Left Nav Items -->
                <nav class="hidden md:flex items-center space-x-8 text-xs uppercase tracking-widest">
                    <a href="{{ url('/') }}" class="hover:text-bloom transition-colors {{ request()->is('/') ? 'text-botanic font-semibold border-b-2 border-botanic pb-1' : 'text-stone-700' }}">Beranda</a>
                    <a href="{{ url('/koleksi') }}" class="hover:text-bloom transition-colors {{ request()->is('koleksi*') ? 'text-botanic font-semibold border-b-2 border-botanic pb-1' : 'text-stone-700' }}">Koleksi</a>
                    <a href="{{ url('/tentang-kami') }}" class="hover:text-bloom transition-colors {{ request()->is('tentang-kami') ? 'text-botanic font-semibold border-b-2 border-botanic pb-1' : 'text-stone-700' }}">Tentang</a>
                </nav>

                <!-- Center Brand Colophon -->
                <div class="text-center">
                    <a href="{{ url('/') }}" class="group inline-flex items-center gap-2 text-base sm:text-lg uppercase tracking-[0.18em]">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7 group-hover:rotate-12 transition-transform duration-300 flex-shrink-0" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="24" cy="24" r="22" fill="#FDF7F8" stroke="#B8546B" stroke-width="1.5"/>
                            <path d="M24 9 C26.5 15, 31 18, 31 23 C31 27.5, 27.5 31, 24 31 C20.5 31, 17 27.5, 17 23 C17 18, 21.5 15, 24 9 Z" fill="#B8546B"/>
                            <path d="M13 20 C18 18.5, 22 22, 23 26 C20 29, 16 29, 13.5 26.5 C11.5 24.5, 11.5 21.5, 13 20 Z" fill="#D48B9B"/>
                            <path d="M35 20 C30 18.5, 26 22, 25 26 C28 29, 32 29, 34.5 26.5 C36.5 24.5, 36.5 21.5, 35 20 Z" fill="#D48B9B"/>
                            <circle cx="24" cy="23" r="2.8" fill="#FFF8F6" stroke="#4E674E" stroke-width="1.2"/>
                            <path d="M24 31 C24 36, 21.5 39, 18 40 C20.5 37, 22.5 35, 24 31 Z" fill="#4E674E"/>
                            <path d="M24 31 C24 36, 26.5 39, 30 40 C27.5 37, 25.5 35, 24 31 Z" fill="#6A856A"/>
                        </svg>
                        <span class="inline-flex items-center gap-0.5">
                            <span class="font-serif tracking-normal text-obsidian font-bold">Blooming</span>
                            <span class="font-serif italic font-normal text-bloom tracking-normal">Kembang</span>
                            <span class="w-1.5 h-1.5 rounded-full bg-botanic inline-block ml-1 group-hover:scale-125 transition-transform"></span>
                        </span>
                    </a>
                </div>

                <!-- Right Utility Links -->
                <div class="flex items-center space-x-6 text-xs uppercase tracking-widest">
                    @auth
                        @if(auth()->user()->hasAnyRole(['super_admin', 'admin', 'warehouse_staff']))
                            <a href="{{ url('/admin') }}" class="link-underlined text-botanic font-medium">Panel Admin</a>
                        @endif
                        <a href="{{ route('account.dashboard') }}" class="hover:text-bloom transition-colors text-stone-700">Akun Saya</a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="hover:text-bloom uppercase tracking-widest transition-colors text-stone-500 cursor-pointer">Keluar</button>
                        </form>
                        <button 
                            type="button" 
                            @click="$dispatch('open-cart')" 
                            onclick="window.dispatchEvent(new CustomEvent('open-cart'))"
                            class="hover:text-bloom transition-colors uppercase tracking-widest flex items-center space-x-1.5 cursor-pointer text-stone-800"
                        >
                            <span>Keranjang</span>
                            <span class="font-mono text-[10px] font-semibold px-2 py-0.5 rounded-full bg-blush-100 text-blush-800 border border-blush-200">
                                {{ app(\App\Services\CartService::class)->getCount() }}
                            </span>
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="hover:text-bloom transition-colors text-stone-700">Masuk</a>
                        <a href="{{ route('register') }}" class="px-3 py-1 rounded-full border border-blush-300 text-bloom hover:bg-blush-50 transition-colors font-medium">Daftar</a>
                    @endauth
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
        <footer class="border-t border-sage-200/60 bg-gradient-to-b from-white/80 via-sage-50/40 to-blush-50/50 backdrop-blur-xs mt-24 py-16 text-xs text-obsidian">
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
