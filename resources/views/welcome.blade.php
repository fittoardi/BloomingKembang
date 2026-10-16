<x-storefront-layout>
    <!-- Hero Editorial Gallery -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24">
        <div class="border border-bone p-8 sm:p-16 text-center space-y-6 bg-paper">
            <span class="text-xs uppercase tracking-[0.25em] text-neutral-500">Koleksi Terbaru 2026</span>
            <h1 class="text-2xl sm:text-4xl font-normal tracking-wide text-obsidian">
                Sentuhan Lembut & Elegan Hijab Pilihan
            </h1>
            <p class="max-w-xl mx-auto text-xs sm:text-sm text-neutral-600 leading-relaxed font-normal">
                Ditenun dari serat voal ultrafine dan ceruty babydoll pilihan. Nyaman, jatuh berkarakter, dan senantiasa bersahaja dalam setiap pemakaian.
            </p>
            <div class="pt-4">
                <a href="{{ url('/koleksi') }}" class="link-underlined text-xs sm:text-sm uppercase tracking-widest font-medium">
                    Jelajahi Semua Koleksi
                </a>
            </div>
        </div>
    </section>

    <!-- Curated Product Grid -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex items-center justify-between mb-8 pb-3 border-b border-bone">
            <h2 class="text-xs uppercase tracking-widest font-medium text-obsidian">Produk Pilihan (Katalog CONTOH)</h2>
            <a href="{{ url('/koleksi') }}" class="link-underlined text-xs uppercase tracking-wider">Lihat Semua</a>
        </div>

        @php
            $featuredProducts = \App\Models\Product::with(['category', 'variants'])->where('status', \App\Enums\ProductStatus::Active)->take(6)->get();
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 sm:gap-12">
            @forelse($featuredProducts as $product)
                <div class="group flex flex-col justify-between">
                    <!-- Photo Frame / Placeholder Mockup -->
                    <div class="aspect-[3/4] bg-bone border border-neutral-200 relative overflow-hidden flex items-center justify-center p-6 text-center">
                        <div class="space-y-2">
                            <span class="text-[10px] uppercase tracking-widest text-neutral-400 block font-mono">{{ $product->category->name ?? 'Hijab' }}</span>
                            <span class="text-xs font-medium text-obsidian block">{{ $product->name }}</span>
                            <span class="text-[11px] text-neutral-500 block font-mono">{{ $product->material ?? 'Bahan Pilihan' }}</span>
                        </div>
                    </div>

                    <!-- Caption & Details -->
                    <div class="mt-4 space-y-1">
                        <div class="flex items-center justify-between text-xs">
                            <h3 class="font-normal text-obsidian">{{ $product->name }}</h3>
                            @if($firstVariant = $product->variants->first())
                                <span class="font-mono text-xs">Rp {{ number_format($firstVariant->price, 0, ',', '.') }}</span>
                            @endif
                        </div>
                        <p class="text-[11px] text-neutral-500 line-clamp-1">{{ $product->short_description }}</p>
                        
                        <!-- Swatches -->
                        <div class="flex items-center space-x-1.5 pt-2">
                            @foreach($product->variants->take(4) as $variant)
                                @if($variant->color_hex)
                                    <span class="w-3.5 h-3.5 border border-neutral-300 inline-block" style="background-color: {{ $variant->color_hex }};" title="{{ $variant->color }}"></span>
                                @endif
                            @endforeach
                            @if($product->variants->count() > 4)
                                <span class="text-[10px] text-neutral-400 pl-1">+{{ $product->variants->count() - 4 }}</span>
                            @endif
                        </div>

                        <div class="pt-3">
                            <a href="{{ url('/produk/' . $product->slug) }}" class="link-underlined text-[11px] uppercase tracking-wider block">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-3 py-12 text-center text-xs text-neutral-500">
                    Belum ada produk aktif yang ditampilkan.
                </div>
            @endforelse
        </div>
    </section>

    <!-- Museum Vitrine Value Banner -->
    <section class="border-t border-bone bg-bone/40 py-20 mt-16">
        <div class="max-w-5xl mx-auto px-4 text-center space-y-4">
            <h3 class="text-xs uppercase tracking-[0.2em] font-medium text-obsidian">Dedikasi Kualitas</h3>
            <p class="text-xs sm:text-sm text-neutral-600 max-w-2xl mx-auto leading-relaxed">
                Setiap helai kain melewati seleksi ketat untuk memastikan kelembutan, kerapian jahit tepi, dan ketepatan warna yang ramah dipadupadankan.
            </p>
            <div class="pt-4">
                <a href="{{ url('/tentang-kami') }}" class="link-underlined text-xs uppercase tracking-widest">
                    Baca Kisah Kami
                </a>
            </div>
        </div>
    </section>
</x-storefront-layout>
