<?php

namespace App\Livewire;

use App\Enums\OrderStatus;
use App\Enums\ProductStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\Wishlist;
use App\Services\CartService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProductShow extends Component
{
    public Product $product;

    public ?int $selectedVariantId = null;

    public int $quantity = 1;

    public string $activeTab = 'description'; // description, material, size_guide, care_guide

    public int $activeImageIndex = 0;

    public int $newRating = 5;

    public string $newComment = '';

    public string $newTitle = '';

    public function mount(string $slug): void
    {
        $this->product = Product::with(['category', 'media', 'variants' => function ($q) {
            $q->where('is_active', true)->orderBy('position');
        }])
            ->where('slug', $slug)
            ->where('status', ProductStatus::Active)
            ->firstOrFail();

        // Pilih varian pertama yang ada stoknya, atau fallback ke varian pertama
        $readyVariant = $this->product->variants->first(fn ($v) => ($v->stock - $v->reserved) > 0);
        $this->selectedVariantId = $readyVariant ? $readyVariant->id : $this->product->variants->first()?->id;
    }

    public function selectVariant(int $variantId): void
    {
        $this->selectedVariantId = $variantId;
        $this->quantity = 1;
    }

    public function selectImage(int $index): void
    {
        $this->activeImageIndex = $index;
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function incrementQuantity(): void
    {
        $selectedVariant = $this->getSelectedVariant();
        $availableStock = $selectedVariant ? ($selectedVariant->stock - $selectedVariant->reserved) : 0;

        if ($this->quantity < $availableStock) {
            $this->quantity++;
        }
    }

    public function decrementQuantity(): void
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function getSelectedVariant(): ?ProductVariant
    {
        return $this->product->variants->firstWhere('id', $this->selectedVariantId);
    }

    public function addToCart(CartService $cartService): void
    {
        if (! $this->selectedVariantId) {
            return;
        }

        $selectedVariant = $this->getSelectedVariant();
        $cartService->addItem($this->selectedVariantId, $this->quantity);
        $this->dispatch('cart-updated');
        $this->dispatch('open-cart');

        if ($selectedVariant) {
            $this->dispatch('product-added-to-cart', [
                'item_id' => $selectedVariant->sku,
                'item_name' => $this->product->name,
                'price' => $selectedVariant->price,
                'quantity' => $this->quantity,
            ]);
        }
    }

    public function toggleWishlist(): void
    {
        if (! Auth::check()) {
            $this->redirect(route('login'));

            return;
        }

        $existing = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $this->product->id)
            ->first();

        if ($existing) {
            $existing->delete();
        } else {
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $this->product->id,
                'created_at' => now(),
            ]);
        }
    }

    public function isVerifiedBuyer(): bool
    {
        if (! Auth::check()) {
            return false;
        }

        return Order::where('user_id', Auth::id())
            ->whereIn('status', [OrderStatus::Paid, OrderStatus::Processing, OrderStatus::Shipped, OrderStatus::Completed])
            ->whereHas('items.variant', fn ($q) => $q->where('product_id', $this->product->id))
            ->exists();
    }

    public function hasUserReviewed(): bool
    {
        if (! Auth::check()) {
            return false;
        }

        return Review::where('user_id', Auth::id())
            ->where('product_id', $this->product->id)
            ->exists();
    }

    public function submitReview(): void
    {
        if (! Auth::check()) {
            $this->redirect(route('login'));

            return;
        }

        if (! $this->isVerifiedBuyer()) {
            session()->flash('review_error', 'Hanya pelanggan yang telah membeli produk ini yang dapat memberikan ulasan.');

            return;
        }

        if ($this->hasUserReviewed()) {
            session()->flash('review_error', 'Anda telah memberikan ulasan untuk produk ini.');

            return;
        }

        $this->validate([
            'newRating' => 'required|integer|min:1|max:5',
            'newComment' => 'required|string|min:5|max:1000',
            'newTitle' => 'nullable|string|max:120',
        ], [
            'newRating.required' => 'Silakan pilih rating bintang.',
            'newComment.required' => 'Isi ulasan wajib ditulis.',
            'newComment.min' => 'Isi ulasan minimal 5 karakter.',
        ]);

        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $this->product->id,
            'rating' => $this->newRating,
            'title' => $this->newTitle ?: null,
            'comment' => $this->newComment,
            'is_approved' => true,
        ]);

        $this->reset(['newComment', 'newTitle']);
        $this->newRating = 5;
        $this->product->refresh();

        session()->flash('review_success', 'Terima kasih! Ulasan Anda telah berhasil diterbitkan.');
    }

    public function render(): View
    {
        $selectedVariant = $this->getSelectedVariant();
        $availableStock = $selectedVariant ? ($selectedVariant->stock - $selectedVariant->reserved) : 0;

        // Produk Terkait (kategori sama)
        $relatedProducts = Product::with(['variants'])
            ->where('category_id', $this->product->category_id)
            ->where('id', '!=', $this->product->id)
            ->where('status', ProductStatus::Active)
            ->take(3)
            ->get();

        // Ulasan yang telah disetujui
        $approvedReviews = $this->product->approvedReviews()->with('user')->get();

        // Schema JSON-LD Product
        $jsonLd = [
            '@context' => 'https://schema.org/',
            '@type' => 'Product',
            'name' => $this->product->name,
            'description' => $this->product->short_description ?? strip_tags($this->product->description ?? ''),
            'sku' => $selectedVariant?->sku ?? $this->product->slug,
            'brand' => [
                '@type' => 'Brand',
                'name' => 'BloomingKembang',
            ],
            'offers' => [
                '@type' => 'Offer',
                'priceCurrency' => 'IDR',
                'price' => $selectedVariant?->price ?? 0,
                'availability' => $availableStock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'url' => url('/produk/'.$this->product->slug),
            ],
        ];

        // Schema JSON-LD BreadcrumbList
        $breadcrumbJsonLd = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => 'Beranda',
                    'item' => url('/'),
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => 'Koleksi',
                    'item' => url('/koleksi'),
                ],
            ],
        ];

        if ($this->product->category) {
            $breadcrumbJsonLd['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => $this->product->category->name,
                'item' => url('/koleksi/'.$this->product->category->slug),
            ];
            $breadcrumbJsonLd['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => 4,
                'name' => $this->product->name,
                'item' => url('/produk/'.$this->product->slug),
            ];
        } else {
            $breadcrumbJsonLd['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => $this->product->name,
                'item' => url('/produk/'.$this->product->slug),
            ];
        }

        // Media images
        $images = $this->product->getMedia('images');

        return view('pages.product.show', [
            'selectedVariant' => $selectedVariant,
            'availableStock' => $availableStock,
            'relatedProducts' => $relatedProducts,
            'images' => $images,
            'approvedReviews' => $approvedReviews,
            'isVerifiedBuyer' => $this->isVerifiedBuyer(),
            'hasUserReviewed' => $this->hasUserReviewed(),
            'isInWishlist' => Auth::check() ? Wishlist::where('user_id', Auth::id())->where('product_id', $this->product->id)->exists() : false,
            'jsonLd' => $jsonLd,
            'breadcrumbJsonLd' => $breadcrumbJsonLd,
        ])->layout('layouts.storefront', [
            'metaTitle' => ($this->product->seo_title ?: $this->product->name).' | BloomingKembang',
            'metaDescription' => $this->product->seo_description ?: $this->product->short_description,
        ]);
    }
}
