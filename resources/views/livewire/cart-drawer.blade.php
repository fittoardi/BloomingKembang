<div>
    <!-- Cart Backdrop & Slide-Over Panel -->
    <div 
        x-data="{ open: @entangle('isOpen') }" 
        x-show="open" 
        x-cloak 
        class="fixed inset-0 z-50 overflow-hidden"
        @keydown.escape.window="open = false; $wire.close()"
    >
        <!-- Backdrop -->
        <div 
            x-show="open" 
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-obsidian/40 backdrop-blur-sm transition-opacity"
            @click="open = false; $wire.close()"
        ></div>

        <!-- Slide-over Container -->
        <div class="fixed inset-y-0 right-0 max-w-full flex pl-10">
            <div 
                x-show="open"
                x-transition:enter="transform transition ease-in-out duration-300 sm:duration-400"
                x-transition:enter-start="translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transform transition ease-in-out duration-300 sm:duration-400"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="translate-x-full"
                class="w-screen max-w-md bg-paper border-l border-bone flex flex-col justify-between shadow-2xl"
            >
                <!-- Drawer Header -->
                <div class="p-6 border-b border-bone flex items-center justify-between">
                    <div>
                        <h2 class="text-xs uppercase tracking-[0.2em] font-medium text-obsidian">
                            Keranjang Belanja
                        </h2>
                        <span class="text-[11px] font-mono text-neutral-400">
                            {{ $count }} {{ $count === 1 ? 'item' : 'items' }}
                        </span>
                    </div>
                    <button 
                        type="button" 
                        wire:click="close"
                        class="text-neutral-400 hover:text-obsidian p-1 transition-colors text-xs uppercase tracking-widest font-mono"
                    >
                        [Tutup]
                    </button>
                </div>

                <!-- Items List -->
                <div class="flex-1 overflow-y-auto p-6 space-y-6">
                    @forelse($items as $item)
                        @php
                            $variant = $item->variant;
                            $product = $variant?->product;
                            $thumb = $product?->getFirstMediaUrl('images', 'thumb');
                        @endphp
                        <div class="flex gap-4 pb-6 border-b border-bone items-start">
                            <!-- Thumbnail / Mockup -->
                            <div class="w-20 h-24 bg-bone border border-bone flex-shrink-0 relative overflow-hidden flex items-center justify-center">
                                @if($thumb)
                                    <img src="{{ $thumb }}" alt="{{ $product->name }}" class="w-full h-full object-cover" />
                                @else
                                    <span class="text-[9px] uppercase font-mono text-neutral-400 text-center px-1">
                                        {{ $product->category->name ?? 'Hijab' }}
                                    </span>
                                @endif
                            </div>

                            <!-- Details -->
                            <div class="flex-1 flex flex-col justify-between min-h-[96px]">
                                <div class="space-y-1">
                                    <div class="flex items-start justify-between gap-2">
                                        <h3 class="text-xs font-normal text-obsidian line-clamp-1">
                                            {{ $product->name ?? 'Produk' }}
                                        </h3>
                                        <button 
                                            type="button" 
                                            wire:click="removeItem({{ $item->id }})" 
                                            class="text-neutral-400 hover:text-red-600 transition-colors text-[11px]"
                                            title="Hapus"
                                        >
                                            &times;
                                        </button>
                                    </div>
                                    <div class="flex items-center space-x-2 text-[11px] text-neutral-500 font-mono">
                                        @if($variant?->color_hex)
                                            <span class="w-2.5 h-2.5 inline-block border border-neutral-300" style="background-color: {{ $variant->color_hex }};"></span>
                                        @endif
                                        <span>{{ $variant?->color }}</span>
                                        @if($variant?->size)
                                            <span>&bull; {{ $variant->size }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-center justify-between pt-2">
                                    <!-- Quantity Selector -->
                                    <div class="flex items-center border border-bone text-xs">
                                        <button 
                                            type="button" 
                                            wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})"
                                            class="px-2.5 py-0.5 text-neutral-500 hover:text-obsidian"
                                        >
                                            -
                                        </button>
                                        <span class="px-3 py-0.5 font-mono text-[11px]">{{ $item->quantity }}</span>
                                        <button 
                                            type="button" 
                                            wire:click="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})"
                                            class="px-2.5 py-0.5 text-neutral-500 hover:text-obsidian"
                                        >
                                            +
                                        </button>
                                    </div>

                                    <!-- Price -->
                                    <span class="font-mono text-xs font-medium text-obsidian">
                                        Rp {{ number_format(($variant?->price ?? 0) * $item->quantity, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-16 text-center space-y-4">
                            <span class="text-xs uppercase tracking-widest text-neutral-400 font-mono block">
                                Keranjang Kosong
                            </span>
                            <p class="text-xs text-neutral-500 max-w-xs mx-auto">
                                Anda belum menambahkan produk kerudung atau aksesoris pilihan ke dalam keranjang.
                            </p>
                            <div class="pt-2">
                                <a 
                                    href="{{ url('/koleksi') }}" 
                                    @click="open = false; $wire.close()"
                                    class="link-underlined text-xs uppercase tracking-wider"
                                >
                                    Jelajahi Koleksi
                                </a>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Drawer Footer (Subtotal & Checkout) -->
                @if($items->isNotEmpty())
                    <div class="p-6 border-t border-bone bg-paper space-y-4">
                        <div class="space-y-1 text-xs">
                            <div class="flex justify-between items-center text-neutral-500">
                                <span>Estimasi Total Berat:</span>
                                <span class="font-mono">{{ number_format($totalWeight, 0, ',', '.') }} gram</span>
                            </div>
                            <div class="flex justify-between items-center text-obsidian pt-1">
                                <span class="uppercase tracking-wider font-medium text-xs">Subtotal:</span>
                                <span class="font-mono text-base font-medium">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                            </div>
                            <p class="text-[10px] text-neutral-400">Ongkos kirim dan kode voucher dihitung saat checkout.</p>
                        </div>

                        <div class="space-y-2 pt-2">
                            <a 
                                href="{{ url('/checkout') }}" 
                                class="w-full block py-3.5 bg-obsidian text-paper text-center text-xs uppercase tracking-widest font-medium hover:bg-neutral-800 transition-colors"
                            >
                                Lanjut ke Pembayaran
                            </a>
                            <button 
                                type="button" 
                                wire:click="close"
                                class="w-full py-2.5 text-center text-xs uppercase tracking-wider text-neutral-500 hover:text-obsidian transition-colors font-mono"
                            >
                                Lanjut Belanja
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
