<!-- Museum Minimal Colophon Footer -->
<footer class="border-t border-sage-200/60 bg-gradient-to-b from-white/80 via-sage-50/40 to-blush-50/50 backdrop-blur-xs mt-24 py-16 text-xs text-obsidian">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-12">
        <div class="space-y-3">
            <h4 class="text-xs tracking-widest uppercase font-medium">BloomingKembang</h4>
            <p class="text-neutral-600 leading-relaxed text-xs">
                Koleksi hijab bernapas lembut dan berkarakter elegan untuk muslimah bersahaja.
            </p>
        </div>

        <div class="space-y-3">
            <h4 class="text-xs tracking-widest uppercase font-medium">Koleksi</h4>
            <ul class="space-y-2 text-neutral-600 text-xs">
                <li><a href="{{ url('/koleksi/hijab-segi-empat') }}" class="hover:text-obsidian transition-colors">Hijab Segi Empat</a></li>
                <li><a href="{{ url('/koleksi/pashmina') }}" class="hover:text-obsidian transition-colors">Pashmina</a></li>
                <li><a href="{{ url('/koleksi/bergo-daily') }}" class="hover:text-obsidian transition-colors">Bergo Daily</a></li>
                <li><a href="{{ url('/koleksi/inner-dan-aksesoris') }}" class="hover:text-obsidian transition-colors">Inner & Aksesoris</a></li>
            </ul>
        </div>

        <div class="space-y-3">
            <h4 class="text-xs tracking-widest uppercase font-medium">Bantuan & Informasi</h4>
            <ul class="space-y-2 text-neutral-600 text-xs">
                <li><a href="{{ url('/lacak-pesanan') }}" class="hover:text-obsidian font-medium text-obsidian transition-colors">Lacak Pesanan</a></li>
                <li><a href="{{ url('/faq') }}" class="hover:text-obsidian transition-colors">FAQ</a></li>
                <li><a href="{{ url('/kebijakan-retur') }}" class="hover:text-obsidian transition-colors">Kebijakan Retur</a></li>
                <li><a href="{{ url('/syarat-dan-ketentuan') }}" class="hover:text-obsidian transition-colors">Syarat & Ketentuan</a></li>
                <li><a href="{{ url('/kebijakan-privasi') }}" class="hover:text-obsidian transition-colors">Kebijakan Privasi</a></li>
            </ul>
        </div>

        <div class="space-y-3">
            <h4 class="text-xs tracking-widest uppercase font-medium">Hubungi Kami</h4>
            <p class="text-neutral-600 leading-relaxed text-xs">
                WhatsApp: +62 812-3456-7890<br>
                Email: cs@bloomingkembang.com<br>
                Bandung, Jawa Barat
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-12 pt-8 border-t border-bone flex flex-col sm:flex-row items-center justify-between text-[11px] text-neutral-500">
        <p>&copy; {{ date('Y') }} BloomingKembang. Hak cipta dilindungi.</p>
        <p class="mt-2 sm:mt-0 tracking-widest uppercase">Paper-White Minimalist Storefront</p>
    </div>
</footer>

