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
                'tag' => 'Atelier Hijab Kurasi 2026',
                'badge' => 'Signature Edition',
                'label_subtitle' => 'Voal & Pashmina Draped in Grace',
                'label_detail' => 'Warna Sage & Dusty Rose Kurasi 2026',
                'cta_text' => 'Jelajahi Koleksi',
            ],
            [
                'title' => 'Pashmina Silk Shimmer & Ceruty Flowy',
                'subtitle' => 'Kilau lembut mewah yang memikat dengan drape kain jatuh sempurna. Sangat anggun untuk resepsi dan momen istimewa.',
                'image' => 'images/products/pashmina-silk.jpg',
                'link_url' => '/koleksi/pashmina',
                'tag' => 'Shimmer & Glow Series',
                'badge' => 'Silk Shimmer',
                'label_subtitle' => 'Kilau Anggun Elegan',
                'label_detail' => 'Kain Lembut Jatuh & Sejuk Seharian',
                'cta_text' => 'Lihat Pashmina Silk',
            ],
            [
                'title' => 'Voal Miracle & Segi Empat Laser Cut',
                'subtitle' => 'Potongan laser cut presisi dengan tekstur kain anti kusut. Pilihan tepat untuk kenyamanan seharian penuh percaya diri.',
                'image' => 'images/products/voal-signature.jpg',
                'link_url' => '/koleksi/hijab-segi-empat',
                'tag' => 'Best Seller Voal',
                'badge' => 'Laser Cut Edge',
                'label_subtitle' => 'Voal Ultrafine Turkish',
                'label_detail' => 'Tegak di Dahi, Anti Geser',
                'cta_text' => 'Beli Voal Signature',
            ],
            [
                'title' => 'Bergo Daily & Khimar Soft Pet Series',
                'subtitle' => 'Praktis, langsung pakai, dan sejuk di cuaca tropis. Jahit butik tepi halus dengan ketahanan bahan tinggi.',
                'image' => 'images/products/bergo-daily.jpg',
                'link_url' => '/koleksi/bergo-daily',
                'tag' => 'Daily Practical Wear',
                'badge' => 'Instant Bergo',
                'label_subtitle' => 'Soft Pet Nyaman & Pas',
                'label_detail' => 'Jersey ITY Premium Breathable',
                'cta_text' => 'Lihat Bergo Daily',
            ],
            [
                'title' => 'Koleksi Syar\'i & Elegan 2026',
                'subtitle' => 'Ditenun dari serat voal ultrafine dan ceruty babydoll pilihan. Sempurna menemani ibadah dan aktivitas harian Anda.',
                'image' => 'images/home/hero-editorial.jpg',
                'link_url' => '/koleksi',
                'tag' => 'Editorial Collection',
                'badge' => 'Syar\'i Silhouette',
                'label_subtitle' => 'Anggun Bersahaja Syar\'i',
                'label_detail' => 'Menutup Dada dengan Sempurna',
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
                    'label_subtitle' => $preset['label_subtitle'],
                    'label_detail' => $preset['label_detail'],
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

    <!-- Hero Editorial Section with Auto-Advancing Carousel (3.5s) -->
    <section 
        x-data="{
            current: 0,
            total: {{ count($slides) }},
            intervalMs: 3500,
            stepMs: 50,
            progress: 0,
            timer: null,
            isPaused: false,
            touchStartX: 0,
            touchEndX: 0,
            init() {
                this.startAutoplay();
            },
            startAutoplay() {
                this.clearTimer();
                this.timer = setInterval(() => {
                    if (!this.isPaused) {
                        this.progress += (this.stepMs / this.intervalMs) * 100;
                        if (this.progress >= 100) {
                            this.progress = 0;
                            this.next();
                        }
                    }
                }, this.stepMs);
            },
            clearTimer() {
                if (this.timer) {
                    clearInterval(this.timer);
                    this.timer = null;
                }
            },
            next() {
                this.current = (this.current + 1) % this.total;
                this.progress = 0;
            },
            prev() {
                this.current = (this.current - 1 + this.total) % this.total;
                this.progress = 0;
            },
            goTo(index) {
                this.current = index;
                this.progress = 0;
            },
            pause() {
                this.isPaused = true;
            },
            resume() {
                this.isPaused = false;
            },
            togglePause() {
                this.isPaused = !this.isPaused;
            },
            handleTouchStart(e) {
                this.touchStartX = e.changedTouches[0].screenX;
            },
            handleTouchEnd(e) {
                this.touchEndX = e.changedTouches[0].screenX;
                if (this.touchStartX - this.touchEndX > 45) {
                    this.next();
                } else if (this.touchEndX - this.touchStartX > 45) {
                    this.prev();
                }
            }
        }"
        @mouseenter="pause()"
        @mouseleave="resume()"
        @keydown.arrow-right.window="next()"
        @keydown.arrow-left.window="prev()"
        class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-14 relative select-none"
        aria-roledescription="carousel"
        aria-label="Koleksi Unggulan BloomingKembang"
    >
        <div class="border border-blush-200/90 bg-gradient-to-br from-sage-50/95 via-white/95 to-blush-50/90 relative overflow-hidden rounded-2xl shadow-xs backdrop-blur-xs">
            <!-- Atmospheric Ambient Orbs -->
            <div class="absolute -top-24 -left-24 w-80 h-80 rounded-full bg-sage-200/40 blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -right-24 w-80 h-80 rounded-full bg-blush-200/40 blur-3xl pointer-events-none"></div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center p-6 sm:p-10 lg:p-14 relative z-10">
                <!-- Text Column (Left) -->
                <div class="lg:col-span-7 text-center lg:text-left relative flex flex-col justify-between">
                    <div class="grid [grid-template-areas:'stack'] items-center min-h-[290px] sm:min-h-[280px]">
                        @foreach ($slides as $index => $slide)
                            <div 
                                x-show="current === {{ $index }}"
                                x-transition:enter="transition-all ease-out duration-600 delay-100"
                                x-transition:enter-start="opacity-0 translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition-all ease-in duration-300"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 -translate-y-2"
                                class="col-start-1 row-start-1 space-y-5"
                            >
                                <div>
                                    <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-sage-100/90 text-botanic text-[11px] font-mono tracking-widest uppercase border border-sage-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-bloom animate-pulse"></span>
                                        {{ $slide['tag'] }}
                                    </span>
                                </div>

                                <h1 class="text-2xl sm:text-4xl lg:text-5xl font-serif font-normal tracking-wide text-obsidian uppercase leading-tight min-h-[2.4em] flex items-center justify-center lg:justify-start">
                                    {{ $slide['title'] }}
                                </h1>

                                <p class="max-w-xl mx-auto lg:mx-0 text-xs sm:text-sm text-stone-600 leading-relaxed font-normal min-h-[3.2em]">
                                    {{ $slide['subtitle'] }}
                                </p>

                                <!-- CTAs -->
                                <div class="pt-2 flex flex-wrap items-center justify-center lg:justify-start gap-4">
                                    <a href="{{ url($slide['link_url']) }}" class="px-8 py-3.5 bg-botanic text-paper text-xs uppercase tracking-widest font-medium hover:bg-sage-700 transition-colors shadow-sm rounded-sm">
                                        {{ $slide['cta_text'] }} &rarr;
                                    </a>
                                    <a href="{{ url('/tentang-kami') }}" class="px-6 py-3.5 border border-blush-300 bg-white/80 text-bloom text-xs uppercase tracking-widest font-medium hover:bg-blush-50 transition-colors rounded-sm">
                                        Filosofi Brand
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Mini Value Badges (always visible, anchor to foundation) -->
                    <div class="pt-6 border-t border-bone/70 grid grid-cols-3 gap-3 text-left mt-6">
                        <div class="space-y-0.5">
                            <span class="text-[10px] font-mono uppercase tracking-wider text-botanic font-semibold block">100% Alami</span>
                            <span class="text-[11px] text-stone-600 font-medium block">Voal Ultrafine Turkish</span>
                        </div>
                        <div class="space-y-0.5 border-l border-bone/80 pl-3">
                            <span class="text-[10px] font-mono uppercase tracking-wider text-bloom font-semibold block">Jahit Butik</span>
                            <span class="text-[11px] text-stone-600 font-medium block">Tepi Rapi & Presisi</span>
                        </div>
                        <div class="space-y-0.5 border-l border-bone/80 pl-3">
                            <span class="text-[10px] font-mono uppercase tracking-wider text-botanic font-semibold block">Garansi 7 Hari</span>
                            <span class="text-[11px] text-stone-600 font-medium block">Retur Tanpa Repot</span>
                        </div>
                    </div>
                </div>

                <!-- Visual Column (Right) -->
                <div 
                    class="lg:col-span-5 relative flex flex-col items-center justify-center"
                    @touchstart="handleTouchStart($event)"
                    @touchend="handleTouchEnd($event)"
                >
                    <!-- Visual Image Container -->
                    <div class="relative w-full max-w-sm sm:max-w-md aspect-[3/4] rounded-xl overflow-hidden border border-blush-200/90 shadow-lg group bg-stone-100 grid">
                        @foreach ($slides as $index => $slide)
                            <div 
                                x-show="current === {{ $index }}"
                                x-transition:enter="transition-opacity ease-out duration-700"
                                x-transition:enter-start="opacity-0"
                                x-transition:enter-end="opacity-100"
                                x-transition:leave="transition-opacity ease-in duration-500"
                                x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0"
                                class="col-start-1 row-start-1 w-full h-full relative"
                            >
                                <img 
                                    src="{{ asset($slide['image']) }}" 
                                    alt="{{ $slide['title'] }}" 
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                                    loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                                />
                                <!-- Subtle Gradient Overlay -->
                                <div class="absolute inset-0 bg-gradient-to-t from-obsidian/75 via-transparent to-black/20 pointer-events-none"></div>

                                <!-- Slide Index Badge Top Right -->
                                <div class="absolute top-4 right-4 px-2.5 py-1 rounded-full bg-obsidian/60 backdrop-blur-md border border-white/20 text-white font-mono text-[10px] tracking-widest pointer-events-none shadow-xs">
                                    <span>{{ sprintf('%02d', $index + 1) }}</span> / <span>{{ sprintf('%02d', count($slides)) }}</span>
                                </div>

                                <!-- Floating Glassmorphism Label Bottom -->
                                <div class="absolute bottom-4 left-4 right-4 p-4 rounded-lg bg-white/95 backdrop-blur-md border border-white/50 shadow-md text-left transition-all">
                                    <span class="text-[10px] uppercase font-mono tracking-widest text-botanic font-bold block">{{ $slide['badge'] }}</span>
                                    <p class="text-xs font-serif font-medium text-obsidian uppercase truncate mt-0.5">{{ $slide['label_subtitle'] }}</p>
                                    <span class="text-[10px] text-stone-500 font-mono block mt-0.5">{{ $slide['label_detail'] }}</span>
                                </div>
                            </div>
                        @endforeach

                        <!-- Navigation Arrows Overlay (Left & Right) -->
                        <button 
                            type="button"
                            @click.stop="prev()"
                            class="absolute left-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-white/90 hover:bg-white text-obsidian shadow-md flex items-center justify-center opacity-0 group-hover:opacity-100 sm:opacity-75 sm:hover:opacity-100 transition-all focus:outline-none focus:ring-2 focus:ring-botanic z-20 cursor-pointer"
                            aria-label="Slide sebelumnya"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <button 
                            type="button"
                            @click.stop="next()"
                            class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-white/90 hover:bg-white text-obsidian shadow-md flex items-center justify-center opacity-0 group-hover:opacity-100 sm:opacity-75 sm:hover:opacity-100 transition-all focus:outline-none focus:ring-2 focus:ring-botanic z-20 cursor-pointer"
                            aria-label="Slide selanjutnya"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Carousel Control Bar (Indicators, Progress & Autoplay Toggle) -->
            <div class="px-6 py-4 bg-white/60 border-t border-blush-200/60 backdrop-blur-xs flex flex-wrap items-center justify-between gap-4 text-xs">
                <!-- Left: Slide Indicators with Live Progress Bar -->
                <div class="flex items-center gap-2">
                    @foreach ($slides as $index => $slide)
                        <button 
                            type="button"
                            @click="goTo({{ $index }})"
                            class="group relative h-2.5 rounded-full transition-all duration-300 focus:outline-none cursor-pointer overflow-hidden border border-stone-200/80"
                            :class="current === {{ $index }} ? 'w-12 sm:w-16 bg-botanic/15' : 'w-3 bg-stone-200 hover:bg-stone-300'"
                            aria-label="Pilih Slide {{ $index + 1 }}: {{ $slide['title'] }}"
                        >
                            <!-- Progress fill inside the active pill -->
                            <div 
                                x-show="current === {{ $index }}"
                                class="h-full bg-botanic rounded-full transition-all duration-75"
                                :style="'width: ' + progress + '%'"
                            ></div>
                        </button>
                    @endforeach
                </div>

                <!-- Center/Right: Slide Counter & Play/Pause Controls -->
                <div class="flex items-center gap-3">
                    <!-- Autoplay Status & Play/Pause Button -->
                    <button 
                        type="button"
                        @click="togglePause()"
                        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-mono tracking-wider border border-stone-200 bg-white/80 hover:bg-white text-stone-600 transition-colors cursor-pointer"
                        :title="isPaused ? 'Lanjutkan rotasi otomatis' : 'Jeda rotasi otomatis'"
                    >
                        <template x-if="!isPaused">
                            <span class="flex items-center gap-1.5 text-botanic">
                                <span class="w-1.5 h-1.5 rounded-full bg-bloom animate-ping"></span>
                                <span>Auto 3.5s</span>
                            </span>
                        </template>
                        <template x-if="isPaused">
                            <span class="flex items-center gap-1.5 text-amber-700">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                <span>Dijeda</span>
                            </span>
                        </template>
                    </button>

                    <!-- Slide Index Counter -->
                    <span class="font-mono text-[11px] text-stone-500 tracking-wider">
                        <span class="text-obsidian font-semibold" x-text="String(current + 1).padStart(2, '0')">01</span> / 
                        <span x-text="String(total).padStart(2, '0')">{{ sprintf('%02d', count($slides)) }}</span>
                    </span>

                    <!-- Next / Prev Buttons -->
                    <div class="inline-flex items-center rounded-sm border border-stone-200 bg-white/80 overflow-hidden shadow-2xs">
                        <button 
                            type="button"
                            @click="prev()"
                            class="p-1.5 hover:bg-stone-100 text-stone-600 transition-colors focus:outline-none cursor-pointer"
                            aria-label="Sebelumnya"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <div class="w-px h-4 bg-stone-200"></div>
                        <button 
                            type="button"
                            @click="next()"
                            class="p-1.5 hover:bg-stone-100 text-stone-600 transition-colors focus:outline-none cursor-pointer"
                            aria-label="Selanjutnya"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Curated Categories Banner Strip with Real Images -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <!-- Category 1: Segi Empat -->
            <a href="{{ url('/koleksi/hijab-segi-empat') }}" class="group relative block aspect-[4/3] rounded-lg overflow-hidden border border-sage-200/80 shadow-xs hover:shadow-md transition-all">
                <img 
                    src="{{ asset('images/home/cat-segi-empat.jpg') }}" 
                    alt="Koleksi Hijab Segi Empat" 
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                />
                <div class="absolute inset-0 bg-gradient-to-t from-obsidian/75 via-obsidian/25 to-transparent"></div>
                <div class="absolute bottom-5 left-5 right-5 text-left text-paper">
                    <span class="text-[10px] uppercase font-mono tracking-widest text-blush-200 font-semibold block mb-1">Signature Voal</span>
                    <h3 class="text-sm uppercase tracking-widest font-medium text-white flex items-center justify-between">
                        <span>Hijab Segi Empat</span>
                        <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
                    </h3>
                </div>
            </a>

            <!-- Category 2: Pashmina -->
            <a href="{{ url('/koleksi/pashmina') }}" class="group relative block aspect-[4/3] rounded-lg overflow-hidden border border-blush-200/80 shadow-xs hover:shadow-md transition-all">
                <img 
                    src="{{ asset('images/home/cat-pashmina.jpg') }}" 
                    alt="Koleksi Pashmina Flowy" 
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                />
                <div class="absolute inset-0 bg-gradient-to-t from-obsidian/75 via-obsidian/25 to-transparent"></div>
                <div class="absolute bottom-5 left-5 right-5 text-left text-paper">
                    <span class="text-[10px] uppercase font-mono tracking-widest text-blush-200 font-semibold block mb-1">Ceruty Babydoll</span>
                    <h3 class="text-sm uppercase tracking-widest font-medium text-white flex items-center justify-between">
                        <span>Pashmina Flowy</span>
                        <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
                    </h3>
                </div>
            </a>

            <!-- Category 3: Inner & Aksesoris -->
            <a href="{{ url('/koleksi/inner-dan-aksesoris') }}" class="group relative block aspect-[4/3] rounded-lg overflow-hidden border border-sage-200/80 shadow-xs hover:shadow-md transition-all">
                <img 
                    src="{{ asset('images/home/cat-inner-aksesoris.jpg') }}" 
                    alt="Koleksi Inner dan Aksesoris" 
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                />
                <div class="absolute inset-0 bg-gradient-to-t from-obsidian/75 via-obsidian/25 to-transparent"></div>
                <div class="absolute bottom-5 left-5 right-5 text-left text-paper">
                    <span class="text-[10px] uppercase font-mono tracking-widest text-blush-200 font-semibold block mb-1">Premium Knit & Clips</span>
                    <h3 class="text-sm uppercase tracking-widest font-medium text-white flex items-center justify-between">
                        <span>Inner & Aksesoris</span>
                        <span class="group-hover:translate-x-1 transition-transform">&rarr;</span>
                    </h3>
                </div>
            </a>
        </div>
    </section>

    <!-- Curated Product Grid -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex items-center justify-between mb-10 pb-3 border-b border-bone">
            <div>
                <span class="text-[10px] uppercase font-mono tracking-widest text-botanic font-medium block">Paling Banyak Dipilih</span>
                <h2 class="text-sm uppercase tracking-widest font-medium text-obsidian">Koleksi Terlaris</h2>
            </div>
            <a href="{{ url('/koleksi') }}" class="text-xs uppercase tracking-wider text-bloom font-medium hover:underline flex items-center gap-1">
                <span>Lihat Semua Koleksi</span>
                <span>&rarr;</span>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 sm:gap-12">
            @forelse($featuredProducts as $product)
                <div class="group flex flex-col justify-between border border-sage-200/60 bg-white/90 backdrop-blur-xs p-4 hover:border-bloom hover:shadow-md transition-all rounded-sm">
                    <!-- Photo Frame / Visual Preview -->
                    <div class="aspect-[3/4] bg-stone-50 relative overflow-hidden flex items-center justify-center border border-bone rounded-sm">
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
                            <span class="absolute top-3 left-3 bg-white/95 backdrop-blur-xs border border-sage-200 text-botanic px-2.5 py-0.5 text-[9px] font-mono uppercase tracking-wider rounded-full shadow-xs">
                                {{ $product->sold_count }} Terjual
                            </span>
                        @endif
                    </div>

                    <!-- Caption & Details -->
                    <div class="mt-4 space-y-1.5">
                        <span class="text-[10px] uppercase tracking-wider font-mono text-stone-400 block">{{ $product->category->name ?? 'Kategori' }}</span>
                        <div class="flex items-center justify-between text-xs">
                            <h3 class="font-normal text-obsidian uppercase tracking-wide group-hover:text-bloom transition-colors">
                                <a href="{{ url('/produk/' . $product->slug) }}">{{ $product->name }}</a>
                            </h3>
                            @if($firstVariant = $product->variants->first())
                                <span class="font-mono text-xs font-bold text-botanic">Rp {{ number_format($firstVariant->price, 0, ',', '.') }}</span>
                            @endif
                        </div>
                        <p class="text-[11px] text-stone-500 line-clamp-1 leading-relaxed">{{ $product->short_description }}</p>
                        
                        <!-- Swatches Preview -->
                        <div class="flex items-center space-x-1.5 pt-2">
                            @foreach($product->variants->take(5) as $variant)
                                @if($variant->color_hex)
                                    <span class="w-3.5 h-3.5 border border-stone-300 rounded-full inline-block shadow-xs" style="background-color: {{ $variant->color_hex }};" title="{{ $variant->color }}"></span>
                                @endif
                            @endforeach
                            @if($product->variants->count() > 5)
                                <span class="text-[10px] text-stone-400 pl-1 font-mono">+{{ $product->variants->count() - 5 }}</span>
                            @endif
                        </div>

                        <div class="pt-3 border-t border-bone mt-3 flex items-center justify-between text-[11px]">
                            <a href="{{ url('/produk/' . $product->slug) }}" class="text-bloom font-medium hover:underline uppercase tracking-wider text-[10px]">
                                Detail Produk &rarr;
                            </a>
                            <span class="text-[10px] text-botanic font-mono font-medium">100% Original</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 py-16 text-center text-xs text-stone-500 border border-bone p-8">
                    Belum ada produk aktif yang ditampilkan.
                </div>
            @endforelse
        </div>
    </section>

    <!-- Value Proposition Grid -->
    <section class="border-y border-sage-200/60 bg-gradient-to-r from-sage-50/60 via-white/80 to-blush-50/60 py-16 backdrop-blur-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center sm:text-left">
                <div class="border border-sage-200/90 p-6 space-y-2 bg-sage-50/70 rounded-sm shadow-2xs backdrop-blur-xs">
                    <span class="text-xs uppercase font-mono tracking-widest text-botanic font-bold block">01 / Serat Pilihan</span>
                    <h3 class="text-xs uppercase tracking-wider font-semibold text-obsidian">Kain Ringan & Tegak</h3>
                    <p class="text-xs text-stone-600 leading-relaxed">
                        Dipilih secara cermat dari serat voal ultrafine dan ceruty babydoll berstandar ekspor, anti-kusut, dan adem dipakai seharian.
                    </p>
                </div>

                <div class="border border-blush-200/80 p-6 space-y-2 bg-blush-50/50 rounded-sm">
                    <span class="text-xs uppercase font-mono tracking-widest text-bloom font-bold block">02 / Garansi 7 Hari</span>
                    <h3 class="text-xs uppercase tracking-wider font-semibold text-obsidian">Jaminan Kepuasan</h3>
                    <p class="text-xs text-stone-600 leading-relaxed">
                        Kami menjamin retur atau tukar produk gratis jika terdapat cacat jahitan atau ketidaksesuaian pesanan dalam 7 hari kalender.
                    </p>
                </div>

                <div class="border border-sage-200/80 p-6 space-y-2 bg-sage-50/50 rounded-sm">
                    <span class="text-xs uppercase font-mono tracking-widest text-botanic font-bold block">03 / Pengiriman Cepat</span>
                    <h3 class="text-xs uppercase tracking-wider font-semibold text-obsidian">JNE, J&T & SiCepat</h3>
                    <p class="text-xs text-stone-600 leading-relaxed">
                        Pesanan diverifikasi instan via Midtrans dan langsung disiapkan oleh staf gudang kami di Bandung dengan resi otomatis.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Editorial Story Strip with Wide Boutique Backdrop -->
    <section class="relative py-24 overflow-hidden border-b border-blush-200/60">
        <!-- Wide Background Photo with Parallax feeling -->
        <div class="absolute inset-0 z-0">
            <img 
                src="{{ asset('images/home/hero-editorial.jpg') }}" 
                alt="Filosofi BloomingKembang" 
                class="w-full h-full object-cover object-center filter blur-xs scale-105 opacity-25" 
            />
            <div class="absolute inset-0 bg-gradient-to-r from-sage-50/90 via-white/85 to-blush-50/90 backdrop-blur-2xs"></div>
        </div>

        <div class="relative z-10 max-w-4xl mx-auto px-4 text-center space-y-4">
            <span class="text-[10px] uppercase font-mono tracking-widest text-botanic font-semibold">Filosofi Keanggunan</span>
            <h3 class="text-xl sm:text-3xl font-serif font-normal uppercase tracking-wider text-obsidian">
                Menemani Setiap Langkah Bersahaja
            </h3>
            <p class="text-xs sm:text-sm text-stone-600 max-w-xl mx-auto leading-relaxed">
                Karena keindahan sejati tidak perlu bersuara keras. Kami percaya busana santun mampu memancarkan wibawa kelembutan wanita muslimah secara alami.
            </p>
            <div class="pt-4">
                <a href="{{ url('/tentang-kami') }}" class="inline-flex items-center gap-1.5 px-6 py-3 border border-blush-300 bg-white/90 text-bloom text-xs uppercase tracking-widest font-medium hover:bg-blush-50 transition-colors rounded-sm shadow-xs">
                    <span>Pelajari Lebih Lanjut</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Testimonials / Suara Sahabat BloomingKembang -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="text-center max-w-2xl mx-auto mb-14 space-y-3">
            <span class="text-[10px] uppercase font-mono tracking-widest text-botanic font-semibold block">Suara Pelanggan</span>
            <h2 class="text-xl sm:text-3xl font-serif font-normal uppercase tracking-wider text-obsidian">
                Dipercaya oleh Muslimah Indonesia
            </h2>
            <p class="text-xs sm:text-sm text-stone-600 leading-relaxed font-normal">
                Kesan tulus dari para sahabat yang telah merasakan kenyamanan serat voal ultrafine dan jahitan butik presisi kami.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Review 1 -->
            <div class="p-8 border border-sage-200/80 bg-white/90 rounded-sm shadow-2xs space-y-4 flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="flex items-center space-x-1 text-amber-500">
                        @for($i = 0; $i < 5; $i++)
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        @endfor
                    </div>
                    <p class="text-xs text-stone-700 leading-relaxed italic">
                        "Bahan voal ultrafine-nya benar-benar tegak di dahi dan tidak mudah kusut walau dipakai seharian beraktivitas di luar ruangan. Jahitan tepinya sangat rapi standar butik. Sudah langganan 3 warna!"
                    </p>
                </div>
                <div class="pt-4 border-t border-bone space-y-0.5">
                    <span class="text-xs font-semibold text-obsidian block">Annisa Rahmawati</span>
                    <span class="text-[11px] text-stone-500 font-mono block">Bandung &bull; Pembeli Terverifikasi</span>
                    <span class="text-[10px] text-botanic font-medium block mt-1">Koleksi: Voal Ultrafine Signature - Soft Sage</span>
                </div>
            </div>

            <!-- Review 2 -->
            <div class="p-8 border border-blush-200/80 bg-white/90 rounded-sm shadow-2xs space-y-4 flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="flex items-center space-x-1 text-amber-500">
                        @for($i = 0; $i < 5; $i++)
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        @endfor
                    </div>
                    <p class="text-xs text-stone-700 leading-relaxed italic">
                        "Pashmina silk-nya jatuh sangat anggun, tidak licin berlebihan, dan warnanya persis seperti di foto katalog. Kemasan amplop eksklusifnya memberi pengalaman unboxing yang sangat mewah."
                    </p>
                </div>
                <div class="pt-4 border-t border-bone space-y-0.5">
                    <span class="text-xs font-semibold text-obsidian block">Dian Savitri</span>
                    <span class="text-[11px] text-stone-500 font-mono block">Jakarta Selatan &bull; Pembeli Terverifikasi</span>
                    <span class="text-[10px] text-bloom font-medium block mt-1">Koleksi: Pashmina Silk Premium - Rose Mauve</span>
                </div>
            </div>

            <!-- Review 3 -->
            <div class="p-8 border border-sage-200/80 bg-white/90 rounded-sm shadow-2xs space-y-4 flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="flex items-center space-x-1 text-amber-500">
                        @for($i = 0; $i < 5; $i++)
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        @endfor
                    </div>
                    <p class="text-xs text-stone-700 leading-relaxed italic">
                        "Bergo daily ini penyelamat saat harus keluar mendadak. Bahannya sejuk, pet-nya pas di muka tidak kaku. Pengiriman super kilat, sore pesan besok paginya sudah sampai di rumah."
                    </p>
                </div>
                <div class="pt-4 border-t border-bone space-y-0.5">
                    <span class="text-xs font-semibold text-obsidian block">Fatimah Azzahra</span>
                    <span class="text-[11px] text-stone-500 font-mono block">Surabaya &bull; Pembeli Terverifikasi</span>
                    <span class="text-[10px] text-botanic font-medium block mt-1">Koleksi: Bergo Khimar Daily - Mocca Haze</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter & Exclusive Voucher Strip -->
    <section class="border-t border-sage-200/80 bg-gradient-to-r from-sage-100/70 via-white to-blush-100/60 py-16">
        <div class="max-w-4xl mx-auto px-4 text-center space-y-6" x-data="{ email: '', submitted: false }">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white border border-sage-200 text-botanic text-[10px] uppercase font-mono tracking-widest font-semibold">
                <span class="w-1.5 h-1.5 rounded-full bg-bloom"></span>
                Warta & Penawaran Eksklusif
            </span>

            <div class="space-y-2">
                <h3 class="text-xl sm:text-3xl font-serif font-normal uppercase tracking-wider text-obsidian">
                    Dapatkan Voucher Sambutan Diskon 10%
                </h3>
                <p class="text-xs sm:text-sm text-stone-600 max-w-lg mx-auto leading-relaxed">
                    Daftarkan alamat email Anda untuk menerima kabar peluncuran warna baru, restock produk unggulan, dan kupon potongan belanja pertama.
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
                            class="flex-1 text-xs border border-sage-300 bg-white px-4 py-3 focus:border-obsidian focus:ring-0 placeholder-stone-400 rounded-xs shadow-2xs"
                        />
                        <button 
                            type="submit" 
                            class="px-6 py-3 bg-botanic text-paper text-xs uppercase tracking-widest font-medium hover:bg-sage-700 transition-colors shadow-xs rounded-xs cursor-pointer whitespace-nowrap"
                        >
                            Berlangganan
                        </button>
                    </form>
                </template>

                <template x-if="submitted">
                    <div class="p-4 bg-white border border-sage-300 text-botanic rounded-xs text-xs font-medium space-y-1 shadow-xs">
                        <p class="text-sm font-semibold">✓ Terima kasih telah bergabung!</p>
                        <p class="text-stone-600 text-[11px]">Kupon sambutan 10% Anda: <strong class="font-mono text-obsidian uppercase">RAMADHAN20</strong>. Gunakan saat melakukan checkout pesanan pertama Anda.</p>
                    </div>
                </template>

                <span class="text-[10px] text-stone-400 mt-2 block font-mono">
                    Kami menghargai privasi Anda. Bebas berhenti berlangganan kapan saja.
                </span>
            </div>
        </div>
    </section>
</x-storefront-layout>
