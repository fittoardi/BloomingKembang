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
            class="link-underlined text-xs uppercase tracking-wider font-medium"
        >
            + Cari Produk Lain
        </a>
    </div>

    <!-- Flash message -->
    @if(session('status'))
        <div class="mb-6 p-4 border border-bone bg-bone text-xs text-obsidian flex justify-between items-center">
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <!-- Sidebar Navigation -->
        <aside class="space-y-2 text-xs uppercase tracking-wider">
            <nav class="space-y-1">
                <a href="{{ route('account.dashboard') }}" class="block p-3 border border-bone hover:border-neutral-300 transition-colors">
                    Dashboard
                </a>
                <a href="{{ url('/akun/pesanan') }}" class="block p-3 border border-bone hover:border-neutral-300 transition-colors">
                    Pesanan Saya
                </a>
                <a href="{{ url('/akun/alamat') }}" class="block p-3 border border-bone hover:border-neutral-300 transition-colors">
                    Buku Alamat
                </a>
                <a href="{{ url('/akun/wishlist') }}" class="block p-3 border border-obsidian bg-bone text-obsidian font-medium">
                    Wishlist
                </a>
                <a href="{{ route('profile.edit') }}" class="block p-3 border border-bone hover:border-neutral-300 transition-colors">
                    Pengaturan Profil
                </a>
            </nav>
        </aside>

        <!-- Wishlist Content -->
        <section class="md:col-span-3 space-y-6">
            @if($wishlists->isEmpty())
                <div class="border border-bone p-16 text-center bg-paper space-y-4">
                    <span class="text-xs uppercase font-mono tracking-widest text-neutral-400 block">
                        Wishlist Masih Kosong
                    </span>
                    <p class="text-xs text-neutral-500 max-w-sm mx-auto">
                        Simpan produk hijab yang Anda sukai dengan menekan ikon hati pada halaman produk agar mudah ditemukan kembali.
                    </p>
                    <div class="pt-2">
                        <a 
                            href="{{ url('/koleksi') }}" 
                            class="link-underlined text-xs uppercase tracking-wider font-medium"
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
                        <div class="border border-bone bg-paper p-4 flex flex-col justify-between group space-y-3">
                            <div class="space-y-3">
                                <!-- Photo Frame -->
                                <a href="{{ url('/produk/' . ($prod->slug ?? '')) }}" class="aspect-[3/4] bg-bone border border-bone relative overflow-hidden flex items-center justify-center block">
                                    @if($thumb)
                                        <img src="{{ $thumb }}" alt="{{ $prod->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" />
                                    @else
                                        <div class="space-y-1 text-center p-4">
                                            <span class="text-[10px] uppercase font-mono text-neutral-400 block">{{ $prod->category->name ?? 'Hijab' }}</span>
                                            <span class="text-xs font-medium text-obsidian block">{{ $prod->name }}</span>
                                        </div>
                                    @endif
                                </a>

                                <div>
                                    <span class="text-[10px] uppercase font-mono text-neutral-400 block">{{ $prod->category->name ?? 'Koleksi' }}</span>
                                    <h3 class="text-xs font-normal text-obsidian mt-0.5 line-clamp-1">{{ $prod->name }}</h3>
                                    @if($firstVar)
                                        <p class="font-mono text-xs font-medium text-obsidian mt-1">
                                            Rp {{ number_format($firstVar->price, 0, ',', '.') }}
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <div class="pt-3 border-t border-bone flex items-center justify-between text-xs">
                                <button 
                                    type="button" 
                                    wire:click="removeFromWishlist({{ $item->id }})"
                                    class="text-neutral-400 hover:text-red-600 text-[11px] transition-colors"
                                >
                                    Hapus
                                </button>
                                
                                @if($firstVar && ($firstVar->stock - $firstVar->reserved) > 0)
                                    <button 
                                        type="button"
                                        wire:click="addToCart({{ $firstVar->id }})"
                                        class="link-underlined text-[11px] uppercase tracking-wider font-medium"
                                    >
                                        + Keranjang
                                    </button>
                                @else
                                    <span class="text-[10px] uppercase font-mono text-neutral-400">Habis</span>
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
