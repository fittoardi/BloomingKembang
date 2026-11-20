<x-storefront-layout title="BloomingKembang — Koleksi Hijab Elegan & Bersahaja">
    @php
        $featuredProducts = \App\Models\Product::with(['category', 'variants'])
            ->where('status', \App\Enums\ProductStatus::Active)
            ->orderBy('sold_count', 'desc')
            ->take(6)
            ->get();
    @endphp

    <!-- 1. CIRCULAR CATEGORY SHORTCUTS (HIJAU BOTANIC & PINK BLOOM) -->
    <section class="w-full px-4 sm:px-8 lg:px-12 xl:px-16 py-8 sm:py-10 border-b border-stone-200/80">
        @php
            $catShortcuts = [
                ['url' => '/koleksi/hijab-segi-empat', 'title' => 'Segi Empat', 'img' => 'images/home/cat-segi-empat.jpg', 'icon' => 'M4 4h16v16H4z', 'bg' => 'bg-sage-100 text-botanic'],
                ['url' => '/koleksi/pashmina', 'title' => 'Pashmina', 'img' => 'images/home/cat-pashmina.jpg', 'icon' => 'M7 4h10v16H7z', 'bg' => 'bg-blush-100 text-bloom'],
                ['url' => '/koleksi/bergo-daily', 'title' => 'Bergo Daily', 'img' => 'images/products/bergo-daily.jpg', 'icon' => 'M12 2a5 5 0 00-5 5v3H6a2 2 0 00-2 2v6a2 2 0 002 2h12a2 2 0 002-2v-6a2 2 0 00-2-2h-1V7a5 5 0 00-5-5z', 'bg' => 'bg-sage-100 text-botanic'],
                ['url' => '/koleksi', 'title' => 'Khimar Syar\'i', 'img' => 'images/products/khimar-syari.jpg', 'icon' => 'M12 3L2 12h3v8h14v-8h3L12 3z', 'bg' => 'bg-blush-100 text-bloom'],
                ['url' => '/koleksi/inner-dan-aksesoris', 'title' => 'Inner & Klip', 'img' => 'images/home/cat-inner-aksesoris.jpg', 'icon' => 'M12 4v16m8-8H4', 'bg' => 'bg-sage-100 text-botanic'],
                ['url' => '/koleksi', 'title' => 'Koleksi Pilihan', 'img' => 'images/home/elzatta-hero-loyalty.jpg', 'icon' => 'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z', 'bg' => 'bg-blush-100 text-bloom'],
            ];
        @endphp
        <div class="grid grid-cols-3 sm:grid-cols-6 gap-4 sm:gap-6 lg:gap-8 xl:gap-12 text-center">
            @foreach($catShortcuts as $cat)
                <a href="{{ url($cat['url']) }}" class="group flex flex-col items-center space-y-2">
                    <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full overflow-hidden border-2 border-stone-200 group-hover:border-bloom shadow-xs group-hover:shadow-md transition-all duration-300 p-0.5 bg-white flex items-center justify-center">
                        @if(file_exists(public_path($cat['img'])))
                            <img src="{{ asset($cat['img']) }}" alt="{{ $cat['title'] }}" class="w-full h-full object-cover rounded-full group-hover:scale-110 transition-transform duration-500" loading="lazy" />
                        @else
                            <div class="w-full h-full rounded-full flex items-center justify-center {{ $cat['bg'] }} transition-transform duration-300 group-hover:scale-110">
                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="{{ $cat['icon'] }}" /></svg>
                            </div>
                        @endif
                    </div>
                    <span class="text-xs font-semibold text-stone-800 uppercase tracking-wider group-hover:text-botanic transition-colors">{{ $cat['title'] }}</span>
                </a>
            @endforeach
        </div>
    </section>

    <!-- 2. DESIGNED FOR EVERYDAY MODESTY (DUAL-TONE ACCENT CARDS) -->
    <section class="w-full px-4 sm:px-8 lg:px-12 xl:px-16 py-8 sm:py-12">
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
                    src="{{ file_exists(public_path('images/home/modesty-group.jpg')) ? asset('images/home/modesty-group.jpg') : asset('images/product-placeholder.jpg') }}" 
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
                    src="{{ file_exists(public_path('images/home/hero-editorial.jpg')) ? asset('images/home/hero-editorial.jpg') : asset('images/product-placeholder.jpg') }}" 
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

    <!-- 3. CURATED BEST SELLERS CATALOG GRID (KOMBINASI BOTANIC & BLOOM) -->
    <section class="w-full px-4 sm:px-8 lg:px-12 xl:px-16 py-14">
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

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 sm:gap-8">
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

    <!-- 4. 4-PILLAR VALUE PROPOSITIONS (HARMONISASI ALTERNASI BOTANIC & BLOOM) -->
    <section class="border-y border-sage-200/80 bg-white/70 backdrop-blur-xs py-12">
        <div class="w-full px-4 sm:px-8 lg:px-12 xl:px-16">
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

    <!-- 5. TESTIMONIALS / SUARA PELANGGAN (KOMBINASI BOTANIC & BLOOM) -->
    <section class="w-full px-4 sm:px-8 lg:px-12 xl:px-16 py-16">
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

    <!-- 6. NEWSLETTER & ATELIER UPDATES (SOFT BOTANIC & BLOOM GRADIENT) -->
    <section class="border-t border-sage-200/80 bg-gradient-to-r from-sage-50/70 via-white to-blush-50/70 py-16">
        <div class="max-w-4xl mx-auto px-4 text-center space-y-6" x-data="{ email: '', submitted: false }">
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1 rounded-full bg-white border border-sage-200 text-botanic text-[10px] uppercase tracking-[0.18em] font-medium shadow-2xs">
                <span class="w-1.5 h-1.5 rounded-full bg-bloom"></span>
                Warta & Peluncuran Koleksi
            </span>

            <div class="space-y-2">
                <h3 class="text-xl sm:text-3xl font-serif font-normal uppercase tracking-wider text-stone-900">
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
                        <p class="text-stone-600 text-[11px]">Kupon sambutan 10% Anda: <strong class="font-mono text-stone-900 uppercase">RAMADHAN20</strong>. Gunakan saat melakukan checkout pesanan pertama Anda.</p>
                    </div>
                </template>

                <span class="text-[11px] text-stone-400 mt-2 block">
                    Kami menghargai privasi Anda. Bebas berhenti berlangganan kapan saja.
                </span>
            </div>
        </div>
    </section>
</x-storefront-layout>
