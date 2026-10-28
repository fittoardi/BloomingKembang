<!-- Minimal Header Navigation -->
<header x-data class="border-b border-blush-200/60 bg-white/85 backdrop-blur-md sticky top-0 z-40 transition-colors shadow-[0_2px_12px_rgba(78,103,78,0.03)]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
        <!-- Left Nav Items -->
        <nav class="hidden md:flex items-center space-x-8 text-xs uppercase tracking-widest" aria-label="Menu Utama">
            <a href="{{ url('/') }}" class="hover:text-bloom transition-colors {{ request()->is('/') ? 'text-botanic font-semibold border-b-2 border-botanic pb-1' : 'text-stone-700' }}">Beranda</a>
            <a href="{{ url('/koleksi') }}" class="hover:text-bloom transition-colors {{ request()->is('koleksi*') ? 'text-botanic font-semibold border-b-2 border-botanic pb-1' : 'text-stone-700' }}">Koleksi</a>
            <a href="{{ url('/tentang-kami') }}" class="hover:text-bloom transition-colors {{ request()->is('tentang-kami') ? 'text-botanic font-semibold border-b-2 border-botanic pb-1' : 'text-stone-700' }}">Tentang</a>
        </nav>

        <!-- Center Brand Colophon -->
        <div class="text-center">
            <a href="{{ url('/') }}" class="group inline-flex items-center gap-2 text-base sm:text-lg uppercase tracking-[0.18em]" aria-label="Beranda BloomingKembang">
                <svg class="w-6 h-6 sm:w-7 sm:h-7 group-hover:rotate-12 transition-transform duration-300 flex-shrink-0" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="24" cy="24" r="22" fill="#FDF7F8" stroke="#B8546B" stroke-width="1.5"/>
                    <path d="M24 9 C26.5 15, 31 18, 31 23 C31 27.5, 27.5 31, 24 31 C20.5 31, 17 27.5, 17 23 C17 18, 21.5 15, 24 9 Z" fill="#B8546B"/>
                    <path d="M13 20 C18 18.5, 22 22, 23 26 C20 29, 16 29, 13.5 26.5 C11.5 24.5, 11.5 21.5, 13 20 Z" fill="#D48B9B"/>
                    <path d="M35 20 C30 18.5, 26 22, 25 26 C28 29, 32 29, 34.5 26.5 C36.5 24.5, 36.5 21.5, 35 20 Z" fill="#D48B9B"/>
                    <circle cx="24" cy="23" r="2.8" fill="#FFF8F6" stroke="#4E674E" stroke-width="1.2"/>
                    <path d="M24 31 C24 36, 21.5 39, 18 40 C20.5 37, 22.5 35, 24 31 Z" fill="#4E674E"/>
                    <path d="M24 31 C24 36, 26.5 39, 30 40 C27.5 37, 25.5 35, 24 31 Z" fill="#6A856A"/>
                </svg>
                <span class="inline-flex items-center gap-0.5">
                    <span class="font-serif tracking-normal text-obsidian font-bold">Blooming</span>
                    <span class="font-serif italic font-normal text-bloom tracking-normal">Kembang</span>
                    <span class="w-1.5 h-1.5 rounded-full bg-botanic inline-block ml-1 group-hover:scale-125 transition-transform"></span>
                </span>
            </a>
        </div>

        <!-- Right Utility Links -->
        <div class="flex items-center space-x-5 text-xs uppercase tracking-widest">
            <!-- Quick Search Bar -->
            <form action="{{ url('/koleksi') }}" method="GET" class="hidden sm:flex items-center relative">
                <input 
                    type="text" 
                    name="cari" 
                    value="{{ request('cari') }}" 
                    placeholder="Cari koleksi..." 
                    class="text-[11px] font-sans border border-sage-200/90 rounded-full pl-7 pr-3 py-1 bg-white/80 focus:bg-white focus:border-botanic focus:ring-0 placeholder-stone-400 w-28 focus:w-44 transition-all duration-300"
                    aria-label="Cari koleksi produk"
                />
                <svg class="w-3.5 h-3.5 text-stone-400 absolute left-2.5 top-2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </form>

            @auth
                @if(auth()->user()->hasAnyRole(['super_admin', 'admin', 'warehouse_staff']))
                    <a href="{{ url('/admin') }}" class="link-underlined text-botanic font-medium">Panel Admin</a>
                @endif
                <a href="{{ route('account.dashboard') }}" class="hover:text-bloom transition-colors text-stone-700">Akun Saya</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="hover:text-bloom uppercase tracking-widest transition-colors text-stone-500 cursor-pointer">Keluar</button>
                </form>
                <button 
                    type="button" 
                    @click="$dispatch('open-cart')" 
                    onclick="window.dispatchEvent(new CustomEvent('open-cart'))"
                    class="hover:text-bloom transition-colors uppercase tracking-widest flex items-center space-x-1.5 cursor-pointer text-stone-800"
                    aria-label="Buka Keranjang Belanja"
                >
                    <span>Keranjang</span>
                    <span class="font-mono text-[10px] font-semibold px-2 py-0.5 rounded-full bg-blush-100 text-blush-800 border border-blush-200">
                        {{ app(\App\Services\CartService::class)->getCount() }}
                    </span>
                </button>
            @else
                <a href="{{ route('login') }}" class="hover:text-bloom transition-colors text-stone-700">Masuk</a>
                <a href="{{ route('register') }}" class="px-3 py-1 rounded-full border border-blush-300 text-bloom hover:bg-blush-50 transition-colors font-medium">Daftar</a>
            @endauth
        </div>
    </div>
</header>

