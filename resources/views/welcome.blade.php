<x-storefront-layout title="BloomingKembang — Koleksi Hijab Elegan & Bersahaja">
    @php
        $topBanner = \App\Models\Banner::where('is_active', true)
            ->where('position', 'top_bar')
            ->where(fn($q) => $q->whereNull('starts_at')->orWhere('starts_at', '<=', now()))
            ->where(fn($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>=', now()))
            ->orderBy('sort_order')
            ->first();

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

    <!-- Top Announcement Banner if Active -->
    @if($topBanner)
        <aside class="bg-obsidian text-paper text-center py-2 px-4 text-[10px] sm:text-xs font-mono uppercase tracking-widest border-b border-bone">
            @if($topBanner->link_url)
                <a href="{{ $topBanner->link_url }}" class="hover:underline flex items-center justify-center gap-2">
                    <span>{{ $topBanner->title }}</span>
                    @if($topBanner->subtitle) <span class="opacity-75 hidden sm:inline">&bull; {{ $topBanner->subtitle }}</span> @endif
                    <span>&rarr;</span>
                </a>
            @else
                <span>{{ $topBanner->title }}</span>
                @if($topBanner->subtitle) <span class="opacity-75 hidden sm:inline">&bull; {{ $topBanner->subtitle }}</span> @endif
            @endif
        </aside>
    @endif

    <!-- Hero Editorial Section -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-20">
        <div class="border border-bone p-8 sm:p-16 text-center space-y-6 bg-paper relative overflow-hidden">
            <span class="text-xs uppercase tracking-[0.25em] text-neutral-500 font-mono block">
                Koleksi Hijab Kurasi 2026
            </span>
            <h1 class="text-2xl sm:text-4xl lg:text-5xl font-normal tracking-wide text-obsidian uppercase">
                {{ $heroBanner?->title ?? 'Sentuhan Lembut & Elegan Hijab Pilihan' }}
            </h1>
            <p class="max-w-2xl mx-auto text-xs sm:text-sm text-neutral-600 leading-relaxed font-normal">
                {{ $heroBanner?->subtitle ?? 'Ditenun dari serat voal ultrafine dan ceruty babydoll pilihan. Lembut bersahaja, tegak di dahi, dan anggun dalam setiap momen.' }}
            </p>
            <div class="pt-4 flex flex-wrap items-center justify-center gap-4">
                <a href="{{ url('/koleksi') }}" class="px-8 py-3 bg-obsidian text-paper text-xs uppercase tracking-widest font-medium hover:bg-neutral-800 transition-colors shadow-sm">
                    Jelajahi Koleksi &rarr;
                </a>
                <a href="{{ url('/tentang-kami') }}" class="px-6 py-3 border border-bone text-obsidian text-xs uppercase tracking-widest font-medium hover:bg-bone transition-colors">
                    Filosofi Brand
                </a>
            </div>
        </div>
    </section>

    <!-- Curated Categories Banner Strip -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <a href="{{ url('/koleksi/hijab-segi-empat') }}" class="border border-bone bg-paper p-6 hover:border-obsidian transition-colors text-center group">
                <span class="text-[10px] uppercase font-mono tracking-widest text-neutral-400 block mb-1">Signature Voal</span>
                <h3 class="text-xs uppercase tracking-widest font-medium text-obsidian group-hover:underline">Hijab Segi Empat &rarr;</h3>
            </a>
            <a href="{{ url('/koleksi/pashmina') }}" class="border border-bone bg-paper p-6 hover:border-obsidian transition-colors text-center group">
                <span class="text-[10px] uppercase font-mono tracking-widest text-neutral-400 block mb-1">Ceruty Babydoll</span>
                <h3 class="text-xs uppercase tracking-widest font-medium text-obsidian group-hover:underline">Pashmina Flowy &rarr;</h3>
            </a>
            <a href="{{ url('/koleksi/inner-dan-aksesoris') }}" class="border border-bone bg-paper p-6 hover:border-obsidian transition-colors text-center group">
                <span class="text-[10px] uppercase font-mono tracking-widest text-neutral-400 block mb-1">Premium Knit</span>
                <h3 class="text-xs uppercase tracking-widest font-medium text-obsidian group-hover:underline">Inner & Aksesoris &rarr;</h3>
            </a>
        </div>
    </section>

    <!-- Curated Product Grid -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="flex items-center justify-between mb-10 pb-3 border-b border-bone">
            <div>
                <span class="text-[10px] uppercase font-mono tracking-widest text-neutral-400 block">Paling Banyak Dipilih</span>
                <h2 class="text-sm uppercase tracking-widest font-medium text-obsidian">Koleksi Terlaris</h2>
            </div>
            <a href="{{ url('/koleksi') }}" class="link-underlined text-xs uppercase tracking-wider">
                Lihat Semua Koleksi &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 sm:gap-12">
            @forelse($featuredProducts as $product)
                <div class="group flex flex-col justify-between border border-bone/60 bg-paper p-4 hover:border-obsidian transition-all">
                    <!-- Photo Frame / Visual Preview -->
                    <div class="aspect-[3/4] bg-bone relative overflow-hidden flex items-center justify-center p-6 text-center border border-bone">
                        @if($product->hasMedia('images'))
                            <img 
                                src="{{ $product->getFirstMediaUrl('images', 'preview') ?: $product->getFirstMediaUrl('images') }}" 
                                alt="{{ $product->name }}" 
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                loading="lazy"
                            />
                        @else
                            <div class="space-y-2">
                                <span class="text-[10px] uppercase tracking-widest text-neutral-400 block font-mono">{{ $product->category->name ?? 'Hijab' }}</span>
                                <span class="text-xs font-medium text-obsidian block uppercase">{{ $product->name }}</span>
                                <span class="text-[11px] text-neutral-500 block font-mono">{{ $product->material ?? 'Bahan Pilihan' }}</span>
                            </div>
                        @endif

                        @if($product->sold_count > 0)
                            <span class="absolute top-3 left-3 bg-paper/90 backdrop-blur-xs border border-bone px-2 py-0.5 text-[9px] font-mono uppercase tracking-wider text-obsidian">
                                {{ $product->sold_count }} Terjual
                            </span>
                        @endif
                    </div>

                    <!-- Caption & Details -->
                    <div class="mt-4 space-y-1">
                        <span class="text-[10px] uppercase tracking-wider font-mono text-neutral-400 block">{{ $product->category->name ?? 'Kategori' }}</span>
                        <div class="flex items-center justify-between text-xs">
                            <h3 class="font-normal text-obsidian uppercase tracking-wide group-hover:underline">
                                <a href="{{ url('/produk/' . $product->slug) }}">{{ $product->name }}</a>
                            </h3>
                            @if($firstVariant = $product->variants->first())
                                <span class="font-mono text-xs font-bold text-obsidian">Rp {{ number_format($firstVariant->price, 0, ',', '.') }}</span>
                            @endif
                        </div>
                        <p class="text-[11px] text-neutral-500 line-clamp-1 leading-relaxed">{{ $product->short_description }}</p>
                        
                        <!-- Swatches Preview -->
                        <div class="flex items-center space-x-1.5 pt-2">
                            @foreach($product->variants->take(5) as $variant)
                                @if($variant->color_hex)
                                    <span class="w-3.5 h-3.5 border border-neutral-300 inline-block shadow-xs" style="background-color: {{ $variant->color_hex }};" title="{{ $variant->color }}"></span>
                                @endif
                            @endforeach
                            @if($product->variants->count() > 5)
                                <span class="text-[10px] text-neutral-400 pl-1 font-mono">+{{ $product->variants->count() - 5 }}</span>
                            @endif
                        </div>

                        <div class="pt-3 border-t border-bone/60 mt-3 flex items-center justify-between text-[11px]">
                            <a href="{{ url('/produk/' . $product->slug) }}" class="link-underlined uppercase tracking-wider">
                                Detail Produk
                            </a>
                            <span class="text-[10px] text-neutral-400 font-mono">100% Original</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 py-16 text-center text-xs text-neutral-500 border border-bone p-8">
                    Belum ada produk aktif yang ditampilkan.
                </div>
            @endforelse
        </div>
    </section>

    <!-- Value Proposition Grid -->
    <section class="border-y border-bone bg-paper py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center sm:text-left">
                <div class="border border-bone p-6 space-y-2 bg-bone/30">
                    <span class="text-xs uppercase font-mono tracking-widest text-obsidian font-semibold block">01 / Serat Pilihan</span>
                    <h3 class="text-xs uppercase tracking-wider font-medium text-obsidian">Kain Ringan & Tegak</h3>
                    <p class="text-xs text-neutral-600 leading-relaxed">
                        Dipilih secara cermat dari serat voal ultrafine dan ceruty babydoll berstandar ekspor, anti-kusut, dan adem dipakai seharian.
                    </p>
                </div>

                <div class="border border-bone p-6 space-y-2 bg-bone/30">
                    <span class="text-xs uppercase font-mono tracking-widest text-obsidian font-semibold block">02 / Garansi 7 Hari</span>
                    <h3 class="text-xs uppercase tracking-wider font-medium text-obsidian">Jaminan Kepuasan</h3>
                    <p class="text-xs text-neutral-600 leading-relaxed">
                        Kami menjamin retur atau tukar produk gratis jika terdapat cacat jahitan atau ketidaksesuaian pesanan dalam 7 hari kalender.
                    </p>
                </div>

                <div class="border border-bone p-6 space-y-2 bg-bone/30">
                    <span class="text-xs uppercase font-mono tracking-widest text-obsidian font-semibold block">03 / Pengiriman Cepat</span>
                    <h3 class="text-xs uppercase tracking-wider font-medium text-obsidian">JNE, J&T & SiCepat</h3>
                    <p class="text-xs text-neutral-600 leading-relaxed">
                        Pesanan diverifikasi instan via Midtrans dan langsung disiapkan oleh staf gudang kami di Bandung dengan resi otomatis.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Editorial Story Strip -->
    <section class="max-w-4xl mx-auto px-4 py-20 text-center space-y-4">
        <span class="text-[10px] uppercase font-mono tracking-widest text-neutral-400">Filosofi Keanggunan</span>
        <h3 class="text-xl sm:text-2xl font-normal uppercase tracking-wider text-obsidian">
            Menemani Setiap Langkah Bersahaja
        </h3>
        <p class="text-xs sm:text-sm text-neutral-600 max-w-xl mx-auto leading-relaxed">
            Karena keindahan sejati tidak perlu bersuara keras. Kami percaya busana santun mampu memancarkan wibawa kelembutan wanita muslimah secara alami.
        </p>
        <div class="pt-4">
            <a href="{{ url('/tentang-kami') }}" class="link-underlined text-xs uppercase tracking-widest font-medium">
                Pelajari Lebih Lanjut &rarr;
            </a>
        </div>
    </section>
</x-storefront-layout>
