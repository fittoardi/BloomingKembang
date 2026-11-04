@extends('errors.minimal')

@section('title', 'Sesi Berakhir')
@section('code', '419')
@section('message', 'Sesi Telah Berakhir')

@section('icon')
<svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
</svg>
@endsection

@section('description')
Sesi keamanan atau token formulir Anda telah berakhir karena tidak ada aktivitas dalam beberapa waktu. Silakan muat ulang halaman ini atau coba kembali permintaan Anda.
@endsection

@section('extra-action')
<button type="button" onclick="window.location.reload()" class="w-full sm:w-auto inline-flex items-center justify-center px-6 py-2.5 rounded-xs bg-bloom text-white text-xs font-medium tracking-widest uppercase hover:bg-blush-700 transition-colors shadow-2xs cursor-pointer">
    Muat Ulang Halaman
</button>
@endsection

