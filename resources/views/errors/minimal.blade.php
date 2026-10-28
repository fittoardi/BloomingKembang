<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') — BloomingKembang</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#faf9f7] text-stone-800 font-sans antialiased selection:bg-blush-200 selection:text-blush-900 min-h-screen flex flex-col justify-between">
    <!-- Mini Header -->
    <header class="py-6 border-b border-stone-200/80 bg-white/80 backdrop-blur-md sticky top-0 z-10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 flex items-center justify-between">
            <a href="{{ url('/') }}" class="group flex items-center gap-1.5 text-lg uppercase tracking-[0.18em]">
                <span class="font-serif font-bold text-stone-900 group-hover:text-botanic transition-colors">Blooming</span>
                <span class="font-serif italic font-normal text-bloom">Kembang</span>
                <span class="w-1.5 h-1.5 rounded-full bg-botanic inline-block ml-0.5"></span>
            </a>
            <a href="{{ route('catalog.index') }}" class="text-xs tracking-wider uppercase font-medium text-stone-600 hover:text-botanic transition-colors">
                Koleksi Hijab &rarr;
            </a>
        </div>
    </header>

    <!-- Error Content Container -->
    <main class="flex-1 flex items-center justify-center px-4 py-16 sm:py-24">
        <div class="max-w-md w-full text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-blush-50 border border-blush-200/80 text-bloom mb-6 shadow-2xs">
                @yield('icon')
            </div>

            <p class="text-[11px] font-semibold tracking-[0.2em] text-bloom uppercase mb-2">
                Kode @yield('code')
            </p>

            <h1 class="font-serif text-3xl sm:text-4xl font-normal uppercase tracking-wider text-stone-900 mb-4">
                @yield('message')
            </h1>

            <p class="text-sm text-stone-600 leading-relaxed mb-8">
                @yield('description')
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ url('/') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2.5 rounded-xs bg-botanic text-white text-xs font-medium tracking-widest uppercase hover:bg-sage-700 transition-colors shadow-2xs">
                    Kembali ke Beranda
                </a>
                <a href="{{ route('catalog.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2.5 rounded-xs border border-stone-300 bg-white text-stone-800 text-xs font-medium tracking-widest uppercase hover:bg-stone-50 transition-colors">
                    Jelajahi Koleksi
                </a>
            </div>
        </div>
    </main>

    <!-- Mini Footer -->
    <footer class="py-6 border-t border-stone-200/60 text-center text-xs text-stone-500">
        <p>&copy; {{ date('Y') }} BloomingKembang. Keanggunan Muslimah yang Bersahaja.</p>
    </footer>
</body>
</html>
