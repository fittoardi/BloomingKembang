@extends('errors.minimal')

@section('title', 'Terlalu Banyak Permintaan')
@section('code', '429')
@section('message', 'Mohon Tunggu Sebentar')

@section('icon')
<svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
</svg>
@endsection

@section('description')
Sistem mendeteksi terlalu banyak permintaan dalam waktu singkat demi keamanan transaksi Anda. Silakan tunggu sekitar 1 menit sebelum mencoba kembali.
@endsection
