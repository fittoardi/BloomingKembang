<?php

namespace App\Livewire;

use App\Models\Page;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class StaticPage extends Component
{
    public Page $page;

    public function mount(string $slug = 'tentang-kami'): void
    {
        $this->page = Page::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();
    }

    public function render(): View
    {
        return view('pages.static.show')->layout('layouts.storefront', [
            'title' => ($this->page->seo_title ?: $this->page->title).' — BloomingKembang',
            'metaDescription' => $this->page->seo_description,
        ]);
    }
}
