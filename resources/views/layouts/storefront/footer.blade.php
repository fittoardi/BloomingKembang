<!-- Refined Atelier Colophon Footer (Kombinasi Hijau Botanic & Pink Bloom) -->
<footer class="border-t border-sage-200/80 bg-gradient-to-b from-transparent via-sage-50/50 to-blush-50/40 mt-28 pt-20 pb-12 sm:pt-24 sm:pb-16 text-stone-800">
    <div class="w-full px-4 sm:px-8 lg:px-12 xl:px-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-16">
        <div class="space-y-4">
            <a href="{{ url('/') }}" class="inline-block focus:outline-none" aria-label="Beranda BloomingKembang">
                <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name', 'BloomingKembang') }}" class="h-10 sm:h-12 w-auto object-contain transition-transform duration-300 hover:scale-105" />
            </a>
            <p class="text-stone-600 leading-relaxed text-xs sm:text-[13px] max-w-sm">
                Koleksi hijab bernapas lembut dan berkarakter elegan untuk muslimah bersahaja. Ditenun dari serat pilihan dengan ketelitian butik standar premium.
            </p>
        </div>

        <div class="space-y-4">
            <h4 class="text-sm tracking-wider uppercase font-serif font-bold text-stone-900 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-bloom shrink-0"></span>
                <span>Koleksi Pilihan</span>
            </h4>
            <ul class="space-y-2.5 text-stone-600 text-xs sm:text-[13px]">
                <li><a href="{{ url('/koleksi/hijab-segi-empat') }}" class="hover:text-bloom hover:underline transition-colors">Hijab Segi Empat</a></li>
                <li><a href="{{ url('/koleksi/pashmina') }}" class="hover:text-bloom hover:underline transition-colors">Pashmina Silk & Flowy</a></li>
                <li><a href="{{ url('/koleksi/bergo-daily') }}" class="hover:text-bloom hover:underline transition-colors">Bergo Daily & Khimar</a></li>
                <li><a href="{{ url('/koleksi/inner-dan-aksesoris') }}" class="hover:text-bloom hover:underline transition-colors">Inner Rajut & Aksesoris</a></li>
            </ul>
        </div>

        <div class="space-y-4">
            <h4 class="text-sm tracking-wider uppercase font-serif font-bold text-stone-900 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-botanic shrink-0"></span>
                <span>Bantuan & Layanan</span>
            </h4>
            <ul class="space-y-2.5 text-stone-600 text-xs sm:text-[13px]">
                <li><a href="{{ url('/lacak-pesanan') }}" class="hover:text-bloom hover:underline transition-colors font-medium text-stone-900">Lacak Status Pesanan</a></li>
                <li><a href="{{ url('/faq') }}" class="hover:text-bloom hover:underline transition-colors">Pertanyaan Umum (FAQ)</a></li>
                <li><a href="{{ url('/kebijakan-retur') }}" class="hover:text-bloom hover:underline transition-colors">Kebijakan Garansi & Retur</a></li>
                <li><a href="{{ url('/syarat-dan-ketentuan') }}" class="hover:text-bloom hover:underline transition-colors">Syarat & Ketentuan</a></li>
                <li><a href="{{ url('/kebijakan-privasi') }}" class="hover:text-bloom hover:underline transition-colors">Kebijakan Privasi</a></li>
            </ul>
        </div>

        <div class="space-y-4">
            <h4 class="text-sm tracking-wider uppercase font-serif font-bold text-stone-900 flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-bloom shrink-0"></span>
                <span>Hubungi Kami</span>
            </h4>
            <div class="text-stone-600 leading-relaxed text-xs sm:text-[13px] space-y-1.5">
                <p>WhatsApp: <a href="https://wa.me/6281234567890" target="_blank" class="text-botanic hover:text-bloom font-semibold transition-colors">+62 812-3456-7890</a></p>
                <p>Email: <a href="mailto:cs@bloomingkembang.com" class="text-botanic hover:text-bloom font-medium transition-colors">cs@bloomingkembang.com</a></p>
                <p class="text-stone-500 pt-1">Atelier Butik: Bandung, Jawa Barat</p>
            </div>
        </div>
    </div>

    <div class="w-full px-4 sm:px-8 lg:px-12 xl:px-16 mt-16 pt-8 border-t border-sage-200/80 flex flex-col sm:flex-row items-center justify-between text-xs text-stone-500 gap-3">
        <p>&copy; {{ date('Y') }} Blooming<span class="text-bloom italic font-serif">Kembang</span>. Hak cipta dilindungi.</p>
        <p class="tracking-wider text-botanic font-medium">Keanggunan Muslimah yang Bersahaja</p>
    </div>
</footer>

