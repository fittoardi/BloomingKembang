<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ $title ?? 'BloomingKembang — Koleksi Hijab Elegan & Bersahaja' }}</title>

<!-- Favicon -->
<link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}?v=3">
<link rel="icon" type="image/png" sizes="64x64" href="{{ asset('favicon.png') }}?v=3">
<link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=3">

<!-- Canonical & SEO Meta Tags -->
<link rel="canonical" href="{{ url()->current() }}" />
<meta name="description" content="{{ $metaDescription ?? 'Koleksi hijab bernapas lembut dan berkarakter elegan untuk muslimah bersahaja. Ditenun dari serat voal ultrafine dan ceruty babydoll pilihan.' }}">
<meta name="keywords" content="hijab, kerudung, pashmina, voal ultrafine, hijab segi empat, inner rajut, fashion muslimah, BloomingKembang">
<meta name="author" content="BloomingKembang">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:title" content="{{ $title ?? 'BloomingKembang — Koleksi Hijab Elegan & Bersahaja' }}">
<meta property="og:description" content="{{ $metaDescription ?? 'Koleksi hijab bernapas lembut dan berkarakter elegan untuk muslimah bersahaja.' }}">
<meta property="og:site_name" content="BloomingKembang">
<meta property="og:image" content="{{ $ogImage ?? asset('images/og-bloomingkembang.jpg') }}">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="{{ url()->current() }}">
<meta name="twitter:title" content="{{ $title ?? 'BloomingKembang — Koleksi Hijab Elegan & Bersahaja' }}">
<meta name="twitter:description" content="{{ $metaDescription ?? 'Koleksi hijab bernapas lembut dan berkarakter elegan untuk muslimah bersahaja.' }}">
<meta name="twitter:image" content="{{ $ogImage ?? asset('images/og-bloomingkembang.jpg') }}">

<!-- Organization & WebSite JSON-LD Schema.org -->
@php
    $defaultSchema = [
        '@context' => 'https://schema.org',
        '@graph' => [
            [
                '@type' => 'Organization',
                '@id' => url('/') . '#organization',
                'name' => 'BloomingKembang',
                'url' => url('/'),
                'logo' => asset('images/logo.png'),
                'description' => 'Brand kurasi hijab elegan dan pakaian muslimah bersahaja berbahan serat voal dan ceruty premium.',
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => 'Jl. Citarum No. 28',
                    'addressLocality' => 'Bandung',
                    'addressRegion' => 'Jawa Barat',
                    'postalCode' => '40115',
                    'addressCountry' => 'ID',
                ],
                'contactPoint' => [
                    '@type' => 'ContactPoint',
                    'telephone' => '+62-812-3456-7890',
                    'contactType' => 'customer service',
                    'areaServed' => 'ID',
                    'availableLanguage' => ['id', 'en'],
                ],
            ],
            [
                '@type' => 'WebSite',
                '@id' => url('/') . '#website',
                'url' => url('/'),
                'name' => 'BloomingKembang',
                'publisher' => [
                    '@id' => url('/') . '#organization',
                ],
                'potentialAction' => [
                    '@type' => 'SearchAction',
                    'target' => url('/koleksi') . '?cari={search_term_string}',
                    'query-input' => 'required name=search_term_string',
                ],
            ],
        ],
    ];
@endphp
<script type="application/ld+json">
{!! json_encode($defaultSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>

<!-- Google Fonts: Inter & JetBrains Mono -->
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=inter:400,500|jetbrains-mono:400" rel="stylesheet" />

<!-- Scripts & Styles -->
@vite(['resources/css/app.css', 'resources/js/app.js'])
@livewireStyles

<!-- E-Commerce Analytics Script Helper (GA4 + Meta Pixel) -->
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}

    window.trackEcommerce = function(eventName, params) {
        const consent = localStorage.getItem('blooming_consent');
        if (consent !== 'granted') {
            return;
        }

        window.dataLayer.push({
            event: eventName,
            ecommerce: params
        });

        if (typeof fbq === 'function') {
            const metaEventMap = {
                'view_item': 'ViewContent',
                'add_to_cart': 'AddToCart',
                'begin_checkout': 'InitiateCheckout',
                'purchase': 'Purchase'
            };
            const metaEvent = metaEventMap[eventName] || eventName;
            fbq('track', metaEvent, params);
        }

        console.debug('[Analytics Tracked]', eventName, params);
    };
</script>

