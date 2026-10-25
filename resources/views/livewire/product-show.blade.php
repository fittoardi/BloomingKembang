<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16">
    <!-- Breadcrumb Nav -->
    <nav class="text-[11px] uppercase tracking-wider text-neutral-400 mb-8 flex items-center space-x-2">
        <a href="{{ url('/') }}" class="hover:text-obsidian transition-colors">Beranda</a>
        <span>/</span>
        <a href="{{ url('/koleksi') }}" class="hover:text-obsidian transition-colors">Koleksi</a>
        @if($product->category)
            <span>/</span>
            <a href="{{ url('/koleksi/' . $product->category->slug) }}" class="hover:text-obsidian transition-colors">{{ $product->category->name }}</a>
        @endif
        <span>/</span>
        <span class="text-obsidian font-medium truncate max-w-[200px] sm:max-w-none">{{ $product->name }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-16 items-start">
        <!-- Galeri Gambar Editorial & Multi-Image Gallery -->
        <div class="lg:col-span-7 space-y-4" x-data="{ zoomModal: false, activeZoomImg: '' }">
            @if($images->isNotEmpty())
                @php
                    $currentImage = $images[$activeImageIndex] ?? $images->first();
                @endphp
                <!-- Main Photo Showcase with Zoom on Click -->
                <div class="aspect-[3/4] bg-bone border border-bone relative overflow-hidden group">
                    <img 
                        src="{{ $currentImage->getUrl('preview') }}" 
                        alt="{{ $product->name }}" 
                        class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105 cursor-zoom-in"
                        @click="zoomModal = true; activeZoomImg = '{{ $currentImage->getUrl() }}'"
                    />

                    <!-- Zoom Icon Badge -->
                    <button 
                        type="button"
                        @click="zoomModal = true; activeZoomImg = '{{ $currentImage->getUrl() }}'"
                        class="absolute bottom-4 right-4 bg-paper/90 border border-bone px-3 py-1.5 text-[10px] uppercase font-mono tracking-wider text-obsidian shadow-sm hover:bg-white transition-colors flex items-center space-x-1.5"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                        </svg>
                        <span>Zoom Detail</span>
                    </button>

                    @if($availableStock <= 0)
                        <div class="absolute inset-0 bg-paper/85 flex items-center justify-center">
                            <span class="text-xs uppercase tracking-widest text-neutral-600 font-mono border border-neutral-400 px-4 py-2 bg-white">
                                Varian Ini Sedang Habis
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Thumbnail Carousel / Strip -->
                @if($images->count() > 1)
                    <div class="flex items-center space-x-3 overflow-x-auto pb-2 pt-1">
                        @foreach($images as $idx => $img)
                            <button 
                                type="button"
                                wire:click="selectImage({{ $idx }})"
                                class="w-16 h-20 sm:w-20 sm:h-24 flex-shrink-0 border transition-all overflow-hidden {{ $activeImageIndex === $idx ? 'border-obsidian ring-1 ring-obsidian' : 'border-bone opacity-70 hover:opacity-100' }}"
                            >
                                <img src="{{ $img->getUrl('thumb') }}" alt="Thumbnail {{ $idx + 1 }}" class="w-full h-full object-cover" />
                            </button>
                        @endforeach
                    </div>
                @endif
            @else
                <!-- Main Photo Showcase (Minimalist Editorial Typography Placeholder) -->
                <div class="aspect-[3/4] bg-bone border border-bone relative overflow-hidden flex flex-col items-center justify-center p-10 text-center">
                    <div class="space-y-4 max-w-sm">
                        <span class="text-xs uppercase tracking-[0.2em] text-neutral-400 font-mono block">
                            {{ $product->category->name ?? 'Hijab' }} &bull; {{ $product->material ?? 'Bahan Pilihan' }}
                        </span>
                        <h2 class="text-xl sm:text-2xl font-light text-obsidian tracking-wide">
                            {{ $product->name }}
                        </h2>
                        @if($selectedVariant)
                            <div class="pt-4 border-t border-neutral-300">
                                <span class="text-xs uppercase tracking-widest text-neutral-500 font-mono block">Varian Terpilih</span>
                                <span class="text-sm font-medium text-obsidian block mt-1">
                                    {{ $selectedVariant->color }}{{ $selectedVariant->size ? ' — ' . $selectedVariant->size : '' }}
                                </span>
                            </div>
                        @endif
                    </div>

                    @if($availableStock <= 0)
                        <div class="absolute inset-0 bg-paper/85 flex items-center justify-center">
                            <span class="text-xs uppercase tracking-widest text-neutral-600 font-mono border border-neutral-400 px-4 py-2 bg-white">
                                Varian Ini Sedang Habis
                            </span>
                        </div>
                    @endif
                </div>
            @endif

            <div class="text-[11px] text-neutral-400 font-mono text-center">
                Foto representatif kain & warna asli dalam pencahayaan natural.
            </div>

            <!-- Modal Zoom Gambar Resolusi Penuh -->
            <div 
                x-show="zoomModal" 
                x-cloak 
                x-transition.opacity
                class="fixed inset-0 z-50 bg-obsidian/90 flex items-center justify-center p-4"
                @keydown.escape.window="zoomModal = false"
            >
                <div class="relative max-w-4xl max-h-[90vh] bg-paper p-2" @click.away="zoomModal = false">
                    <button 
                        type="button" 
                        @click="zoomModal = false" 
                        class="absolute -top-10 right-0 text-paper text-sm uppercase tracking-widest font-mono hover:text-neutral-300"
                    >
                        Tutup [ESC]
                    </button>
                    <img :src="activeZoomImg" alt="{{ $product->name }}" class="max-h-[85vh] w-auto mx-auto object-contain" />
                </div>
            </div>
        </div>

        <!-- Kolom Informasi & Pemilihan Varian -->
        <div class="lg:col-span-5 space-y-8 bg-white/90 backdrop-blur-xs p-6 sm:p-8 border border-sage-200/80 rounded-sm shadow-2xs">
            <div>
                <span class="text-[10px] uppercase tracking-widest text-neutral-400 font-mono block">
                    {{ $product->category->name ?? 'Koleksi' }}
                </span>
                <h1 class="text-2xl sm:text-3xl font-normal text-obsidian tracking-tight mt-1">
                    {{ $product->name }}
                </h1>
                
                <!-- Harga & Harga Coret -->
                <div class="mt-4 flex items-baseline space-x-3">
                    @if($selectedVariant)
                        <span class="text-xl sm:text-2xl font-mono font-medium text-obsidian">
                            Rp {{ number_format($selectedVariant->price, 0, ',', '.') }}
                        </span>
                        @if($selectedVariant->compare_price && $selectedVariant->compare_price > $selectedVariant->price)
                            <span class="text-sm font-mono text-neutral-400 line-through">
                                Rp {{ number_format($selectedVariant->compare_price, 0, ',', '.') }}
                            </span>
                        @endif
                    @endif
                </div>

                <p class="text-xs sm:text-sm text-neutral-600 mt-4 leading-relaxed font-normal">
                    {{ $product->short_description }}
                </p>
            </div>

            <!-- Pilihan Varian Warna & Ukuran -->
            <div class="space-y-6 pt-6 border-t border-bone">
                <!-- Swatch Warna -->
                <div class="space-y-3">
                    <div class="flex items-center justify-between text-xs">
                        <span class="uppercase tracking-wider text-neutral-500 font-medium">Pilihan Warna:</span>
                        <span class="text-obsidian font-mono font-medium">{{ $selectedVariant?->color }}</span>
                    </div>
                    <div class="flex flex-wrap gap-2.5">
                        @foreach($product->variants as $variant)
                            @php
                                $isVarReady = ($variant->stock - $variant->reserved) > 0;
                            @endphp
                            <button 
                                type="button"
                                wire:click="selectVariant({{ $variant->id }})"
                                title="{{ $variant->color }} ({{ $variant->size }})"
                                class="flex items-center gap-2 px-3 py-1.5 text-xs border transition-all {{ $selectedVariantId === $variant->id ? 'border-obsidian bg-bone font-medium ring-1 ring-obsidian' : 'border-bone hover:border-neutral-400' }} {{ !$isVarReady ? 'opacity-60' : '' }}"
                            >
                                @if($variant->color_hex)
                                    <span class="w-3.5 h-3.5 rounded-none border border-neutral-300 inline-block" style="background-color: {{ $variant->color_hex }};"></span>
                                @endif
                                <span>{{ $variant->color }}</span>
                                @if($variant->size)
                                    <span class="text-[10px] text-neutral-400 font-mono">({{ $variant->size }})</span>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Indikator Stok -->
                <div class="space-y-1 text-xs">
                    <span class="uppercase tracking-wider text-neutral-500 font-medium block">Ketersediaan Stok:</span>
                    @if($availableStock > 10)
                        <span class="text-green-700 font-medium flex items-center space-x-1.5">
                            <span class="w-2 h-2 rounded-full bg-green-600 inline-block"></span>
                            <span>Tersedia (Ready Stock)</span>
                        </span>
                    @elseif($availableStock > 0)
                        <span class="text-amber-700 font-medium flex items-center space-x-1.5">
                            <span class="w-2 h-2 rounded-full bg-amber-500 inline-block"></span>
                            <span>Sisa {{ $availableStock }} pcs lagi</span>
                        </span>
                    @else
                        <span class="text-red-600 font-medium flex items-center space-x-1.5">
                            <span class="w-2 h-2 rounded-full bg-red-600 inline-block"></span>
                            <span>Habis untuk varian ini</span>
                        </span>
                    @endif
                </div>

                <!-- Jumlah (Quantity) & CTA Button -->
                <div class="space-y-4 pt-4 border-t border-bone">
                    <div class="flex items-center space-x-4">
                        <span class="text-xs uppercase tracking-wider text-neutral-500 font-medium">Jumlah:</span>
                        <div class="flex items-center border border-bone">
                            <button 
                                wire:click="decrementQuantity" 
                                type="button"
                                class="px-3 py-1 text-neutral-600 hover:text-obsidian disabled:opacity-30" 
                                {{ $quantity <= 1 ? 'disabled' : '' }}
                            >
                                -
                            </button>
                            <span class="px-4 py-1 font-mono text-xs">{{ $quantity }}</span>
                            <button 
                                wire:click="incrementQuantity" 
                                type="button"
                                class="px-3 py-1 text-neutral-600 hover:text-obsidian disabled:opacity-30"
                                {{ $quantity >= $availableStock ? 'disabled' : '' }}
                            >
                                +
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        @if($availableStock > 0)
                            <button 
                                type="button"
                                wire:click="addToCart"
                                class="flex-1 py-3.5 bg-obsidian text-paper text-xs uppercase tracking-widest font-medium hover:bg-neutral-800 transition-colors text-center"
                            >
                                Tambah ke Keranjang
                            </button>
                        @else
                            <button 
                                type="button" 
                                disabled 
                                class="flex-1 py-3.5 bg-neutral-200 text-neutral-400 text-xs uppercase tracking-widest font-medium cursor-not-allowed text-center"
                            >
                                Stok Habis
                            </button>
                        @endif

                        <!-- Wishlist Toggle Button -->
                        <button 
                            type="button"
                            wire:click="toggleWishlist"
                            title="{{ $isInWishlist ? 'Hapus dari Wishlist' : 'Simpan ke Wishlist' }}"
                            class="p-3.5 border border-bone hover:border-obsidian text-obsidian transition-colors flex items-center justify-center {{ $isInWishlist ? 'bg-bone text-red-600' : 'bg-paper text-neutral-400' }}"
                        >
                            <svg class="w-4 h-4 {{ $isInWishlist ? 'fill-current text-red-600' : 'stroke-current fill-none' }}" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tabs: Deskripsi, Bahan, Panduan Ukuran, Perawatan -->
            <div class="pt-6 border-t border-bone space-y-4">
                <div class="flex border-b border-bone text-xs">
                    <button 
                        wire:click="setTab('description')"
                        class="pb-2.5 px-3 border-b-2 font-medium transition-colors {{ $activeTab === 'description' ? 'border-obsidian text-obsidian' : 'border-transparent text-neutral-400 hover:text-obsidian' }}"
                    >
                        Deskripsi
                    </button>
                    <button 
                        wire:click="setTab('material')"
                        class="pb-2.5 px-3 border-b-2 font-medium transition-colors {{ $activeTab === 'material' ? 'border-obsidian text-obsidian' : 'border-transparent text-neutral-400 hover:text-obsidian' }}"
                    >
                        Bahan
                    </button>
                    <button 
                        wire:click="setTab('size_guide')"
                        class="pb-2.5 px-3 border-b-2 font-medium transition-colors {{ $activeTab === 'size_guide' ? 'border-obsidian text-obsidian' : 'border-transparent text-neutral-400 hover:text-obsidian' }}"
                    >
                        Ukuran
                    </button>
                    <button 
                        wire:click="setTab('care_guide')"
                        class="pb-2.5 px-3 border-b-2 font-medium transition-colors {{ $activeTab === 'care_guide' ? 'border-obsidian text-obsidian' : 'border-transparent text-neutral-400 hover:text-obsidian' }}"
                    >
                        Perawatan
                    </button>
                </div>

                <div class="text-xs sm:text-[13px] text-neutral-600 leading-relaxed min-h-[90px]">
                    @if($activeTab === 'description')
                        <div>
                            {!! $product->description ? nl2br(e($product->description)) : 'Deskripsi lengkap belum ditambahkan untuk produk ini.' !!}
                        </div>
                    @elseif($activeTab === 'material')
                        <div class="space-y-2">
                            <span class="font-medium text-obsidian block">Karakter Bahan:</span>
                            <p>{{ $product->material ?? 'Voal Ultrafine / Ceruty Babydoll' }}</p>
                            <p class="text-[11px] text-neutral-400">Tekstur lembut di kulit, mudah dibentuk, tidak mudah kusut, dan breathable.</p>
                        </div>
                    @elseif($activeTab === 'size_guide')
                        <div>
                            {!! $product->size_guide ? nl2br(e($product->size_guide)) : 'Standar ukuran: Segi empat 115 x 115 cm | Pashmina 180 x 75 cm | Jahit tepi rapi kualitas butik.' !!}
                        </div>
                    @elseif($activeTab === 'care_guide')
                        <div>
                            {!! $product->care_guide ? nl2br(e($product->care_guide)) : 'Disarankan mencuci manual dengan air dingin dan detergen lembut. Jangan diperas terlalu kuat. Setrika dengan suhu rendah hingga sedang.' !!}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Produk Terkait (Related Products) -->
    @if($relatedProducts->isNotEmpty())
        <div class="mt-20 pt-12 border-t border-bone">
            <div class="flex items-center justify-between mb-8 pb-3 border-b border-bone">
                <h2 class="text-xs uppercase tracking-widest font-medium text-obsidian">Produk Terkait Lainnya</h2>
                <a href="{{ url('/koleksi/' . ($product->category->slug ?? '')) }}" class="link-underlined text-xs uppercase tracking-wider">
                    Lihat Kategori Ini
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-8">
                @foreach($relatedProducts as $rel)
                    @php
                        $relMin = $rel->variants->min('price');
                    @endphp
                    <a href="{{ url('/produk/' . $rel->slug) }}" class="group block border border-transparent hover:border-bone p-3 bg-white transition-colors">
                        <div class="aspect-[3/4] bg-bone border border-bone flex flex-col items-center justify-center p-6 text-center space-y-2">
                            <span class="text-[10px] uppercase tracking-widest text-neutral-400 font-mono">{{ $rel->category->name ?? 'Hijab' }}</span>
                            <span class="text-xs font-medium text-obsidian">{{ $rel->name }}</span>
                        </div>
                        <div class="mt-3 flex items-center justify-between text-xs">
                            <span class="text-obsidian">{{ $rel->name }}</span>
                            <span class="font-mono">{{ $relMin ? 'Rp ' . number_format($relMin, 0, ',', '.') : '-' }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <!-- JSON-LD Structured Data Script -->
    <script type="application/ld+json">
        {!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>
    <script type="application/ld+json">
        {!! json_encode($breadcrumbJsonLd, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof window.trackEcommerce === 'function') {
                window.trackEcommerce('view_item', {
                    currency: 'IDR',
                    value: {{ (int) ($selectedVariant?->price ?? 0) }},
                    items: [{
                        item_id: '{{ $selectedVariant?->sku ?? $product->slug }}',
                        item_name: '{{ addslashes($product->name) }}',
                        item_category: '{{ addslashes($product->category?->name ?? "Hijab") }}',
                        price: {{ (int) ($selectedVariant?->price ?? 0) }},
                        quantity: 1
                    }]
                });
            }

            window.addEventListener('product-added-to-cart', function(event) {
                if (typeof window.trackEcommerce === 'function') {
                    const item = event.detail[0] || event.detail;
                    window.trackEcommerce('add_to_cart', {
                        currency: 'IDR',
                        value: item.price * item.quantity,
                        items: [item]
                    });
                }
            });
        });
    </script>
</div>
