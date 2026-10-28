<!-- Refined Atelier Colophon Footer -->
<footer class="border-t border-stone-200/80 bg-white/60 mt-24 py-16 text-xs text-stone-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-12">
        <div class="space-y-3">
            <h4 class="text-xs tracking-widest uppercase font-semibold text-stone-900">BloomingKembang</h4>
            <p class="text-stone-500 leading-relaxed text-xs">
                Koleksi hijab bernapas lembut dan berkarakter elegan untuk muslimah bersahaja. Ditenun dari serat pilihan dengan ketelitian butik.
            </p>
        </div>

        <div class="space-y-3">
            <h4 class="text-xs tracking-widest uppercase font-semibold text-stone-900">Koleksi</h4>
            <ul class="space-y-2 text-stone-600 text-xs">
                <li><a href="{{ url('/koleksi/hijab-segi-empat') }}" class="hover:text-bloom transition-colors">Hijab Segi Empat</a></li>
                <li><a href="{{ url('/koleksi/pashmina') }}" class="hover:text-bloom transition-colors">Pashmina Silk & Flowy</a></li>
                <li><a href="{{ url('/koleksi/bergo-daily') }}" class="hover:text-bloom transition-colors">Bergo Daily & Khimar</a></li>
                <li><a href="{{ url('/koleksi/inner-dan-aksesoris') }}" class="hover:text-bloom transition-colors">Inner Rajut & Aksesoris</a></li>
            </ul>
        </div>

        <div class="space-y-3">
            <h4 class="text-xs tracking-widest uppercase font-semibold text-stone-900">Bantuan & Layanan</h4>
            <ul class="space-y-2 text-stone-600 text-xs">
                <li><a href="{{ url('/lacak-pesanan') }}" class="hover:text-bloom transition-colors font-medium text-stone-900">Lacak Status Pesanan</a></li>
                <li><a href="{{ url('/faq') }}" class="hover:text-bloom transition-colors">Pertanyaan Umum (FAQ)</a></li>
                <li><a href="{{ url('/kebijakan-retur') }}" class="hover:text-bloom transition-colors">Kebijakan Garansi & Retur</a></li>
                <li><a href="{{ url('/syarat-dan-ketentuan') }}" class="hover:text-bloom transition-colors">Syarat & Ketentuan</a></li>
                <li><a href="{{ url('/kebijakan-privasi') }}" class="hover:text-bloom transition-colors">Kebijakan Privasi</a></li>
            </ul>
        </div>

        <div class="space-y-3">
            <h4 class="text-xs tracking-widest uppercase font-semibold text-stone-900">Hubungi Kami</h4>
            <p class="text-stone-600 leading-relaxed text-xs">
                WhatsApp: +62 812-3456-7890<br>
                Email: cs@bloomingkembang.com<br>
                Atelier: Bandung, Jawa Barat
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 pt-8 border-t border-stone-200/60 flex flex-col sm:flex-row items-center justify-between text-[11px] text-stone-500">
        <p>&copy; {{ date('Y') }} BloomingKembang. Hak cipta dilindungi.</p>
        <p class="mt-2 sm:mt-0 tracking-wider text-stone-400">Keanggunan Muslimah yang Bersahaja</p>
    </div>
</footer>

