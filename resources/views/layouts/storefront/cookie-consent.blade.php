<!-- Privacy & Analytics Cookie Consent Banner -->
<div 
    x-data="{ 
        showConsent: !localStorage.getItem('blooming_consent'),
        accept() {
            localStorage.setItem('blooming_consent', 'granted');
            this.showConsent = false;
            window.dispatchEvent(new CustomEvent('cookie-consent-closed'));
        },
        decline() {
            localStorage.setItem('blooming_consent', 'denied');
            this.showConsent = false;
            window.dispatchEvent(new CustomEvent('cookie-consent-closed'));
        }
    }"
    x-show="showConsent"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-y-4"
    x-transition:enter-end="opacity-100 transform translate-y-0"
    x-cloak
    class="fixed bottom-0 inset-x-0 z-50 p-4 sm:p-6"
>
    <div class="max-w-4xl mx-auto bg-obsidian text-paper p-4 sm:p-5 border-t-2 border-botanic border-x border-b border-bone/20 shadow-2xl flex flex-col sm:flex-row items-center justify-between gap-4 text-xs rounded-sm">
        <div class="space-y-1">
            <p class="font-medium tracking-wide flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-bloom"></span>
                <span>Pemberitahuan Cookie & Pengalaman Belanja</span>
            </p>
            <p class="text-[11px] text-neutral-400 leading-relaxed">
                Kami menggunakan cookie dan analitik anonim (GA4 & Meta Pixel) untuk meningkatkan kenyamanan navigasi dan rekomendasi produk pilihan.
                Baca <a href="{{ url('/kebijakan-privasi') }}" class="underline hover:text-bloom transition-colors">Kebijakan Privasi</a> kami.
            </p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <button 
                type="button" 
                @click="decline()" 
                class="px-4 py-2 border border-neutral-600 text-neutral-300 hover:text-white text-[11px] uppercase tracking-wider transition-colors cursor-pointer rounded-xs"
            >
                Tolak
            </button>
            <button 
                type="button" 
                @click="accept()" 
                class="px-5 py-2 bg-bloom text-white font-semibold text-[11px] uppercase tracking-wider hover:bg-blush-700 transition-colors shadow-sm cursor-pointer rounded-xs"
            >
                Setujui
            </button>
        </div>
    </div>
</div>

