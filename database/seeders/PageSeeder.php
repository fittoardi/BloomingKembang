<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title' => 'Tentang Kami',
                'slug' => 'tentang-kami',
                'body' => 'BloomingKembang adalah brand hijab single-brand independen yang didedikasikan untuk menghadirkan hijab berkualitas premium dengan sentuhan warna lembut, jahitan tepi rapi, dan material terpilih yang adem serta mudah dibentuk.',
            ],
            [
                'title' => 'Kebijakan Pengembalian & Retur',
                'slug' => 'kebijakan-retur',
                'body' => 'Kami menjamin kualitas setiap helai hijab BloomingKembang. Jika terdapat cacat produksi atau kesalahan pengiriman warna/ukuran, penukaran dapat dilakukan maksimal 3x24 jam sejak paket diterima dengan menyertakan video unboxing.',
            ],
            [
                'title' => 'Pertanyaan yang Sering Diajukan (FAQ)',
                'slug' => 'faq',
                'body' => 'Temukan jawaban terkait cara pemesanan, ketersediaan bahan voal dan ceruty, pengiriman kilat, hingga panduan perawatan hijab favorit Anda.',
            ],
            [
                'title' => 'Syarat & Ketentuan',
                'slug' => 'syarat-dan-ketentuan',
                'body' => 'Syarat dan ketentuan bertransaksi secara aman dan nyaman di toko online resmi BloomingKembang.',
            ],
            [
                'title' => 'Kebijakan Privasi',
                'slug' => 'kebijakan-privasi',
                'body' => 'Kami melindungi privasi data setiap pelanggan sesuai ketentuan Undang-Undang Perlindungan Data Pribadi (UU PDP).',
            ],
        ];

        foreach ($pages as $p) {
            Page::firstOrCreate(
                ['slug' => $p['slug']],
                [
                    'title' => $p['title'],
                    'body' => $p['body'],
                    'is_published' => true,
                ]
            );
        }
    }
}
