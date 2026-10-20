<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20">
    <!-- Breadcrumb -->
    <nav class="text-[10px] tracking-widest uppercase font-mono text-neutral-400 mb-4 flex items-center space-x-2">
        <a href="{{ url('/') }}" class="hover:text-obsidian">Beranda</a>
        <span>/</span>
        <span class="text-obsidian">{{ $page->title }}</span>
    </nav>

    <!-- Header -->
    <div class="border-b border-bone pb-8 mb-10 space-y-2">
        <span class="text-[11px] uppercase font-mono tracking-widest text-neutral-400 block">Informasi & Kebijakan</span>
        <h1 class="text-2xl sm:text-4xl font-normal uppercase tracking-wider text-obsidian">
            {{ $page->title }}
        </h1>
        <p class="text-xs font-mono text-neutral-400">
            Terakhir diperbarui: {{ $page->updated_at->translatedFormat('d F Y') }}
        </p>
    </div>

    <!-- Body Content -->
    <div class="border border-bone bg-paper p-8 sm:p-12">
        <article class="prose prose-neutral max-w-none text-xs sm:text-sm text-neutral-700 leading-relaxed font-normal space-y-6 [&>h3]:text-sm [&>h3]:font-medium [&>h3]:text-obsidian [&>h3]:uppercase [&>h3]:tracking-wider [&>h3]:pt-4 [&>h3]:border-b [&>h3]:border-bone/60 [&>h3]:pb-2 [&>ul]:list-disc [&>ul]:pl-5 [&>ul]:space-y-1.5 [&>p]:leading-relaxed">
            {!! $page->body !!}
        </article>

        <div class="mt-12 pt-8 border-t border-bone flex flex-col sm:flex-row items-center justify-between gap-4 text-xs">
            <span class="text-neutral-500">
                Punya pertanyaan lebih lanjut? Hubungi <a href="https://wa.me/6281234567890" target="_blank" class="text-obsidian font-medium underline">Customer Care WhatsApp</a>
            </span>
            <a href="{{ url('/koleksi') }}" class="link-underlined uppercase tracking-wider text-[11px]">
                Kembali ke Koleksi &rarr;
            </a>
        </div>
    </div>
</div>
