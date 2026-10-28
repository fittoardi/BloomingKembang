<x-storefront-layout title="BloomingKembang — Koleksi Hijab Elegan & Bersahaja">
    @php
        $heroBanners = \App\Models\Banner::where('is_active', true)
            ->where('position', 'hero')
            ->where(fn($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>=', now()))
            ->orderBy('sort_order')
            ->get();

        $defaultSlides = [
            [
                'title' => 'Sentuhan Lembut & Elegan Hijab Pilihan',
                'subtitle' => 'Ditenun dari serat voal ultrafine dan ceruty babydoll pilihan. Lembut bersahaja, tegak di dahi, dan anggun dalam setiap momen.',
                'image' => 'images/home/hero-portrait.jpg',
                'link_url' => '/koleksi',
                'tag' => 'Koleksi Signature',
                'badge' => 'Voal Ultrafine Turkish',
                'cta_text' => 'Jelajahi Koleksi',
            ],
            [
                'title' => 'Pashmina Silk Shimmer & Ceruty Flowy',
                'subtitle' => 'Kilau lembut mewah yang memikat dengan drape kain jatuh sempurna. Sangat anggun untuk resepsi dan momen istimewa.',
                'image' => 'images/products/pashmina-silk.jpg',
                'link_url' => '/koleksi/pashmina',
                'tag' => 'Shimmer & Glow',
                'badge' => 'Silk Shimmer',
                'cta_text' => 'Lihat Pashmina',
            ],
            [
                'title' => 'Voal Miracle & Segi Empat Laser Cut',
                'subtitle' => 'Potongan laser cut presisi dengan tekstur kain anti kusut. Pilihan tepat untuk kenyamanan seharian penuh percaya diri.',
                'image' => 'images/products/voal-signature.jpg',
                'link_url' => '/koleksi/hijab-segi-empat',
                'tag' => 'Best Seller Voal',
                'badge' => 'Laser Cut Edge',
                'cta_text' => 'Beli Voal Miracle',
            ],
            [
                'title' => 'Bergo Daily & Khimar Soft Pet Series',
                'subtitle' => 'Praktis, langsung pakai, dan sejuk di cuaca tropis. Jahit butik tepi halus dengan ketahanan bahan tinggi.',
                'image' => 'images/products/bergo-daily.jpg',
                'link_url' => '/koleksi/bergo-daily',
                'tag' => 'Daily Practical Wear',
                'badge' => 'Instant Bergo',
                'cta_text' => 'Lihat Bergo Daily',
            ],
            [
                'title' => 'Koleksi Syar\'i & Elegan 2026',
                'subtitle' => 'Ditenun dari serat voal ultrafine dan ceruty babydoll pilihan. Sempurna menemani ibadah dan aktivitas harian Anda.',
                'image' => 'images/home/hero-editorial.jpg',
                'link_url' => '/koleksi',
                'tag' => 'Editorial Syar\'i',
                'badge' => 'Coverage & Comfort',
                'cta_text' => 'Temukan Koleksi',
            ],
        ];

        $slides = [];
        if ($heroBanners->isNotEmpty()) {
            foreach ($heroBanners as $idx => $banner) {
                $preset = $defaultSlides[$idx % count($defaultSlides)];
                $slides[] = [
                    'id' => $banner->id,
                    'title' => $banner->title,
                    'subtitle' => $banner->subtitle ?? $preset['subtitle'],
                    'image' => $banner->image ?: $preset['image'],
                    'link_url' => $banner->link_url ?: $preset['link_url'],
                    'tag' => $preset['tag'],
                    'badge' => $preset['badge'],
                    'cta_text' => $preset['cta_text'],
                ];
            }
        } else {
            $slides = $defaultSlides;
        }

        $featuredProducts = \App\Models\Product::with(['category', 'variants'])
            ->where('status', \App\Enums\ProductStatus::Active)
            ->orderBy('sold_count', 'desc')
            ->take(6)
            ->get();
    @endphp

    <!-- 1. HERO CAROUSEL: 100% WIDTH, 600PX HEIGHT (AUTOPLAY OTOMATIS) -->
    <section 
        x-data="{
            current: 0,
            total: {{ count($slides) }},
            intervalMs: 5000,
            timer: null,
            touchStartX: 0,
            touchEndX: 0,
            init() {
                this.startAutoplay();
            },
            startAutoplay() {
                this.clearTimer();
                this.timer = setInterval(() => {
                    this.next();
                }, this.intervalMs);
            },
            clearTimer() {
                if (this.timer) {
                    clearInterval(this.timer);
                    this.timer = null;
                }
            },
            next() {
                this.current = (this.current + 1) % this.total;
            },
            prev() {
                this.current = (this.current - 1 + this.total) % this.total;
            },
            goTo(index) {
                this.current = index;
                this.startAutoplay();
            },
            handleTouchStart(e) {
                this.touchStartX = e.changedTouches[0].screenX;
            },
            handleTouchEnd(e) {
                this.touchEndX = e.changedTouches[0].screenX;
                if (this.touchStartX - this.touchEndX > 45) {
                    this.next();
                    this.startAutoplay();
                } else if (this.touchEndX - this.touchStartX > 45) {
                    this.prev();
                    this.startAutoplay();
                }
            }
        }"
        @mouseenter="clearTimer()"
        @mouseleave="startAutoplay()"
        @focusin="clearTimer()"
        @focusout="startAutoplay()"
        @keydown.arrow-right="next(); startAutoplay()"
        @keydown.arrow-left="prev(); startAutoplay()"
        @touchstart="handleTouchStart($event)"
        @touchend="handleTouchEnd($event)"
        tabindex="0"
        class="w-full h-[600px] relative overflow-hidden bg-stone-900 select-none focus:outline-none"
        aria-roledescription="carousel"
        aria-label="Koleksi Unggulan BloomingKembang"
    >
        <!-- Slide Track Stack (600px Height) -->
        <div class="relative w-full h-[600px]">
            @foreach ($slides as $index => $slide)
                <div 
                    x-show="current === {{ $index }}"
                    x-transition:enter="transition-opacity ease-out duration-700"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition-opacity ease-in duration-500"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="absolute inset-0 w-full h-[600px] flex items-center"
                >
                    <!-- Background Photo (600px Height, Object Cover) -->
                    <img 
                        src="{{ asset($slide['image']) }}" 
                        alt="{{ $slide['title'] }}" 
                        class="w-full h-[600px] object-cover object-center absolute inset-0"
                        loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                    />

                    <!-- Atmospheric Gradient Overlay for Contrast -->
                    <div class="absolute inset-0 bg-gradient-to-r from-stone-950/92 via-stone-950/60 to-stone-950/25 sm:from-stone-950/95 sm:via-stone-950/55 sm:to-transparent pointer-events-none"></div>

                    <!-- Slide Content -->
                    <div class="relative z-10 max-w-7xl mx-auto px-6 sm:px-12 w-full flex flex-col justify-center text-white py-12">
                        <div class="max-w-3xl space-y-4">
                            <!-- Category Tag Pill -->
                            <div>
                                <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-stone-900/60 backdrop-blur-md text-stone-200 text-[11px] font-sans uppercase tracking-[0.2em] font-medium border border-white/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-bloom"></span>
                                    {{ $slide['tag'] }}
                                </span>
                            </div>

                            <!-- Title (Spacious & Elegant for 600px Height) -->
                            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-serif font-normal uppercase tracking-wider text-white leading-tight drop-shadow-md">
                                {{ $slide['title'] }}
                            </h1>

                            <!-- Subtitle -->
                            <p class="text-sm sm:text-base text-stone-100 max-w-2xl font-normal leading-relaxed drop-shadow-xs">
                                {{ $slide['subtitle'] }}
                            </p>

                            <!-- CTAs & Badge -->
                            <div class="pt-4 flex flex-wrap items-center gap-4">
                                <a 
                                    href="{{ url($slide['link_url']) }}" 
                                    class="px-8 py-3.5 bg-bloom hover:bg-blush-700 text-white text-xs uppercase tracking-widest font-semibold transition-all rounded-xs shadow-md inline-flex items-center gap-2 group"
                                >
                                    <span>{{ $slide['cta_text'] }}</span>
                                    <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
                                </a>

                                <a 
                                    href="{{ url('/koleksi') }}" 
                                    class="px-7 py-3.5 bg-botanic/90 hover:bg-botanic text-white border border-white/20 text-xs uppercase tracking-widest font-semibold transition-all rounded-xs backdrop-blur-xs"
                                >
                                    Semua Koleksi
                                </a>

                                <span class="hidden sm:inline-flex items-center text-[11px] font-medium tracking-[0.16em] text-stone-200 uppercase px-3.5 py-1.5 bg-black/40 rounded-full backdrop-blur-xs border border-white/15">
                                    {{ $slide['badge'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Bottom Pill Controls: Dots & Slide Counter -->
        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex items-center gap-3 sm:gap-4 px-4 sm:px-5 py-2.5 rounded-full bg-stone-950/70 backdrop-blur-md text-white border border-white/20 text-xs z-20 shadow-xl">
            <!-- Indicator Dots (Active in Pink Bloom) -->
            <div class="flex items-center gap-2">
                @foreach ($slides as $index => $slide)
                    <button 
                        type="button"
                        @click="goTo({{ $index }})"
                        class="h-2.5 rounded-full transition-all duration-300 focus:outline-none cursor-pointer"
                        :class="current === {{ $index }} ? 'w-8 bg-bloom ring-2 ring-bloom/40' : 'w-2.5 bg-white/50 hover:bg-white/90'"
                        aria-label="Pilih Slide {{ $index + 1 }}: {{ $slide['title'] }}"
                    ></button>
                @endforeach
            </div>

            <div class="w-px h-3.5 bg-white/25"></div>

            <!-- Slide Counter -->
            <span class="font-mono text-xs text-stone-200 tracking-wider">
                <span class="text-white font-bold" x-text="String(current + 1).padStart(2, '0')">01</span> / 
                <span x-text="String(total).padStart(2, '0')">{{ sprintf('%02d', count($slides)) }}</span>
            </span>
        </div>
    </section>

    <!-- 2. CIRCULAR CATEGORY SHORTCUTS (HIJAU BOTANIC & PINK BLOOM) -->
    <section class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10 border-b border-stone-200/80">
        <div class="grid grid-cols-3 sm:grid-cols-6 gap-4 sm:gap-6 text-center">
            <!-- 1. Segi Empat -->
            <a href="{{ url('/koleksi/hijab-segi-empat') }}" class="group flex flex-col items-center space-y-2">
                <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full overflow-hidden border-2 border-stone-200 group-hover:border-bloom shadow-xs group-hover:shadow-md transition-all duration-300 p-0.5 bg-white">
                    <img src="{{ asset('images/home/cat-segi-empat.jpg') }}" alt="Hijab Segi Empat" class="w-full h-full object-cover rounded-full group-hover:scale-110 transition-transform duration-500" />
                </div>
                <span class="text-xs font-semibold text-stone-800 uppercase tracking-wider group-hover:text-botanic transition-colors">Segi Empat</span>
            </a>

            <!-- 2. Pashmina -->
            <a href="{{ url('/koleksi/pashmina') }}" class="group flex flex-col items-center space-y-2">
                <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full overflow-hidden border-2 border-stone-200 group-hover:border-bloom shadow-xs group-hover:shadow-md transition-all duration-300 p-0.5 bg-white">
                    <img src="{{ asset('images/home/cat-pashmina.jpg') }}" alt="Pashmina Flowy" class="w-full h-full object-cover rounded-full group-hover:scale-110 transition-transform duration-500" />
                </div>
                <span class="text-xs font-semibold text-stone-800 uppercase tracking-wider group-hover:text-botanic transition-colors">Pashmina</span>
            </a>

            <!-- 3. Bergo Daily -->
            <a href="{{ url('/koleksi/bergo-daily') }}" class="group flex flex-col items-center space-y-2">
                <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full overflow-hidden border-2 border-stone-200 group-hover:border-bloom shadow-xs group-hover:shadow-md transition-all duration-300 p-0.5 bg-white">
                    <img src="{{ asset('images/products/bergo-daily.jpg') }}" alt="Bergo Daily" class="w-full h-full object-cover rounded-full group-hover:scale-110 transition-transform duration-500" />
                </div>
                <span class="text-xs font-semibold text-stone-800 uppercase tracking-wider group-hover:text-botanic transition-colors">Bergo Daily</span>
            </a>

            <!-- 4. Khimar Syar'i -->
            <a href="{{ url('/koleksi') }}" class="group flex flex-col items-center space-y-2">
                <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full overflow-hidden border-2 border-stone-200 group-hover:border-bloom shadow-xs group-hover:shadow-md transition-all duration-300 p-0.5 bg-white">
                    <img src="{{ asset('images/products/khimar-syari.jpg') }}" alt="Khimar Syar'i" class="w-full h-full object-cover rounded-full group-hover:scale-110 transition-transform duration-500" />
                </div>
                <span class="text-xs font-semibold text-stone-800 uppercase tracking-wider group-hover:text-botanic transition-colors">Khimar Syar'i</span>
            </a>

            <!-- 5. Inner & Aksesoris -->
            <a href="{{ url('/koleksi/inner-dan-aksesoris') }}" class="group flex flex-col items-center space-y-2">
                <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full overflow-hidden border-2 border-stone-200 group-hover:border-bloom shadow-xs group-hover:shadow-md transition-all duration-300 p-0.5 bg-white">
                    <img src="{{ asset('images/home/cat-inner-aksesoris.jpg') }}" alt="Inner & Aksesoris" class="w-full h-full object-cover rounded-full group-hover:scale-110 transition-transform duration-500" />
                </div>
                <span class="text-xs font-semibold text-stone-800 uppercase tracking-wider group-hover:text-botanic transition-colors">Inner & Klip</span>
            </a>

            <!-- 6. Koleksi Pilihan -->
            <a href="{{ url('/koleksi') }}" class="group flex flex-col items-center space-y-2">
                <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full overflow-hidden border-2 border-stone-200 group-hover:border-bloom shadow-xs group-hover:shadow-md transition-all duration-300 p-0.5 bg-white">
                    <img src="{{ asset('images/home/elzatta-hero-loyalty.jpg') }}" alt="Koleksi Pilihan" class="w-full h-full object-cover rounded-full group-hover:scale-110 transition-transform duration-500" />
                </div>
                <span class="text-xs font-semibold text-stone-800 uppercase tracking-wider group-hover:text-botanic transition-colors">Koleksi Pilihan</span>
            </a>
        </div>
    </section>

    <!-- 3. SPOTLIGHT HERO PRODUCT: KEISHA VERSA (KOMBINASI BOTANIC & BLOOM) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
        <div class="bg-gradient-to-br from-sage-50/60 via-white to-blush-50/50 border border-sage-200/90 rounded-2xl overflow-hidden shadow-xs p-6 sm:p-10 lg:p-12">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                <!-- Left: Lookbook Rack Image -->
                <div class="lg:col-span-6">
                    <div class="relative rounded-xl overflow-hidden border border-sage-200 shadow-sm group">
                        <img 
                            src="{{ asset('images/home/keisha-rack.jpg') }}" 
                            alt="Keisha Versa Voal Rack Collection" 
                            class="w-full h-[360px] sm:h-[440px] object-cover group-hover:scale-105 transition-transform duration-700" 
                        />
                        <div class="absolute inset-0 bg-gradient-to-t from-stone-950/75 via-transparent to-transparent"></div>
                        <div class="absolute bottom-5 left-5 right-5 text-white">
                            <span class="inline-block px-3 py-1 rounded-full bg-stone-900/80 backdrop-blur-xs text-stone-200 text-[10px] uppercase tracking-[0.18em] font-medium mb-2 border border-white/20">
                                15 Pilihan Warna Eksklusif
                            </span>
                            <h3 class="text-lg font-serif uppercase tracking-wider">Scarf Rack Atelier 2026</h3>
                            <p class="text-xs text-stone-200 mt-0.5">Draping rapi dan tekstur voal ultrafine yang sejuk bersahaja.</p>
                        </div>
                    </div>
                </div>

                <!-- Right: Product Narrative & Purchase Details -->
                <div class="lg:col-span-6 space-y-6">
                    <div>
                        <span class="text-[11px] uppercase tracking-[0.2em] text-bloom font-semibold block mb-1.5 flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-bloom"></span>
                            <span>Koleksi Signature Essential</span>
                        </span>
                        <h2 class="text-2xl sm:text-3xl font-serif uppercase tracking-wider text-stone-900 leading-snug">
                            Keisha Versa Voal Scarf
                        </h2>
                        <p class="text-xs sm:text-sm text-stone-600 mt-2 leading-relaxed">
                            Hijab voal berukuran 120 &times; 120 cm berkarakter jatuh alami, menutup dada dengan santun, anti letoy di dahi, dan tidak pengap untuk menemani aktivitas harian Anda.
                        </p>
                    </div>

                    <!-- Modesty & Fabric Features Atelier Grid -->
                    <div class="grid grid-cols-2 gap-3 pt-2 text-left">
                        <div class="p-3.5 bg-white/80 border border-sage-200/80 hover:border-botanic rounded-lg transition-colors shadow-2xs">
                            <span class="text-[11px] uppercase tracking-wider text-botanic font-semibold block">100% Ultrafine Voal</span>
                            <span class="text-xs text-stone-600 mt-0.5 block leading-normal">Serat bernapas, sejuk di cuaca panas</span>
                        </div>
                        <div class="p-3.5 bg-white/80 border border-sage-200/80 hover:border-bloom rounded-lg transition-colors shadow-2xs">
                            <span class="text-[11px] uppercase tracking-wider text-bloom font-semibold block">Laser Cut Precision</span>
                            <span class="text-xs text-stone-600 mt-0.5 block leading-normal">Tepi rapi tanpa benang bertiras</span>
                        </div>
                        <div class="p-3.5 bg-white/80 border border-sage-200/80 hover:border-botanic rounded-lg transition-colors shadow-2xs">
                            <span class="text-[11px] uppercase tracking-wider text-botanic font-semibold block">Tegak Sempurna</span>
                            <span class="text-xs text-stone-600 mt-0.5 block leading-normal">Mudah dibentuk & anti geser seharian</span>
                        </div>
                        <div class="p-3.5 bg-white/80 border border-sage-200/80 hover:border-bloom rounded-lg transition-colors shadow-2xs">
                            <span class="text-[11px] uppercase tracking-wider text-bloom font-semibold block">Coverage Modest</span>
                            <span class="text-xs text-stone-600 mt-0.5 block leading-normal">Lebih panjang menutup dada</span>
                        </div>
                    </div>

                    <!-- Color Swatches Preview Bar -->
                    <div class="space-y-2 pt-1">
                        <div class="flex items-center justify-between text-xs">
                            <span class="font-medium text-stone-700">Pilihan Warna Favorit:</span>
                            <span class="text-[11px] uppercase tracking-wider text-botanic font-semibold">15 Warna Lengkap</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="w-6 h-6 rounded-full border-2 border-white shadow-xs" style="background-color: #B87383;" title="726 Dusty Rose"></span>
                            <span class="w-6 h-6 rounded-full border-2 border-white shadow-xs" style="background-color: #7A8B78;" title="469 Olive Sage"></span>
                            <span class="w-6 h-6 rounded-full border-2 border-white shadow-xs" style="background-color: #6A1E2E;" title="670 Maroon Wine"></span>
                            <span class="w-6 h-6 rounded-full border-2 border-white shadow-xs" style="background-color: #D6B598;" title="901 Warm Nude"></span>
                            <span class="w-6 h-6 rounded-full border-2 border-white shadow-xs" style="background-color: #1D2A44;" title="516 Deep Navy"></span>
                            <span class="w-6 h-6 rounded-full border-2 border-white shadow-xs" style="background-color: #8A6B56;" title="312 Mocca Warm"></span>
                            <span class="text-xs text-stone-500 pl-1 font-medium">+9 lainnya</span>
                        </div>
                    </div>

                    <!-- Price & CTA Button -->
                    <div class="pt-4 border-t border-sage-200/80 flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <span class="text-[10px] uppercase tracking-widest text-stone-400 block">Harga Spesial</span>
                            <span class="text-2xl font-serif font-bold text-botanic">Rp 89.000</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <a 
                                href="{{ url('/koleksi/hijab-segi-empat') }}" 
                                class="px-8 py-3.5 bg-botanic hover:bg-sage-700 text-white text-xs uppercase tracking-widest font-semibold transition-colors rounded-xs shadow-xs inline-flex items-center gap-2"
                            >
                                <span>Beli Sekarang</span>
                                <span>&rarr;</span>
                            </a>
                            <a 
                                href="{{ url('/koleksi') }}" 
                                class="px-4 py-3.5 text-bloom hover:text-blush-800 text-xs uppercase tracking-wider font-semibold transition-colors underline"
                            >
                                Koleksi Lengkap
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. DESIGNED FOR EVERYDAY MODESTY (DUAL-TONE ACCENT CARDS) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        <div class="text-center max-w-2xl mx-auto mb-10 space-y-2">
            <span class="text-[11px] uppercase tracking-[0.2em] text-bloom font-semibold block flex items-center justify-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-botanic"></span>
                <span>Filosofi Keseharian Muslimah</span>
                <span class="w-1.5 h-1.5 rounded-full bg-bloom"></span>
            </span>
            <h2 class="text-2xl sm:text-3xl font-serif uppercase tracking-wider text-stone-900">
                DESIGNED FOR EVERYDAY MODESTY
            </h2>
            <p class="text-xs sm:text-sm text-stone-600 leading-relaxed font-normal">
                Keseimbangan sempurna antara keanggunan syar'i, kenyamanan serat alami, dan kepraktisan busana harian.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Card 1: Comfort & Breathability (Aksen Bloom Pink) -->
            <div class="group relative rounded-xl overflow-hidden border-2 border-blush-200/90 hover:border-bloom/70 shadow-sm aspect-[16/10] bg-stone-900 transition-all duration-300">
                <img 
                    src="{{ asset('images/home/modesty-group.jpg') }}" 
                    alt="Comfort & Breathability Collection" 
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 opacity-80" 
                />
                <div class="absolute inset-0 bg-gradient-to-t from-stone-950/85 via-stone-950/40 to-transparent"></div>
                <div class="absolute bottom-6 left-6 right-6 text-white space-y-2">
                    <span class="text-[11px] uppercase tracking-[0.2em] text-blush-300 font-semibold block">
                        Kesejukan & Sirkulasi Alami
                    </span>
                    <h3 class="text-xl font-serif uppercase tracking-wider">Comfort & Breathability</h3>
                    <p class="text-xs text-stone-200 line-clamp-2 max-w-md">
                        Menggunakan serat voal bernapas mikro yang menjaga sirkulasi udara tetap sejuk walau beraktivitas padat seharian.
                    </p>
                    <div class="pt-2">
                        <a href="{{ url('/koleksi/bergo-daily') }}" class="text-xs uppercase font-semibold tracking-wider text-blush-200 hover:text-white inline-flex items-center gap-1.5 transition-colors">
                            <span>Jelajahi Bergo & Daily Wear</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Card 2: Coverage & Syar'i Silhouette (Aksen Botanic Green) -->
            <div class="group relative rounded-xl overflow-hidden border-2 border-sage-200/90 hover:border-botanic/70 shadow-sm aspect-[16/10] bg-stone-900 transition-all duration-300">
                <img 
                    src="{{ asset('images/home/hero-editorial.jpg') }}" 
                    alt="Coverage & Syar'i Silhouette Collection" 
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 opacity-80" 
                />
                <div class="absolute inset-0 bg-gradient-to-t from-stone-950/85 via-stone-950/40 to-transparent"></div>
                <div class="absolute bottom-6 left-6 right-6 text-white space-y-2">
                    <span class="text-[11px] uppercase tracking-[0.2em] text-sage-300 font-semibold block">
                        Siluet Anggun & Syar'i
                    </span>
                    <h3 class="text-xl font-serif uppercase tracking-wider">Coverage & Syar'i Silhouette</h3>
                    <p class="text-xs text-stone-200 line-clamp-2 max-w-md">
                        Potongan syar'i anggun yang menutup dada dengan presisi, drape kain jatuh lembut, dan tidak menerawang.
                    </p>
                    <div class="pt-2">
                        <a href="{{ url('/koleksi/hijab-segi-empat') }}" class="text-xs uppercase font-semibold tracking-wider text-sage-200 hover:text-white inline-flex items-center gap-1.5 transition-colors">
                            <span>Jelajahi Hijab Segi Empat Syar'i</span>
                            <span>&rarr;</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 5. FIND YOUR SHADE (DUAL-TONE PALETTE CONTAINER) -->
    <section 
        x-data="{
            activeShade: { name: 'Dusty Rose', code: '726', hex: '#B87383', note: 'Anggun & Manis Bersahaja' },
            shades: [
                { name: 'Dusty Rose', code: '726', hex: '#B87383', note: 'Anggun & Manis Bersahaja' },
                { name: 'Olive Sage', code: '469', hex: '#7A8B78', note: 'Teduh, Sejuk & Menenangkan' },
                { name: 'Maroon Wine', code: '670', hex: '#6A1E2E', note: 'Mewah, Elegan & Berkarakter' },
                { name: 'Warm Nude', code: '901', hex: '#D6B598', note: 'Netral Cocok Segala Outfit' },
                { name: 'Deep Navy', code: '516', hex: '#1D2A44', note: 'Klasik Formal & Percaya Diri' },
                { name: 'Mocca Warm', code: '312', hex: '#8A6B56', note: 'Earthy Hangat & Santai' },
                { name: 'Mustard Gold', code: '405', hex: '#B8860B', note: 'Ceria & Energik Bercahaya' },
                { name: 'Slate Charcoal', code: '108', hex: '#3C4247', note: 'Tegas, Minimalis & Monokrom' },
                { name: 'Terracotta', code: '815', hex: '#A0522D', note: 'Eksotis & Berjiwa Hangat' },
                { name: 'Baby Lilac', code: '224', hex: '#9F8AA4', note: 'Lembut, Feminin & Syahdu' }
            ]
        }"
        class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12"
    >
        <div class="bg-gradient-to-r from-sage-50/40 via-white to-blush-50/40 border border-sage-200/90 rounded-2xl p-6 sm:p-10 shadow-xs">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6 pb-6 border-b border-sage-200/80">
                <div>
                    <span class="text-[11px] uppercase tracking-[0.2em] text-botanic font-semibold block mb-1 flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-botanic"></span>
                        <span>Palet Warna 2026</span>
                    </span>
                    <h2 class="text-xl sm:text-2xl font-serif uppercase tracking-wider text-stone-900">
                        FIND YOUR SHADE
                    </h2>
                    <p class="text-xs text-stone-600 mt-1">
                        Pilih nada warna yang selaras dengan karakter dan undertone kulit Anda.
                    </p>
                </div>

                <!-- Active Selected Badge -->
                <div class="flex items-center gap-3 bg-white/90 px-4 py-2.5 rounded-xl border border-blush-200/80 shadow-xs self-start md:self-auto">
                    <span class="w-7 h-7 rounded-full border border-stone-300 shadow-xs transition-colors" :style="'background-color: ' + activeShade.hex"></span>
                    <div>
                        <span class="text-xs font-semibold text-stone-900 block" x-text="activeShade.name + ' (' + activeShade.code + ')'">Dusty Rose (726)</span>
                        <span class="text-[11px] text-stone-500 font-sans block" x-text="activeShade.note">Anggun & Manis Bersahaja</span>
                    </div>
                </div>
            </div>

            <!-- Swatches Strip -->
            <div class="pt-6">
                <div class="grid grid-cols-5 sm:grid-cols-10 gap-3 text-center">
                    <template x-for="shade in shades" :key="shade.code">
                        <button 
                            type="button"
                            @click="activeShade = shade"
                            class="group flex flex-col items-center space-y-1.5 focus:outline-none cursor-pointer"
                        >
                            <span 
                                class="w-10 h-10 sm:w-12 sm:h-12 rounded-full border-2 transition-all duration-300 shadow-2xs group-hover:scale-110 flex items-center justify-center"
                                :class="activeShade.code === shade.code ? 'border-bloom ring-2 ring-bloom/30 scale-105' : 'border-white hover:border-stone-300'"
                                :style="'background-color: ' + shade.hex"
                            >
                                <svg x-show="activeShade.code === shade.code" class="w-4 h-4 text-white drop-shadow-xs" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </span>
                            <span class="text-[10px] text-stone-600 font-medium truncate max-w-[65px]" x-text="shade.code"></span>
                        </button>
                    </template>
                </div>

                <div class="text-center pt-6">
                    <a 
                        :href="'{{ url('/koleksi') }}?cari=' + encodeURIComponent(activeShade.name)" 
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-botanic hover:bg-sage-700 text-white text-xs uppercase tracking-widest font-semibold transition-colors rounded-xs shadow-xs"
                    >
                        <span>Cari Koleksi Warna Ini</span>
                        <span>&rarr;</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- 6. CURATED BEST SELLERS CATALOG GRID (KOMBINASI BOTANIC & BLOOM) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="flex items-center justify-between mb-8 pb-3 border-b border-sage-200/80">
            <div>
                <span class="text-[11px] uppercase tracking-[0.2em] text-bloom font-semibold block mb-0.5 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-bloom"></span>
                    <span>Paling Banyak Dipilih</span>
                </span>
                <h2 class="text-xl sm:text-2xl font-serif uppercase tracking-wider text-stone-900">
                    Koleksi Terlaris
                </h2>
            </div>
            <a href="{{ url('/koleksi') }}" class="text-xs uppercase tracking-wider text-botanic font-semibold hover:text-bloom hover:underline flex items-center gap-1.5 transition-colors">
                <span>Lihat Semua Katalog</span>
                <span>&rarr;</span>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
            @forelse($featuredProducts as $product)
                <div class="group flex flex-col justify-between border border-sage-200/80 bg-white p-4 hover:border-botanic hover:shadow-md transition-all rounded-lg">
                    <!-- Photo Frame -->
                    <div class="aspect-[3/4] bg-stone-50 relative overflow-hidden flex items-center justify-center border border-sage-100 rounded-md">
                        @php
                            $imageUrl = $product->getFirstMediaUrl('images', 'preview') 
                                ?: ($product->getFirstMediaUrl('images') ?: asset('images/product-placeholder.jpg'));
                        @endphp
                        <img 
                            src="{{ $imageUrl }}" 
                            alt="{{ $product->name }}" 
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            loading="lazy"
                        />

                        @if($product->sold_count > 0)
                            <span class="absolute top-3 left-3 bg-bloom/95 backdrop-blur-xs text-white px-2.5 py-0.5 text-[9px] uppercase tracking-wider rounded-full shadow-2xs font-medium">
                                {{ $product->sold_count }} Terjual
                            </span>
                        @endif
                    </div>

                    <!-- Caption & Details -->
                    <div class="mt-4 space-y-2">
                        <span class="text-[10px] uppercase tracking-[0.15em] font-medium text-stone-400 block">{{ $product->category->name ?? 'Hijab' }}</span>
                        <div class="flex items-center justify-between text-xs">
                            <h3 class="font-semibold text-stone-900 uppercase tracking-wide group-hover:text-bloom transition-colors">
                                <a href="{{ url('/produk/' . $product->slug) }}">{{ $product->name }}</a>
                            </h3>
                            @if($firstVariant = $product->variants->first())
                                <span class="text-xs font-bold text-botanic font-mono">Rp {{ number_format($firstVariant->price, 0, ',', '.') }}</span>
                            @endif
                        </div>
                        <p class="text-[11px] text-stone-500 line-clamp-1 leading-relaxed">{{ $product->short_description }}</p>
                        
                        <!-- Swatches Preview -->
                        <div class="flex items-center space-x-1.5 pt-1">
                            @foreach($product->variants->take(5) as $variant)
                                @if($variant->color_hex)
                                    <span class="w-3.5 h-3.5 border border-stone-300 rounded-full inline-block shadow-2xs" style="background-color: {{ $variant->color_hex }};" title="{{ $variant->color }}"></span>
                                @endif
                            @endforeach
                            @if($product->variants->count() > 5)
                                <span class="text-[10px] text-stone-400 pl-1 font-medium">+{{ $product->variants->count() - 5 }}</span>
                            @endif
                        </div>

                        <div class="pt-3 border-t border-sage-100 flex items-center justify-between text-[11px]">
                            <a href="{{ url('/produk/' . $product->slug) }}" class="text-botanic font-semibold hover:text-bloom hover:underline uppercase tracking-wider text-[11px] inline-flex items-center gap-1 transition-colors">
                                <span>Detail Produk</span>
                                <span>&rarr;</span>
                            </a>
                            <span class="text-[10px] text-botanic/80 font-medium bg-sage-50 px-2 py-0.5 rounded-full border border-sage-200/60">100% Original</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 py-16 text-center text-xs text-stone-500 border border-sage-200 p-8 rounded-lg bg-white">
                    Belum ada produk aktif yang ditampilkan.
                </div>
            @endforelse
        </div>
    </section>

    <!-- 7. 4-PILLAR VALUE PROPOSITIONS (HARMONISASI ALTERNASI BOTANIC & BLOOM) -->
    <section class="border-y border-sage-200/80 bg-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- 1. All-Channel Shopping (Botanic Green Card) -->
                <div class="flex items-start gap-4 p-4 bg-sage-50/80 border border-sage-200/90 rounded-lg hover:border-botanic transition-colors shadow-2xs">
                    <div class="w-10 h-10 rounded-full bg-sage-100 text-botanic flex items-center justify-center flex-shrink-0 border border-sage-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-semibold uppercase tracking-wider text-botanic">Seluruh Saluran Belanja</h4>
                        <p class="text-[11px] text-stone-600 mt-1 leading-relaxed">Pilihan berbelanja mudah lewat website resmi maupun store butik kami.</p>
                    </div>
                </div>

                <!-- 2. Loyalty Point Reward (Bloom Pink Card) -->
                <div class="flex items-start gap-4 p-4 bg-blush-50/80 border border-blush-200/90 rounded-lg hover:border-bloom transition-colors shadow-2xs">
                    <div class="w-10 h-10 rounded-full bg-blush-100 text-bloom flex items-center justify-center flex-shrink-0 border border-blush-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-semibold uppercase tracking-wider text-bloom">Loyalty Point Reward</h4>
                        <p class="text-[11px] text-stone-600 mt-1 leading-relaxed">Kumpulkan poin di setiap transaksi dan tukar kupon potongan harga.</p>
                    </div>
                </div>

                <!-- 3. Pengemasan Eksklusif (Botanic Green Card) -->
                <div class="flex items-start gap-4 p-4 bg-sage-50/80 border border-sage-200/90 rounded-lg hover:border-botanic transition-colors shadow-2xs">
                    <div class="w-10 h-10 rounded-full bg-sage-100 text-botanic flex items-center justify-center flex-shrink-0 border border-sage-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-semibold uppercase tracking-wider text-botanic">Pengemasan Aman & Rapi</h4>
                        <p class="text-[11px] text-stone-600 mt-1 leading-relaxed">Setiap produk dibungkus rapi dengan proteksi butik standar premium.</p>
                    </div>
                </div>

                <!-- 4. Garansi Retur 7 Hari (Bloom Pink Card) -->
                <div class="flex items-start gap-4 p-4 bg-blush-50/80 border border-blush-200/90 rounded-lg hover:border-bloom transition-colors shadow-2xs">
                    <div class="w-10 h-10 rounded-full bg-blush-100 text-bloom flex items-center justify-center flex-shrink-0 border border-blush-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-semibold uppercase tracking-wider text-bloom">Garansi Retur 7 Hari</h4>
                        <p class="text-[11px] text-stone-600 mt-1 leading-relaxed">Jaminan tukar produk gratis jika cacat jahitan atau beda pesanan.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 8. TESTIMONIALS / SUARA PELANGGAN (KOMBINASI BOTANIC & BLOOM) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center max-w-2xl mx-auto mb-12 space-y-2">
            <span class="text-[11px] uppercase tracking-[0.2em] text-botanic font-semibold block flex items-center justify-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-bloom"></span>
                <span>Suara Sahabat BloomingKembang</span>
            </span>
            <h2 class="text-2xl sm:text-3xl font-serif uppercase tracking-wider text-stone-900">
                Dipercaya oleh Muslimah Indonesia
            </h2>
            <p class="text-xs sm:text-sm text-stone-600 leading-relaxed font-normal">
                Kesan tulus dari para sahabat yang telah merasakan kenyamanan serat voal ultrafine dan jahitan butik presisi kami.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Review 1 (Aksen Sage Botanic) -->
            <div class="p-6 border border-sage-200/90 bg-white rounded-lg shadow-2xs hover:border-botanic transition-colors space-y-4 flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="flex items-center space-x-1 text-amber-500">
                        @for($i = 0; $i < 5; $i++)
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                        @endfor
                    </div>
                    <p class="text-xs text-stone-700 leading-relaxed italic">
                        "Bahan voal ultrafine-nya benar-benar tegak di dahi dan tidak mudah kusut walau dipakai seharian beraktivitas di luar ruangan. Jahitan tepinya sangat rapi standar butik. Sudah langganan 3 warna!"
                    </p>
                </div>
                <div class="pt-4 border-t border-sage-100 space-y-0.5">
                    <span class="text-xs font-semibold text-stone-900 block">Annisa Rahmawati</span>
                    <span class="text-[11px] text-stone-500 font-sans block">Bandung &bull; Pembeli Terverifikasi</span>
                    <span class="text-[10px] text-botanic font-semibold block mt-1">Koleksi: Voal Ultrafine Signature - Soft Sage</span>
                </div>
            </div>

            <!-- Review 2 (Aksen Blush Bloom) -->
            <div class="p-6 border border-blush-200/90 bg-white rounded-lg shadow-2xs hover:border-bloom transition-colors space-y-4 flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="flex items-center space-x-1 text-amber-500">
                        @for($i = 0; $i < 5; $i++)
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                        @endfor
                    </div>
                    <p class="text-xs text-stone-700 leading-relaxed italic">
                        "Pashmina silk-nya jatuh sangat anggun, tidak licin berlebihan, dan warnanya persis seperti di foto katalog. Kemasan amplop eksklusifnya memberi pengalaman unboxing yang sangat mewah."
                    </p>
                </div>
                <div class="pt-4 border-t border-blush-100 space-y-0.5">
                    <span class="text-xs font-semibold text-stone-900 block">Dian Savitri</span>
                    <span class="text-[11px] text-stone-500 font-sans block">Jakarta Selatan &bull; Pembeli Terverifikasi</span>
                    <span class="text-[10px] text-bloom font-semibold block mt-1">Koleksi: Pashmina Silk Premium - Rose Mauve</span>
                </div>
            </div>

            <!-- Review 3 (Aksen Sage Botanic) -->
            <div class="p-6 border border-sage-200/90 bg-white rounded-lg shadow-2xs hover:border-botanic transition-colors space-y-4 flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="flex items-center space-x-1 text-amber-500">
                        @for($i = 0; $i < 5; $i++)
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                        @endfor
                    </div>
                    <p class="text-xs text-stone-700 leading-relaxed italic">
                        "Bergo daily ini penyelamat saat harus keluar mendadak. Bahannya sejuk, pet-nya pas di muka tidak kaku. Pengiriman super kilat, sore pesan besok paginya sudah sampai di rumah."
                    </p>
                </div>
                <div class="pt-4 border-t border-sage-100 space-y-0.5">
                    <span class="text-xs font-semibold text-stone-900 block">Fatimah Azzahra</span>
                    <span class="text-[11px] text-stone-500 font-sans block">Surabaya &bull; Pembeli Terverifikasi</span>
                    <span class="text-[10px] text-botanic font-semibold block mt-1">Koleksi: Bergo Khimar Daily - Mocca Haze</span>
                </div>
            </div>
        </div>
    </section>

    <!-- 9. NEWSLETTER & ATELIER UPDATES (SOFT BOTANIC & BLOOM GRADIENT) -->
    <section class="border-t border-sage-200/80 bg-gradient-to-r from-sage-50/70 via-white to-blush-50/70 py-16">
        <div class="max-w-4xl mx-auto px-4 text-center space-y-6" x-data="{ email: '', submitted: false }">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-white border border-sage-200 text-botanic text-[10px] uppercase tracking-[0.18em] font-medium shadow-2xs">
                <span class="w-1.5 h-1.5 rounded-full bg-bloom"></span>
                Warta & Peluncuran Koleksi
            </span>

            <div class="space-y-2">
                <h3 class="text-xl sm:text-3xl font-serif font-normal uppercase tracking-wider text-stone-900">
                    Daftar Warta & Peluncuran Koleksi Baru
                </h3>
                <p class="text-xs sm:text-sm text-stone-600 max-w-lg mx-auto leading-relaxed">
                    Daftarkan alamat email Anda untuk menerima kabar peluncuran warna baru, restock produk unggulan, dan inspirasi modest terkini.
                </p>
            </div>

            <!-- Form -->
            <div class="max-w-md mx-auto">
                <template x-if="!submitted">
                    <form @submit.prevent="if(email) { submitted = true; }" class="flex flex-col sm:flex-row gap-2">
                        <input 
                            type="email" 
                            x-model="email" 
                            placeholder="Ketik alamat email Anda..." 
                            required 
                            class="flex-1 text-xs border border-sage-300 bg-white px-4 py-3 focus:border-botanic focus:ring-botanic/20 placeholder-stone-400 rounded-xs shadow-2xs"
                        />
                        <button 
                            type="submit" 
                            class="px-6 py-3 bg-botanic hover:bg-sage-700 text-white text-xs uppercase tracking-widest font-semibold transition-colors shadow-xs rounded-xs cursor-pointer whitespace-nowrap"
                        >
                            Berlangganan
                        </button>
                    </form>
                </template>

                <template x-if="submitted">
                    <div class="p-4 bg-white border border-sage-300 text-stone-800 rounded-xs text-xs font-medium space-y-1 shadow-xs">
                        <p class="text-sm font-semibold text-botanic">✓ Terima kasih telah bergabung!</p>
                        <p class="text-stone-600 text-[11px]">Anda akan menerima kabar pertama saat koleksi dan warna favorit kami resmi dirilis.</p>
                    </div>
                </template>

                <span class="text-[11px] text-stone-400 mt-2 block">
                    Kami menghargai privasi Anda. Bebas berhenti berlangganan kapan saja.
                </span>
            </div>
        </div>
    </section>
</x-storefront-layout>
