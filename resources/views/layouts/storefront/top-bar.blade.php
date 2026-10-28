<!-- Refined Editorial Top Announcement & Category Bar -->
<div class="bg-[#1e241e] text-[#e6e2dc] text-xs tracking-wider border-b border-white/10 select-none">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-2 flex flex-wrap items-center justify-between gap-3 text-[11px] font-sans">
        <!-- Left: Quick Top Categories -->
        <nav aria-label="Kategori Utama" class="flex items-center space-x-3 sm:space-x-5 uppercase tracking-widest font-medium overflow-x-auto whitespace-nowrap scrollbar-none">
            <a href="{{ url('/') }}" class="hover:text-white transition-colors {{ request()->is('/') ? 'text-white font-semibold' : 'text-stone-300' }}">BERANDA</a>
            <span class="text-white/25 hidden sm:inline" aria-hidden="true">&bull;</span>
            <a href="{{ url('/koleksi/hijab-segi-empat') }}" class="hover:text-white transition-colors {{ request()->is('koleksi/hijab-segi-empat') ? 'text-white font-semibold' : 'text-stone-300' }}">SEGI EMPAT</a>
            <span class="text-white/25 hidden sm:inline" aria-hidden="true">&bull;</span>
            <a href="{{ url('/koleksi/pashmina') }}" class="hover:text-white transition-colors {{ request()->is('koleksi/pashmina') ? 'text-white font-semibold' : 'text-stone-300' }}">PASHMINA</a>
            <span class="text-white/25 hidden sm:inline" aria-hidden="true">&bull;</span>
            <a href="{{ url('/koleksi/bergo-daily') }}" class="hover:text-white transition-colors {{ request()->is('koleksi/bergo-daily') ? 'text-white font-semibold' : 'text-stone-300' }}">BERGO</a>
            <span class="text-white/25 hidden sm:inline" aria-hidden="true">&bull;</span>
            <a href="{{ url('/koleksi/inner-dan-aksesoris') }}" class="hover:text-white transition-colors {{ request()->is('koleksi/inner-dan-aksesoris') ? 'text-white font-semibold' : 'text-stone-300' }}">AKSESORIS</a>
        </nav>

        <!-- Right: Free Shipping & WhatsApp CS ticker -->
        <div class="hidden md:flex items-center space-x-4 text-[11px] text-stone-300">
            <span class="inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-sage-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                <span>GRATIS ONGKIR MIN. RP150RB &bull; KODE: <strong class="text-white font-mono bg-white/15 px-1.5 py-0.5 rounded-xs tracking-wider">BLOOMINGFIRST</strong></span>
            </span>
            <span class="text-white/25" aria-hidden="true">&bull;</span>
            <a href="https://wa.me/6281234567890" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 hover:text-white transition-colors">
                <svg class="w-3.5 h-3.5 text-emerald-400" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.751-2.874-2.502-2.961-2.617-.087-.116-.708-.94-.708-1.793s.448-1.273.607-1.446c.159-.173.346-.217.462-.217l.332.007c.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.087.072.188.014.304-.058.116-.087.188-.173.289l-.26.304c-.087.086-.177.18-.076.354.101.174.449.741.964 1.201.662.591 1.221.774 1.394.86.173.086.275.072.376-.044.101-.116.433-.506.549-.68.116-.173.231-.145.39-.087s1.011.477 1.184.564.289.13.332.202c.043.073.043.419-.101.824z"/>
                </svg>
                <span>CS WHATSAPP: 0812-3456-7890</span>
            </a>
        </div>
    </div>
</div>

