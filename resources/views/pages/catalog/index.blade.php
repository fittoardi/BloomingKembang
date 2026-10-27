<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16">
    <!-- Header Koleksi -->
    <div class="mb-10 pb-6 border-b border-bone">
        <nav class="text-[11px] uppercase tracking-wider text-neutral-400 mb-3 flex items-center space-x-2">
            <a href="{{ url('/') }}" class="hover:text-obsidian transition-colors">Beranda</a>
            <span>/</span>
            <span class="text-obsidian font-medium">Koleksi</span>
            @if($currentCategory)
                <span>/</span>
                <span class="text-obsidian font-medium">{{ $currentCategory->name }}</span>
            @endif
        </nav>
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-normal text-obsidian tracking-wide">
                    {{ $currentCategory ? $currentCategory->name : 'Semua Koleksi' }}
                </h1>
                <p class="text-xs sm:text-sm text-neutral-500 mt-2 max-w-xl">
                    {{ $currentCategory ? ($currentCategory->description ?? 'Koleksi hijab pilihan dirancang dengan kenyamanan dan elegansi.') : 'Temukan berbagai pilihan hijab berkualitas, mulai dari voal ultrafine, pashmina silk, hingga instan bergo harian.' }}
                </p>
            </div>
            
            <!-- Category Quick Tabs -->
            <div class="flex flex-wrap gap-2 text-xs">
                <a href="{{ url('/koleksi') }}" class="px-3.5 py-1.5 border {{ empty($categorySlug) ? 'border-botanic bg-botanic text-paper font-medium shadow-xs' : 'border-sage-200/80 bg-white/70 hover:border-botanic text-neutral-600' }} transition-colors rounded-xs">
                    Semua
                </a>
                @foreach($categories as $cat)
                    <a href="{{ url('/koleksi/' . $cat->slug) }}" class="px-3.5 py-1.5 border {{ $categorySlug === $cat->slug ? 'border-botanic bg-botanic text-paper font-medium shadow-xs' : 'border-sage-200/80 bg-white/70 hover:border-botanic text-neutral-600' }} transition-colors rounded-xs">
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 sm:gap-12 items-start">
        <!-- Sidebar Filter -->
        <aside class="lg:col-span-1 space-y-8 bg-white/85 backdrop-blur-xs p-6 border border-sage-200/80 rounded-sm shadow-2xs">
            <div class="flex items-center justify-between pb-3 border-b border-sage-100">
                <h2 class="text-xs uppercase tracking-widest font-semibold text-obsidian">Filter Koleksi</h2>
                @if($search || $color || $material || $availability || $minPrice || $maxPrice || $sortBy !== 'newest')
                    <button wire:click="resetFilters" class="text-[11px] underline text-neutral-500 hover:text-obsidian cursor-pointer">
                        Reset Semua
                    </button>
                @endif
            </div>

            <!-- Pencarian Cepat (Live Search) -->
            <div class="space-y-2">
                <label class="text-[11px] uppercase tracking-wider text-neutral-500 font-medium block">Pencarian Produk</label>
                <div class="relative">
                    <input 
                        type="text" 
                        wire:model.live.debounce.350ms="search" 
                        placeholder="Cari voal, silk, pashmina..." 
                        class="w-full text-xs border border-bone bg-white pl-8 pr-8 py-2.5 focus:border-obsidian focus:ring-0 placeholder-stone-400"
                    />
                    <svg class="w-3.5 h-3.5 text-stone-400 absolute left-2.5 top-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    @if($search)
                        <button 
                            type="button" 
                            wire:click="$set('search', '')" 
                            class="absolute right-2.5 top-2.5 text-stone-400 hover:text-stone-700 text-xs cursor-pointer font-bold"
                            title="Hapus pencarian"
                        >
                            &times;
                        </button>
                    @endif
                </div>
            </div>

            <!-- Urutkan -->
            <div class="space-y-2 pt-2 border-t border-bone">
                <label class="text-[11px] uppercase tracking-wider text-neutral-500 font-medium block">Urutan</label>
                <select wire:model.live="sortBy" class="w-full text-xs border border-bone bg-white p-2.5 focus:border-obsidian focus:ring-0">
                    <option value="newest">Terbaru</option>
                    <option value="best_seller">Paling Banyak Terjual</option>
                    <option value="price_low">Harga: Terendah ke Tertinggi</option>
                    <option value="price_high">Harga: Tertinggi ke Terendah</option>
                </select>
            </div>

            <!-- Ketersediaan Stok -->
            <div class="space-y-2 pt-2 border-t border-bone">
                <label class="text-[11px] uppercase tracking-wider text-neutral-500 font-medium block">Ketersediaan</label>
                <div class="space-y-1.5 text-xs">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" wire:model.live="availability" value="" name="availability" class="text-obsidian focus:ring-0 border-neutral-300">
                        <span>Semua Produk</span>
                    </label>
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="radio" wire:model.live="availability" value="ready" name="availability" class="text-obsidian focus:ring-0 border-neutral-300">
                        <span>Hanya Stok Ready</span>
                    </label>
                </div>
            </div>

            <!-- Filter Bahan -->
            @if($availableMaterials->isNotEmpty())
                <div class="space-y-2 pt-2 border-t border-bone">
                    <label class="text-[11px] uppercase tracking-wider text-neutral-500 font-medium block">Bahan Hijab</label>
                    <select wire:model.live="material" class="w-full text-xs border border-bone bg-white p-2.5 focus:border-obsidian focus:ring-0">
                        <option value="">Semua Bahan</option>
                        @foreach($availableMaterials as $mat)
                            <option value="{{ $mat }}">{{ $mat }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <!-- Filter Warna -->
            @if($availableColors->isNotEmpty())
                <div class="space-y-3 pt-2 border-t border-bone">
                    <div class="flex items-center justify-between">
                        <label class="text-[11px] uppercase tracking-wider text-neutral-500 font-medium block">Warna</label>
                        @if($color)
                            <button wire:click="$set('color', '')" class="text-[10px] underline text-neutral-400 hover:text-obsidian">Clear</button>
                        @endif
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @foreach($availableColors as $c)
                            <button 
                                wire:click="$set('color', '{{ $color === $c->color ? '' : $c->color }}')"
                                title="{{ $c->color }}"
                                class="flex items-center gap-1.5 px-2.5 py-1 text-xs border {{ $color === $c->color ? 'border-obsidian bg-bone font-medium' : 'border-bone hover:border-neutral-400' }} transition-all"
                            >
                                @if($c->color_hex)
                                    <span class="w-2.5 h-2.5 rounded-none border border-neutral-300 inline-block" style="background-color: {{ $c->color_hex }};"></span>
                                @endif
                                <span class="text-[11px]">{{ $c->color }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Filter Rentang Harga -->
            <div class="space-y-2 pt-2 border-t border-bone">
                <label class="text-[11px] uppercase tracking-wider text-neutral-500 font-medium block">Rentang Harga (IDR)</label>
                <div class="grid grid-cols-2 gap-2 text-xs">
                    <input type="number" wire:model.live.debounce.400ms="minPrice" placeholder="Min" class="w-full border border-bone p-2 focus:border-obsidian focus:ring-0">
                    <input type="number" wire:model.live.debounce.400ms="maxPrice" placeholder="Maks" class="w-full border border-bone p-2 focus:border-obsidian focus:ring-0">
                </div>
            </div>
        </aside>

        <!-- Product Grid & Listing -->
        <main class="lg:col-span-3 space-y-8">
            <!-- Active Search Filter Indicator -->
            @if(!empty(trim($search)))
                <div class="flex items-center justify-between p-3.5 bg-sage-50/80 border border-sage-200/90 rounded-sm text-xs">
                    <div class="flex items-center space-x-2">
                        <span class="text-stone-500">Hasil pencarian untuk:</span>
                        <span class="font-medium text-obsidian bg-white px-2 py-0.5 border border-sage-200 rounded-xs">"{{ $search }}"</span>
                        <span class="text-stone-400 font-mono text-[11px]">({{ $products->total() }} produk ditemukan)</span>
                    </div>
                    <button 
                        type="button" 
                        wire:click="$set('search', '')" 
                        class="text-bloom hover:text-stone-900 font-medium text-[11px] uppercase tracking-wider flex items-center space-x-1 cursor-pointer"
                    >
                        <span>Hapus Pencarian</span>
                        <span>&times;</span>
                    </button>
                </div>
            @endif

            <!-- Loading Indicator -->
            <div wire:loading class="w-full py-4 text-center text-xs text-neutral-400 font-mono tracking-wider">
                MEMPERBARUI KATALOG...
            </div>

            <!-- Empty State -->
            @if($products->isEmpty())
                <div class="border border-bone p-16 text-center space-y-4 bg-paper rounded-sm">
                    @if(!empty(trim($search)))
                        <p class="text-sm text-neutral-700">Tidak ada produk yang cocok dengan pencarian <strong class="text-obsidian">"{{ $search }}"</strong>.</p>
                        <p class="text-xs text-neutral-500">Coba periksa ejaan, gunakan kata kunci yang lebih umum, atau reset filter.</p>
                    @else
                        <p class="text-sm text-neutral-600">Tidak ada produk yang sesuai dengan filter yang dipilih.</p>
                    @endif
                    <div class="pt-2">
                        <button wire:click="resetFilters" class="link-underlined text-xs uppercase tracking-wider font-medium cursor-pointer">
                            Reset Semua Filter
                        </button>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($products as $product)
                        @php
                            $firstVariant = $product->variants->first();
                            $minPrice = $product->variants->min('price');
                            $maxPrice = $product->variants->max('price');
                            $hasStock = $product->variants->contains(fn($v) => ($v->stock - $v->reserved) > 0);
                        @endphp
                        <article class="group flex flex-col justify-between border border-sage-200/60 hover:border-bloom transition-all p-3.5 bg-white/90 backdrop-blur-xs rounded-sm shadow-2xs hover:shadow-md">
                            <!-- Image Frame -->
                            <a href="{{ url('/produk/' . $product->slug) }}" class="aspect-[3/4] bg-stone-50 border border-stone-100 relative overflow-hidden block rounded-xs">
                                @php
                                    $catalogImg = $product->getFirstMediaUrl('images', 'preview') 
                                        ?: ($product->getFirstMediaUrl('images') ?: asset('images/product-placeholder.jpg'));
                                @endphp
                                <img 
                                    src="{{ $catalogImg }}" 
                                    alt="{{ $product->name }}" 
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                                    loading="lazy"
                                />

                                @if(!$hasStock)
                                    <div class="absolute inset-0 bg-paper/85 flex items-center justify-center">
                                        <span class="text-[11px] uppercase tracking-widest text-neutral-500 font-mono border border-neutral-300 px-3 py-1 bg-white">
                                             Stok Habis
                                        </span>
                                    </div>
                                @elseif($product->is_featured)
                                    <div class="absolute top-2 left-2">
                                        <span class="text-[9px] uppercase tracking-widest bg-obsidian text-paper px-2 py-0.5 font-medium">
                                            Unggulan
                                        </span>
                                    </div>
                                @endif
                            </a>

                            <!-- Caption & Pricing -->
                            <div class="mt-4 space-y-1.5">
                                <div class="flex items-start justify-between gap-2">
                                    <a href="{{ url('/produk/' . $product->slug) }}" class="text-xs font-normal text-obsidian hover:underline">
                                        {{ $product->name }}
                                    </a>
                                    <span class="text-xs font-mono font-medium text-obsidian whitespace-nowrap">
                                        @if($minPrice && $maxPrice && $minPrice !== $maxPrice)
                                            Rp {{ number_format($minPrice, 0, ',', '.') }} - {{ number_format($maxPrice, 0, ',', '.') }}
                                        @elseif($minPrice)
                                            Rp {{ number_format($minPrice, 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>

                                <p class="text-[11px] text-neutral-500 line-clamp-1">
                                    {{ $product->short_description }}
                                </p>

                                <!-- Variant Swatches -->
                                <div class="flex items-center space-x-1.5 pt-2">
                                    @foreach($product->variants->take(5) as $v)
                                        @if($v->color_hex)
                                            <span 
                                                class="w-3 h-3 border border-neutral-300 inline-block" 
                                                style="background-color: {{ $v->color_hex }};" 
                                                title="{{ $v->color }}{{ $v->size ? ' (' . $v->size . ')' : '' }}"
                                            ></span>
                                        @endif
                                    @endforeach
                                    @if($product->variants->count() > 5)
                                        <span class="text-[10px] text-neutral-400 font-mono">+{{ $product->variants->count() - 5 }}</span>
                                    @endif
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="pt-8 border-t border-bone">
                    {{ $products->links() }}
                </div>
            @endif
        </main>
    </div>
</div>
