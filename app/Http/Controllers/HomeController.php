<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Product;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the storefront home page.
     */
    public function __invoke(): View
    {
        $heroBanners = Banner::active()
            ->hero()
            ->current()
            ->orderBy('sort_order')
            ->get();

        $defaultSlides = [
            [
                'title' => 'Sentuhan Lembut & Elegan Hijab Pilihan',
                'subtitle' => 'Ditenun dari serat voal ultrafine dan ceruty babydoll pilihan. Lembut bersahaja, tegak di dahi, dan anggun dalam setiap momen.',
                'image' => 'images/home/hero-portrait.jpg',
                'link_url' => '/koleksi',
                'tag' => 'Koleksi Signature',
                'badge' => 'Voal Ultrafine Turkish',
                'cta_text' => 'Jelajahi Koleksi',
            ],
            [
                'title' => 'Pashmina Silk Shimmer & Ceruty Flowy',
                'subtitle' => 'Kilau lembut mewah yang memikat dengan drape kain jatuh sempurna. Sangat anggun untuk resepsi dan momen istimewa.',
                'image' => 'images/products/pashmina-silk.jpg',
                'link_url' => '/koleksi/pashmina',
                'tag' => 'Shimmer & Glow',
                'badge' => 'Silk Shimmer',
                'cta_text' => 'Lihat Pashmina',
            ],
            [
                'title' => 'Voal Miracle & Segi Empat Laser Cut',
                'subtitle' => 'Potongan laser cut presisi dengan tekstur kain anti kusut. Pilihan tepat untuk kenyamanan seharian penuh percaya diri.',
                'image' => 'images/products/voal-signature.jpg',
                'link_url' => '/koleksi/hijab-segi-empat',
                'tag' => 'Best Seller Voal',
                'badge' => 'Laser Cut Edge',
                'cta_text' => 'Beli Voal Miracle',
            ],
            [
                'title' => 'Bergo Daily & Khimar Soft Pet Series',
                'subtitle' => 'Praktis, langsung pakai, dan sejuk di cuaca tropis. Jahit butik tepi halus dengan ketahanan bahan tinggi.',
                'image' => 'images/products/bergo-daily.jpg',
                'link_url' => '/koleksi/bergo-daily',
                'tag' => 'Daily Practical Wear',
                'badge' => 'Instant Bergo',
                'cta_text' => 'Lihat Bergo Daily',
            ],
            [
                'title' => 'Koleksi Syar\'i & Elegan 2026',
                'subtitle' => 'Ditenun dari serat voal ultrafine dan ceruty babydoll pilihan. Sempurna menemani ibadah dan aktivitas harian Anda.',
                'image' => 'images/home/hero-editorial.jpg',
                'link_url' => '/koleksi',
                'tag' => 'Editorial Syar\'i',
                'badge' => 'Coverage & Comfort',
                'cta_text' => 'Temukan Koleksi',
            ],
        ];

        $slides = [];
        if ($heroBanners->isNotEmpty()) {
            foreach ($heroBanners as $idx => $banner) {
                $preset = $defaultSlides[$idx % count($defaultSlides)];
                $slides[] = [
                    'id' => $banner->id,
                    'title' => $banner->title,
                    'subtitle' => $banner->subtitle ?? $preset['subtitle'],
                    'image' => $banner->image ?: $preset['image'],
                    'link_url' => $banner->link_url ?: $preset['link_url'],
                    'tag' => $preset['tag'],
                    'badge' => $preset['badge'],
                    'cta_text' => $preset['cta_text'],
                ];
            }
        } else {
            $slides = $defaultSlides;
        }

        $featuredProducts = Product::with(['category', 'variants'])
            ->active()
            ->orderBy('sold_count', 'desc')
            ->take(6)
            ->get();

        return view('pages.home.index', [
            'slides' => $slides,
            'featuredProducts' => $featuredProducts,
        ]);
    }
}
