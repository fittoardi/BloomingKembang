<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20">
    <!-- Breadcrumb -->
    <nav class="text-[11px] tracking-wider uppercase text-stone-500 mb-4 flex items-center space-x-2">
        <a href="{{ url('/') }}" class="hover:text-bloom transition-colors">Beranda</a>
        <span>/</span>
        <span class="text-botanic font-medium">{{ $page->title }}</span>
    </nav>

    <!-- Header -->
    <div class="border-b border-sage-200/80 pb-8 mb-10 space-y-2">
        <span class="text-[11px] uppercase tracking-[0.2em] text-botanic font-semibold block flex items-center gap-1.5">
            <span class="w-1.5 h-1.5 rounded-full bg-bloom"></span>
            <span>Informasi & Kebijakan</span>
        </span>
        <h1 class="text-2xl sm:text-4xl font-serif font-normal uppercase tracking-wider text-obsidian">
            {{ $page->title }}
        </h1>
        <p class="text-xs text-stone-500">
            Terakhir diperbarui: {{ $page->updated_at->translatedFormat('d F Y') }}
        </p>
    </div>

    <!-- Body Content -->
    <div class="border border-sage-200/90 bg-white/95 backdrop-blur-xs p-8 sm:p-12 rounded-lg shadow-2xs">
        <article class="prose prose-neutral max-w-none text-xs sm:text-sm text-neutral-700 leading-relaxed font-normal space-y-6 [&>h3]:text-sm [&>h3]:font-serif [&>h3]:font-semibold [&>h3]:text-botanic [&>h3]:uppercase [&>h3]:tracking-wider [&>h3]:pt-4 [&>h3]:border-b [&>h3]:border-sage-200/80 [&>h3]:pb-2 [&>ul]:list-disc [&>ul]:pl-5 [&>ul]:space-y-1.5 [&>p]:leading-relaxed">
            {!! $page->body !!}
        </article>

        <div class="mt-12 pt-8 border-t border-sage-100 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs">
            <span class="text-neutral-500">
                Punya pertanyaan lebih lanjut? Hubungi <a href="https://wa.me/6281234567890" target="_blank" class="text-botanic hover:text-bloom font-semibold underline transition-colors">Customer Care WhatsApp</a>
            </span>
            <a href="{{ url('/koleksi') }}" class="text-xs uppercase tracking-wider text-botanic hover:text-bloom underline font-semibold transition-colors">
                Kembali ke Koleksi &rarr;
            </a>
        </div>
    </div>
</div>
