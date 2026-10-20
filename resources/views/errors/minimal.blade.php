<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') — BloomingKembang</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600|playfair-display:500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-stone-50 text-stone-800 font-sans antialiased selection:bg-rose-100 selection:text-rose-900 min-h-screen flex flex-col justify-between">
    <!-- Mini Header -->
    <header class="py-6 border-b border-stone-200/60 bg-white/70 backdrop-blur-md sticky top-0 z-10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 flex items-center justify-between">
            <a href="{{ url('/') }}" class="group flex items-center gap-2">
                <span class="font-serif text-2xl font-bold tracking-tight text-stone-900 group-hover:text-rose-700 transition">
                    Blooming<span class="text-rose-600 font-light italic">Kembang</span>
                </span>
            </a>
            <a href="{{ route('catalog.index') }}" class="text-xs tracking-wider uppercase font-medium text-stone-600 hover:text-rose-700 transition">
                Koleksi Hijab &rarr;
            </a>
        </div>
    </header>

    <!-- Error Content Container -->
    <main class="flex-1 flex items-center justify-center px-4 py-16 sm:py-24">
        <div class="max-w-md w-full text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-rose-50 border border-rose-100 text-rose-600 mb-6 shadow-sm">
                @yield('icon')
            </div>

            <p class="text-xs font-semibold tracking-widest text-rose-600 uppercase mb-2">
                Kode @yield('code')
            </p>

            <h1 class="font-serif text-3xl sm:text-4xl font-bold text-stone-900 tracking-tight mb-4">
                @yield('message')
            </h1>

            <p class="text-sm text-stone-600 leading-relaxed mb-8">
                @yield('description')
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ url('/') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2.5 rounded-full bg-stone-900 text-white text-xs font-medium tracking-wide uppercase hover:bg-rose-900 transition shadow-sm">
                    Kembali ke Beranda
                </a>
                <a href="{{ route('catalog.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2.5 rounded-full border border-stone-300 bg-white text-stone-800 text-xs font-medium tracking-wide uppercase hover:bg-stone-50 transition">
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
