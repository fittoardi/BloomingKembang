<?php

use Illuminate\Support\Facades\Route;

test('404 error page renders custom branded template on invalid url', function () {
    $response = $this->get('/halaman-yang-pasti-tidak-ada-404-xyz');

    $response->assertNotFound();
    $response->assertSee('Kode 404', false);
    $response->assertSee('Halaman Tidak Ditemukan', false);
    $response->assertSee('Kembali ke Beranda', false);
    $response->assertSee('Jelajahi Koleksi', false);
    $response->assertSee('images/logo.png', false);
});

test('403 forbidden error page renders custom branded template', function () {
    Route::get('/test-403-error', function () {
        abort(403);
    });

    $response = $this->get('/test-403-error');

    $response->assertForbidden();
    $response->assertSee('Kode 403', false);
    $response->assertSee('Akses Terbatas', false);
});

test('401 unauthorized error page renders custom branded template', function () {
    Route::get('/test-401-error', function () {
        abort(401);
    });

    $response = $this->get('/test-401-error');

    $response->assertStatus(401);
    $response->assertSee('Kode 401', false);
    $response->assertSee('Autentikasi Diperlukan', false);
    $response->assertSee('Masuk ke Akun', false);
});

test('419 page expired error page renders custom branded template', function () {
    Route::get('/test-419-error', function () {
        abort(419);
    });

    $response = $this->get('/test-419-error');

    $response->assertStatus(419);
    $response->assertSee('Kode 419', false);
    $response->assertSee('Sesi Telah Berakhir', false);
    $response->assertSee('Muat Ulang Halaman', false);
});

test('429 rate limit error page renders custom branded template', function () {
    Route::get('/test-429-error', function () {
        abort(429);
    });

    $response = $this->get('/test-429-error');

    $response->assertStatus(429);
    $response->assertSee('Kode 429', false);
    $response->assertSee('Mohon Tunggu Sebentar', false);
});

test('500 server error page renders custom branded template', function () {
    Route::get('/test-500-error', function () {
        abort(500);
    });

    $response = $this->get('/test-500-error');

    $response->assertStatus(500);
    $response->assertSee('Kode 500', false);
    $response->assertSee('Terjadi Kendala Teknis', false);
});

test('503 maintenance error page renders custom branded template', function () {
    Route::get('/test-503-error', function () {
        abort(503);
    });

    $response = $this->get('/test-503-error');

    $response->assertStatus(503);
    $response->assertSee('Kode 503', false);
    $response->assertSee('Sistem Sedang Diperbarui', false);
});

