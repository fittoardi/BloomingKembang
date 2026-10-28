<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Breadcrumb / Header -->
    <div class="border-b border-bone pb-6 mb-8 flex flex-col sm:flex-row sm:items-baseline justify-between gap-4">
        <div>
            <nav class="text-[10px] tracking-widest uppercase font-mono text-neutral-400 mb-2 flex items-center space-x-2">
                <a href="{{ route('account.dashboard') }}" class="hover:text-obsidian">Akun Saya</a>
                <span>/</span>
                <span class="text-obsidian">Wishlist</span>
            </nav>
            <h1 class="text-xl sm:text-2xl font-normal uppercase tracking-wider text-obsidian">
                Wishlist Produk Pilihan
            </h1>
        </div>
        <a 
            href="{{ url('/koleksi') }}" 
            class="text-botanic hover:text-bloom underline text-xs uppercase tracking-wider font-semibold transition-colors"
        >
            + Cari Produk Lain
        </a>
    </div>

    <!-- Flash message -->
    @if(session('status'))
        <div class="mb-6 p-4 border border-sage-200 bg-sage-50 text-xs text-botanic font-medium flex justify-between items-center rounded-sm">
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <!-- Sidebar Navigation -->
        <aside class="space-y-2 text-xs uppercase tracking-wider">
            <nav class="space-y-1">
                <a href="{{ route('account.dashboard') }}" class="block p-3 border border-sage-200/80 bg-white/70 hover:border-bloom hover:text-bloom text-stone-700 transition-colors rounded-xs">
                    Dashboard
                </a>
                <a href="{{ url('/akun/pesanan') }}" class="block p-3 border border-sage-200/80 bg-white/70 hover:border-bloom hover:text-bloom text-stone-700 transition-colors rounded-xs">
                    Pesanan Saya
                </a>
                <a href="{{ url('/akun/alamat') }}" class="block p-3 border border-sage-200/80 bg-white/70 hover:border-bloom hover:text-bloom text-stone-700 transition-colors rounded-xs">
                    Buku Alamat
                </a>
                <a href="{{ url('/akun/wishlist') }}" class="block p-3 border border-botanic bg-sage-50 text-botanic font-semibold rounded-xs">
                    Wishlist
                </a>
                <a href="{{ route('profile.edit') }}" class="block p-3 border border-sage-200/80 bg-white/70 hover:border-bloom hover:text-bloom text-stone-700 transition-colors rounded-xs">
                    Pengaturan Profil
                </a>
            </nav>
        </aside>

        <!-- Wishlist Content -->
        <section class="md:col-span-3 space-y-6">
            @if($wishlists->isEmpty())
                <div class="border border-sage-200/90 p-16 text-center bg-white/95 rounded-lg shadow-2xs space-y-4">
                    <span class="text-xs uppercase font-mono tracking-widest text-stone-400 block">
                        Wishlist Masih Kosong
                    </span>
                    <p class="text-xs text-stone-500 max-w-sm mx-auto">
                        Simpan produk hijab yang Anda sukai dengan menekan ikon hati pada halaman produk agar mudah ditemukan kembali.
                    </p>
                    <div class="pt-2">
                        <a 
                            href="{{ url('/koleksi') }}" 
                            class="text-botanic hover:text-bloom underline text-xs uppercase tracking-wider font-semibold transition-colors"
                        >
                            Jelajahi Koleksi Sekarang
                        </a>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($wishlists as $item)
                        @php
                            $prod = $item->product;
                            $firstVar = $prod?->variants->first();
                            $thumb = $prod?->getFirstMediaUrl('images', 'thumb');
                        @endphp
                        <div class="border border-sage-200/90 bg-white/95 hover:border-bloom transition-all rounded-lg p-4 flex flex-col justify-between group space-y-3 shadow-2xs hover:shadow-md">
                            <div class="space-y-3">
                                <!-- Photo Frame -->
                                <a href="{{ url('/produk/' . ($prod->slug ?? '')) }}" class="aspect-[3/4] bg-sage-50/50 border border-sage-200/80 relative overflow-hidden flex items-center justify-center block rounded-xs">
                                    @if($thumb)
                                        <img src="{{ $thumb }}" alt="{{ $prod->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                                    @else
                                        <div class="space-y-1 text-center p-4">
                                            <span class="text-[10px] uppercase font-mono text-stone-400 block">{{ $prod->category->name ?? 'Hijab' }}</span>
                                            <span class="text-xs font-semibold text-stone-900 block">{{ $prod->name }}</span>
                                        </div>
                                    @endif
                                </a>

                                <div>
                                    <span class="text-[10px] uppercase font-mono text-stone-400 block">{{ $prod->category->name ?? 'Koleksi' }}</span>
                                    <h3 class="text-xs font-semibold text-stone-900 group-hover:text-bloom transition-colors mt-0.5 line-clamp-1">{{ $prod->name }}</h3>
                                    @if($firstVar)
                                        <p class="font-mono text-xs font-bold text-botanic mt-1">
                                            Rp {{ number_format($firstVar->price, 0, ',', '.') }}
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <div class="pt-3 border-t border-sage-100 flex items-center justify-between text-xs">
                                <button 
                                    type="button" 
                                    wire:click="removeFromWishlist({{ $item->id }})"
                                    class="text-stone-400 hover:text-bloom text-[11px] transition-colors"
                                >
                                    Hapus
                                </button>
                                
                                @if($firstVar && ($firstVar->stock - $firstVar->reserved) > 0)
                                    <button 
                                        type="button"
                                        wire:click="addToCart({{ $firstVar->id }})"
                                        class="text-botanic hover:text-bloom underline text-[11px] uppercase tracking-wider font-semibold transition-colors"
                                    >
                                        + Keranjang
                                    </button>
                                @else
                                    <span class="text-[10px] uppercase font-mono text-stone-400">Habis</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="pt-6">
                    {{ $wishlists->links() }}
                </div>
            @endif
        </section>
    </div>
</div>
