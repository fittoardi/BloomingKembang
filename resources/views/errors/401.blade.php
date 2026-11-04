@extends('errors.minimal')

@section('title', 'Perlu Masuk Akun')
@section('code', '401')
@section('message', 'Autentikasi Diperlukan')

@section('icon')
<svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
</svg>
@endsection

@section('description')
Halaman ini membutuhkan akses akun terdaftar. Silakan masuk terlebih dahulu untuk melihat data pesanan, alamat, atau pengaturan profil Anda.
@endsection

@section('extra-action')
<a href="{{ route('login') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2.5 rounded-xs bg-bloom text-white text-xs font-medium tracking-widest uppercase hover:bg-blush-700 transition-colors shadow-2xs">
    Masuk ke Akun
</a>
@endsection

