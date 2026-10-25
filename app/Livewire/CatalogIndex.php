<?php

namespace App\Livewire;

use App\Enums\ProductStatus;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class CatalogIndex extends Component
{
    use WithPagination;

    #[Url(as: 'kategori')]
    public string $categorySlug = '';

    #[Url(as: 'warna')]
    public string $color = '';

    #[Url(as: 'bahan')]
    public string $material = '';

    #[Url(as: 'stok')]
    public string $availability = ''; // all, ready

    #[Url(as: 'min_harga')]
    public ?int $minPrice = null;

    #[Url(as: 'max_harga')]
    public ?int $maxPrice = null;

    #[Url(as: 'urutkan')]
    public string $sortBy = 'newest'; // newest, price_low, price_high, best_seller

    public function mount(?string $category = null): void
    {
        if ($category) {
            $this->categorySlug = $category;
        }
    }

    public function updating($property): void
    {
        if (in_array($property, ['categorySlug', 'color', 'material', 'availability', 'minPrice', 'maxPrice', 'sortBy'])) {
            $this->resetPage();
        }
    }

    public function resetFilters(): void
    {
        $this->reset(['color', 'material', 'availability', 'minPrice', 'maxPrice', 'sortBy']);
        $this->resetPage();
    }

    public function render(): View
    {
        $categories = Category::where('is_active', true)->orderBy('position')->get(['id', 'name', 'slug', 'description']);

        $availableColors = ProductVariant::where('is_active', true)
            ->whereNotNull('color')
            ->select('color', 'color_hex')
            ->distinct()
            ->get();

        $availableMaterials = Product::where('status', ProductStatus::Active)
            ->whereNotNull('material')
            ->pluck('material')
            ->unique()
            ->filter()
            ->values();

        $query = Product::query()
            ->with(['category', 'variants' => function ($q) {
                $q->where('is_active', true)->orderBy('position');
            }])
            ->where('status', ProductStatus::Active);

        // Filter category
        if ($this->categorySlug) {
            $query->whereHas('category', function ($q) {
                $q->where('slug', $this->categorySlug);
            });
        }

        // Filter material
        if ($this->material) {
            $query->where('material', $this->material);
        }

        // Filter color
        if ($this->color) {
            $query->whereHas('variants', function ($q) {
                $q->where('color', $this->color)->where('is_active', true);
            });
        }

        // Filter availability
        if ($this->availability === 'ready') {
            $query->whereHas('variants', function ($q) {
                $q->where('is_active', true)->whereRaw('(stock - reserved) > 0');
            });
        }

        // Filter price
        if ($this->minPrice !== null) {
            $query->whereHas('variants', function ($q) {
                $q->where('price', '>=', $this->minPrice);
            });
        }

        if ($this->maxPrice !== null) {
            $query->whereHas('variants', function ($q) {
                $q->where('price', '<=', $this->maxPrice);
            });
        }

        // Sorting
        match ($this->sortBy) {
            'best_seller' => $query->orderByDesc('sold_count'),
            'price_low' => $query->withMin('variants', 'price')->orderBy('variants_min_price', 'asc'),
            'price_high' => $query->withMax('variants', 'price')->orderBy('variants_max_price', 'desc'),
            default => $query->latest('id'),
        };

        $products = $query->paginate(9);

        $currentCategory = null;
        if ($this->categorySlug) {
            $currentCategory = $categories->firstWhere('slug', $this->categorySlug);
        }

        return view('livewire.catalog-index', [
            'products' => $products,
            'categories' => $categories,
            'availableColors' => $availableColors,
            'availableMaterials' => $availableMaterials,
            'currentCategory' => $currentCategory,
        ])->layout('layouts.storefront', [
            'metaTitle' => $currentCategory ? "Koleksi {$currentCategory->name} | BloomingKembang" : 'Katalog Hijab & Koleksi Pilihan | BloomingKembang',
            'metaDescription' => $currentCategory ? ($currentCategory->description ?? "Koleksi hijab {$currentCategory->name} pilihan.") : 'Jelajahi koleksi hijab voal, pashmina, bergo dan hijab instan berkualitas dengan sentuhan lembut dan bersahaja.',
        ]);
    }
}
