<x-storefront-layout title="BloomingKembang — Koleksi Hijab Elegan & Bersahaja">
    @php
        $heroBanner = \App\Models\Banner::where('is_active', true)
            ->where('position', 'hero')
            ->where(fn($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>=', now()))
            ->orderBy('sort_order')
            ->first();

        $featuredProducts = \App\Models\Product::with(['category', 'variants'])
            ->where('status', \App\Enums\ProductStatus::Active)
            ->orderBy('sold_count', 'desc')
            ->take(6)
            ->get();
    @endphp

    <!-- Hero Editorial Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-16 relative">
        <div class="border border-blush-200/90 bg-gradient-to-br from-sage-50/95 via-white/95 to-blush-50/90 relative overflow-hidden rounded-xl shadow-xs backdrop-blur-xs">
            <!-- Atmospheric Ambient Orbs -->
            <div class="absolute -top-24 -left-24 w-80 h-80 rounded-full bg-sage-200/40 blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-24 -right-24 w-80 h-80 rounded-full bg-blush-200/40 blur-3xl pointer-events-none"></div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center p-6 sm:p-12 lg:p-16 relative z-10">
                <!-- Text Column (Left) -->
                <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                    <div>
                        <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-sage-100/90 text-botanic text-[11px] font-mono tracking-widest uppercase border border-sage-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-bloom animate-pulse"></span>
                            Atelier Hijab Kurasi 2026
                        </span>
                    </div>

                    <h1 class="text-2xl sm:text-4xl lg:text-5xl font-serif font-normal tracking-wide text-obsidian uppercase leading-tight">
                        {{ $heroBanner?->title ?? 'Sentuhan Lembut & Elegan Hijab Pilihan' }}
                    </h1>

                    <p class="max-w-xl mx-auto lg:mx-0 text-xs sm:text-sm text-stone-600 leading-relaxed font-normal">
                        {{ $heroBanner?->subtitle ?? 'Ditenun dari serat voal ultrafine dan ceruty babydoll pilihan. Lembut bersahaja, tegak di dahi, dan anggun dalam setiap momen.' }}
                    </p>

                    <!-- CTAs -->
                    <div class="pt-2 flex flex-wrap items-center justify-center lg:justify-start gap-4">
                        <a href="{{ url('/koleksi') }}" class="px-8 py-3.5 bg-botanic text-paper text-xs uppercase tracking-widest font-medium hover:bg-sage-700 transition-colors shadow-sm rounded-sm">
                            Jelajahi Koleksi &rarr;
                        </a>
                        <a href="{{ url('/tentang-kami') }}" class="px-6 py-3.5 border border-blush-300 bg-white/80 text-bloom text-xs uppercase tracking-widest font-medium hover:bg-blush-50 transition-colors rounded-sm">
                            Filosofi Brand
                        </a>
                    </div>

                    <!-- Mini Value Badges -->
                    <div class="pt-6 border-t border-bone/70 grid grid-cols-3 gap-3 text-left">
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
                <div class="lg:col-span-5 relative flex justify-center">
                    <div class="relative w-full max-w-sm sm:max-w-md aspect-[3/4] rounded-lg overflow-hidden border border-blush-200/90 shadow-lg group">
                        <img 
                            src="{{ asset('images/home/hero-portrait.jpg') }}" 
                            alt="BloomingKembang Signature Hijab Collection" 
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                        />
                        <!-- Subtle Gradient Overlay on Portrait -->
                        <div class="absolute inset-0 bg-gradient-to-t from-obsidian/60 via-transparent to-transparent opacity-80"></div>
                        
                        <!-- Floating Glassmorphism Label -->
                        <div class="absolute bottom-4 left-4 right-4 p-4 rounded-md bg-white/90 backdrop-blur-md border border-white/40 shadow-sm text-left">
                            <span class="text-[10px] uppercase font-mono tracking-widest text-botanic font-bold block">Signature Edition</span>
                            <p class="text-xs font-serif font-medium text-obsidian uppercase">Voal & Pashmina Draped in Grace</p>
                            <span class="text-[10px] text-stone-500 font-mono block mt-0.5">Warna Sage & Dusty Rose Kurasi 2026</span>
                        </div>
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
</x-storefront-layout>
