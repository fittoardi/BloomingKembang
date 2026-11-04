@props([
    'variant' => 'dark',
    'alt' => config('app.name', 'BloomingKembang'),
])

@php
    $src = match($variant) {
        'white', 'light' => asset('images/logo-white.png'),
        'dark', 'black' => asset('images/logo.png'),
        default => asset('images/logo.png'),
    };
@endphp

@if($variant === 'adaptive')
    <img src="{{ asset('images/logo.png') }}" alt="{{ $alt }}" {{ $attributes->merge(['class' => 'dark:hidden object-contain inline-block']) }}>
    <img src="{{ asset('images/logo-white.png') }}" alt="{{ $alt }}" {{ $attributes->merge(['class' => 'hidden dark:block object-contain inline-block']) }}>
@else
    <img src="{{ $src }}" alt="{{ $alt }}" {{ $attributes->merge(['class' => 'object-contain inline-block']) }}>
@endif

