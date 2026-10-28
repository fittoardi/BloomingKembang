<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class StorefrontLayout extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?string $title = null,
        public ?string $metaDescription = null,
        public ?string $ogImage = null,
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        return view('layouts.storefront');
    }
}
