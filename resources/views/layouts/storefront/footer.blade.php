<!-- Refined Atelier Colophon Footer (Kombinasi Hijau Botanic & Pink Bloom) -->
<footer class="mt-24 relative overflow-hidden bg-[#1c261c] text-stone-200">
    <!-- Distinct Top Marker Line (Kombinasi Hijau Botanic & Pink Bloom) -->
    <div class="h-1.5 w-full bg-gradient-to-r from-botanic via-bloom to-botanic"></div>

    <!-- Soft Ambient Atmospheric Background Glows -->
    <div class="absolute -top-12 right-0 w-96 h-96 bg-bloom/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-12 left-0 w-96 h-96 bg-botanic/20 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative z-10 w-full px-4 sm:px-8 lg:px-12 xl:px-16 pt-16 pb-12 sm:pt-20 sm:pb-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-16">
        <!-- Col 1: Brand & Narrative -->
        <div class="space-y-4">
            <a href="{{ url('/') }}" class="inline-block focus:outline-none" aria-label="Beranda BloomingKembang">
                <img src="{{ asset('images/logo-white.png') }}" alt="{{ config('app.name', 'BloomingKembang') }}" class="w-full max-w-[280px] h-auto object-contain transition-transform duration-300 hover:scale-105" />
            </a>
            <p class="text-stone-300 leading-relaxed text-xs sm:text-[13px] max-w-sm">
                Koleksi hijab bernapas lembut dan berkarakter elegan untuk muslimah bersahaja. Ditenun dari serat pilihan dengan ketelitian butik standar premium.
            </p>
        </div>

        <!-- Col 2: Koleksi Pilihan -->
        <div class="space-y-4">
            <h4 class="text-sm tracking-wider uppercase font-serif font-bold text-white flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-bloom shrink-0 shadow-[0_0_8px_rgba(184,84,107,0.6)]"></span>
                <span>Koleksi Pilihan</span>
            </h4>
            <ul class="space-y-2.5 text-stone-300 text-xs sm:text-[13px]">
                <li><a href="{{ url('/koleksi/hijab-segi-empat') }}" class="hover:text-blush-300 hover:underline transition-colors">Hijab Segi Empat</a></li>
                <li><a href="{{ url('/koleksi/pashmina') }}" class="hover:text-blush-300 hover:underline transition-colors">Pashmina Silk & Flowy</a></li>
                <li><a href="{{ url('/koleksi/bergo-daily') }}" class="hover:text-blush-300 hover:underline transition-colors">Bergo Daily & Khimar</a></li>
                <li><a href="{{ url('/koleksi/inner-dan-aksesoris') }}" class="hover:text-blush-300 hover:underline transition-colors">Inner Rajut & Aksesoris</a></li>
            </ul>
        </div>

        <!-- Col 3: Bantuan & Layanan -->
        <div class="space-y-4">
            <h4 class="text-sm tracking-wider uppercase font-serif font-bold text-white flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-sage-400 shrink-0 shadow-[0_0_8px_rgba(132,157,132,0.6)]"></span>
                <span>Bantuan & Layanan</span>
            </h4>
            <ul class="space-y-2.5 text-stone-300 text-xs sm:text-[13px]">
                <li><a href="{{ url('/lacak-pesanan') }}" class="hover:text-blush-300 hover:underline transition-colors font-medium text-white">Lacak Status Pesanan</a></li>
                <li><a href="{{ url('/faq') }}" class="hover:text-blush-300 hover:underline transition-colors">Pertanyaan Umum (FAQ)</a></li>
                <li><a href="{{ url('/kebijakan-retur') }}" class="hover:text-blush-300 hover:underline transition-colors">Kebijakan Garansi & Retur</a></li>
                <li><a href="{{ url('/syarat-dan-ketentuan') }}" class="hover:text-blush-300 hover:underline transition-colors">Syarat & Ketentuan</a></li>
                <li><a href="{{ url('/kebijakan-privasi') }}" class="hover:text-blush-300 hover:underline transition-colors">Kebijakan Privasi</a></li>
            </ul>
        </div>

        <!-- Col 4: Hubungi Kami -->
        <div class="space-y-4">
            <h4 class="text-sm tracking-wider uppercase font-serif font-bold text-white flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-bloom shrink-0 shadow-[0_0_8px_rgba(184,84,107,0.6)]"></span>
                <span>Hubungi Kami</span>
            </h4>
            <div class="text-stone-300 leading-relaxed text-xs sm:text-[13px] space-y-1.5">
                <p>WhatsApp: <a href="https://wa.me/6281234567890" target="_blank" class="text-sage-300 hover:text-blush-300 font-semibold transition-colors">+62 812-3456-7890</a></p>
                <p>Email: <a href="mailto:cs@bloomingkembang.com" class="text-sage-300 hover:text-blush-300 font-medium transition-colors">cs@bloomingkembang.com</a></p>
                <p class="text-stone-400 pt-1">Atelier Butik: Bandung, Jawa Barat</p>
            </div>
        </div>
    </div>

    <!-- Bottom Copyright Bar -->
    <div class="relative z-10 w-full px-4 sm:px-8 lg:px-12 xl:px-16 py-6 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between text-xs text-stone-400 gap-3 bg-black/25">
        <p>&copy; {{ date('Y') }} Blooming<span class="text-blush-300 italic font-serif">Kembang</span>. Hak cipta dilindungi.</p>
        <p class="tracking-wider text-sage-300 font-medium">Keanggunan Muslimah yang Bersahaja</p>
    </div>
</footer>
