<?php

namespace App\Http\Controllers;

use App\Enums\ProductStatus;
use App\Models\Category;
use App\Models\Page;
use App\Models\Product;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $products = Product::where('status', ProductStatus::Active)
            ->latest('updated_at')
            ->get();

        $categories = Category::all();

        $pages = Page::where('is_published', true)
            ->latest('updated_at')
            ->get();

        $content = view('sitemap', [
            'products' => $products,
            'categories' => $categories,
            'pages' => $pages,
        ])->render();

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }

    public function robots(): Response
    {
        $baseUrl = url('/');
        $robots = "User-agent: *\n".
            "Allow: /\n".
            "Disallow: /admin/\n".
            "Disallow: /akun/\n".
            "Disallow: /checkout\n".
            "Disallow: /webhooks/\n\n".
            "Sitemap: {$baseUrl}/sitemap.xml\n";

        return response($robots, 200)
            ->header('Content-Type', 'text/plain');
    }
}
