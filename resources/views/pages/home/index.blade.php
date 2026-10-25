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
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-20 relative">
        <div class="border border-blush-200/90 p-8 sm:p-16 text-center space-y-6 bg-gradient-to-br from-sage-50/90 via-white/95 to-blush-50/90 relative overflow-hidden rounded-md shadow-xs backdrop-blur-xs">
            <!-- Atmospheric Ambient Orbs -->
            <div class="absolute -top-16 -left-16 w-64 h-64 rounded-full bg-sage-200/40 blur-3xl pointer-events-none"></div>
            <div class="absolute -bottom-16 -right-16 w-64 h-64 rounded-full bg-blush-200/40 blur-3xl pointer-events-none"></div>

            <div>
                <span class="inline-flex items-center gap-2 px-3.5 py-1 rounded-full bg-sage-100/90 text-botanic text-[11px] font-mono tracking-widest uppercase border border-sage-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-bloom animate-pulse"></span>
                    Koleksi Hijab Kurasi 2026
                </span>
            </div>
            <h1 class="text-2xl sm:text-4xl lg:text-5xl font-serif font-normal tracking-wide text-obsidian uppercase">
                {{ $heroBanner?->title ?? 'Sentuhan Lembut & Elegan Hijab Pilihan' }}
            </h1>
            <p class="max-w-2xl mx-auto text-xs sm:text-sm text-stone-600 leading-relaxed font-normal">
                {{ $heroBanner?->subtitle ?? 'Ditenun dari serat voal ultrafine dan ceruty babydoll pilihan. Lembut bersahaja, tegak di dahi, dan anggun dalam setiap momen.' }}
            </p>
            <div class="pt-4 flex flex-wrap items-center justify-center gap-4">
                <a href="{{ url('/koleksi') }}" class="px-8 py-3.5 bg-botanic text-paper text-xs uppercase tracking-widest font-medium hover:bg-sage-700 transition-colors shadow-sm rounded-sm">
                    Jelajahi Koleksi &rarr;
                </a>
                <a href="{{ url('/tentang-kami') }}" class="px-6 py-3.5 border border-blush-300 bg-white/80 text-bloom text-xs uppercase tracking-widest font-medium hover:bg-blush-50 transition-colors rounded-sm">
                    Filosofi Brand
                </a>
            </div>
        </div>
    </section>

    <!-- Curated Categories Banner Strip -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
            <a href="{{ url('/koleksi/hijab-segi-empat') }}" class="border border-sage-200 bg-gradient-to-br from-sage-50/80 to-white/90 p-6 hover:border-botanic hover:shadow-xs transition-all text-center group rounded-sm backdrop-blur-xs">
                <span class="text-[10px] uppercase font-mono tracking-widest text-botanic font-semibold block mb-1">Signature Voal</span>
                <h3 class="text-xs uppercase tracking-widest font-medium text-obsidian group-hover:text-botanic transition-colors">Hijab Segi Empat &rarr;</h3>
            </a>
            <a href="{{ url('/koleksi/pashmina') }}" class="border border-blush-200 bg-gradient-to-br from-blush-50/80 to-white/90 p-6 hover:border-bloom hover:shadow-xs transition-all text-center group rounded-sm backdrop-blur-xs">
                <span class="text-[10px] uppercase font-mono tracking-widest text-bloom font-semibold block mb-1">Ceruty Babydoll</span>
                <h3 class="text-xs uppercase tracking-widest font-medium text-obsidian group-hover:text-bloom transition-colors">Pashmina Flowy &rarr;</h3>
            </a>
            <a href="{{ url('/koleksi/inner-dan-aksesoris') }}" class="border border-sage-200/90 bg-gradient-to-br from-sage-50/60 via-white/80 to-blush-50/60 p-6 hover:border-botanic hover:shadow-xs transition-all text-center group rounded-sm backdrop-blur-xs">
                <span class="text-[10px] uppercase font-mono tracking-widest text-botanic font-semibold block mb-1">Premium Knit</span>
                <h3 class="text-xs uppercase tracking-widest font-medium text-obsidian group-hover:text-botanic transition-colors">Inner & Aksesoris &rarr;</h3>
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
                    <div class="aspect-[3/4] bg-stone-50 relative overflow-hidden flex items-center justify-center p-6 text-center border border-bone">
                        @if($product->hasMedia('images'))
                            <img 
                                src="{{ $product->getFirstMediaUrl('images', 'preview') ?: $product->getFirstMediaUrl('images') }}" 
                                alt="{{ $product->name }}" 
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                loading="lazy"
                            />
                        @else
                            <div class="space-y-2">
                                <span class="text-[10px] uppercase tracking-widest text-botanic block font-mono">{{ $product->category->name ?? 'Hijab' }}</span>
                                <span class="text-xs font-serif font-medium text-obsidian block uppercase">{{ $product->name }}</span>
                                <span class="text-[11px] text-stone-500 block font-mono">{{ $product->material ?? 'Bahan Pilihan' }}</span>
                            </div>
                        @endif

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

    <!-- Editorial Story Strip -->
    <section class="border-b border-blush-200/60 bg-gradient-to-r from-sage-50/70 via-white/85 to-blush-50/70 py-20 backdrop-blur-xs">
        <div class="max-w-4xl mx-auto px-4 text-center space-y-4">
            <span class="text-[10px] uppercase font-mono tracking-widest text-botanic font-semibold">Filosofi Keanggunan</span>
            <h3 class="text-xl sm:text-2xl font-serif font-normal uppercase tracking-wider text-obsidian">
                Menemani Setiap Langkah Bersahaja
            </h3>
            <p class="text-xs sm:text-sm text-stone-600 max-w-xl mx-auto leading-relaxed">
                Karena keindahan sejati tidak perlu bersuara keras. Kami percaya busana santun mampu memancarkan wibawa kelembutan wanita muslimah secara alami.
            </p>
            <div class="pt-4">
                <a href="{{ url('/tentang-kami') }}" class="inline-flex items-center gap-1.5 text-xs uppercase tracking-widest font-medium text-bloom hover:text-blush-800 transition-colors">
                    <span>Pelajari Lebih Lanjut</span>
                    <span>&rarr;</span>
                </a>
            </div>
        </div>
    </section>
</x-storefront-layout>
