@extends('errors.minimal')

@section('title', 'Kendala Server')
@section('code', '500')
@section('message', 'Terjadi Kendala Teknis')

@section('icon')
<svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
</svg>
@endsection

@section('description')
Mohon maaf, sistem kami sedang mengalami kendala sementara. Tim kami telah mencatat masalah ini dan sedang melakukan penanganan.
@endsection
