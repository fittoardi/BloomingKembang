<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header -->
    <div class="text-center space-y-2 pb-8 border-b border-sage-200/80">
        <span class="text-xs uppercase font-mono tracking-widest text-botanic font-semibold flex items-center justify-center gap-1.5">
            <span class="w-1.5 h-1.5 rounded-full bg-bloom"></span>
            <span>Pelacakan Pesanan</span>
        </span>
        <h1 class="text-2xl sm:text-3xl font-serif font-normal text-obsidian uppercase tracking-wider">
            Lacak Status Pengiriman
        </h1>
        <p class="text-xs text-stone-600 max-w-md mx-auto">
            Masukkan nomor pesanan Anda beserta email atau nomor telepon yang digunakan saat checkout untuk melihat status terkini dan nomor resi.
        </p>
    </div>

    <!-- Tracking Form -->
    <div class="my-8 border border-sage-200/90 bg-white/95 backdrop-blur-xs p-6 sm:p-8 rounded-lg shadow-2xs">
        <form wire:submit.prevent="search" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
                <label for="orderNumber" class="block text-[11px] uppercase font-mono tracking-wider text-obsidian mb-1">
                    Nomor Pesanan *
                </label>
                <input 
                    type="text" 
                    id="orderNumber"
                    wire:model="orderNumber"
                    placeholder="Contoh: BK-20261021-ABCD"
                    class="w-full px-3 py-2 text-xs border border-sage-200 focus:border-botanic focus:ring-botanic/20 rounded-xs uppercase font-mono"
                    required
                />
                @error('orderNumber') <span class="text-[10px] text-rose-600 block mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="contact" class="block text-[11px] uppercase font-mono tracking-wider text-obsidian mb-1">
                    Email atau No. Telepon *
                </label>
                <input 
                    type="text" 
                    id="contact"
                    wire:model="contact"
                    placeholder="nama@email.com / 08123456789"
                    class="w-full px-3 py-2 text-xs border border-sage-200 focus:border-botanic focus:ring-botanic/20 rounded-xs"
                    required
                />
                @error('contact') <span class="text-[10px] text-rose-600 block mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="sm:col-span-2 pt-2 flex justify-end">
                <button 
                    type="submit" 
                    wire:loading.attr="disabled"
                    class="px-6 py-2.5 bg-botanic text-white text-xs uppercase tracking-widest font-semibold hover:bg-sage-700 transition-colors shadow-xs rounded-xs disabled:opacity-50 cursor-pointer"
                >
                    <span wire:loading.remove wire:target="search">Lacak Pesanan &rarr;</span>
                    <span wire:loading wire:target="search">Memeriksa Data...</span>
                </button>
            </div>
        </form>

        @if($errorMessage)
            <div class="mt-4 p-3 border border-rose-300 bg-rose-50 text-xs text-rose-700 rounded-xs">
                {{ $errorMessage }}
            </div>
        @endif
    </div>

    <!-- Order Result Section -->
    @if($order)
        <div class="border border-sage-200/90 bg-white/95 backdrop-blur-xs p-6 sm:p-8 space-y-8 rounded-lg shadow-sm animate-fadeIn">
            <!-- Header Result -->
            <div class="flex flex-wrap items-center justify-between gap-4 pb-6 border-b border-bone">
                <div>
                    <span class="text-[10px] font-mono uppercase tracking-widest text-neutral-400 block">Ditemukan Pesanan</span>
                    <span class="text-xl font-mono font-bold text-obsidian">#{{ $order->number }}</span>
                    <span class="text-xs font-mono text-neutral-500 block">{{ $order->created_at->format('d M Y, H:i') }} WIB</span>
                </div>
                <div class="flex items-center gap-2">
                    @php
                        $badgeStyles = match($order->status->value ?? $order->status) {
                            'pending_payment' => 'bg-amber-50 text-amber-800 border-amber-200',
                            'processing' => 'bg-blue-50 text-blue-800 border-blue-200',
                            'shipped' => 'bg-indigo-50 text-indigo-800 border-indigo-200',
                            'completed' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                            'cancelled', 'expired' => 'bg-neutral-100 text-neutral-600 border-neutral-200',
                            default => 'bg-gray-100 text-gray-700 border-gray-200',
                        };
                    @endphp
                    <span class="px-3 py-1 text-xs font-mono uppercase tracking-wider border {{ $badgeStyles }}">
                        {{ $order->status->label() }}
                    </span>
                </div>
            </div>

            <!-- Resi Highlight if Shipped -->
            @if($order->tracking_number)
                <div class="p-4 bg-sage-50/80 border border-sage-200 rounded-xs space-y-1">
                    <span class="text-[10px] uppercase font-mono tracking-wider text-botanic font-bold block">
                        Nomor Resi Pengiriman
                    </span>
                    <div class="flex items-baseline gap-2">
                        <span class="font-mono text-lg font-bold text-botanic select-all">{{ $order->tracking_number }}</span>
                        <span class="text-xs text-stone-600">({{ strtoupper($order->courier) }} - {{ $order->courier_service }})</span>
                    </div>
                    <p class="text-[11px] text-stone-600">
                        Diserahkan ke kurir pada {{ $order->shipped_at?->format('d M Y, H:i') ?? '-' }} WIB
                    </p>
                </div>
            @endif

            <!-- Pending Payment notice -->
            @if($order->status->value === 'pending_payment')
                <div class="p-4 bg-amber-50 border border-amber-200 flex flex-col sm:flex-row items-center justify-between gap-4 rounded-xs">
                    <div class="text-xs text-amber-900 space-y-0.5">
                        <span class="font-bold uppercase tracking-wider block">Menunggu Pembayaran</span>
                        <p>Total: <strong class="font-mono text-botanic font-bold">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</strong> &bull; Batas: {{ $order->expires_at?->format('d M Y, H:i') }} WIB</p>
                    </div>
                    <button
                        type="button"
                        wire:click="payNow"
                        wire:loading.attr="disabled"
                        class="px-5 py-2 bg-botanic text-white text-xs uppercase tracking-widest font-semibold hover:bg-sage-700 transition-colors whitespace-nowrap shadow-xs rounded-xs disabled:opacity-50 cursor-pointer"
                    >
                        Bayar Sekarang &rarr;
                    </button>
                </div>
            @endif

            <!-- Items -->
            <div class="space-y-3">
                <h3 class="text-xs uppercase tracking-[0.2em] font-medium text-obsidian pb-2 border-b border-bone">
                    Daftar Produk yang Dipesan
                </h3>
                <div class="divide-y divide-bone">
                    @foreach($order->items as $item)
                        <div class="py-2.5 flex items-center justify-between text-xs">
                            <div>
                                <span class="font-medium text-obsidian">{{ $item->product_name }}</span>
                                <span class="font-mono text-[11px] text-neutral-500 block">{{ $item->variant_label }} &times; {{ $item->quantity }} pcs</span>
                            </div>
                            <span class="font-mono text-obsidian">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Shipping Destination -->
            <div class="border border-bone p-4 bg-bone text-xs space-y-1">
                <span class="text-[10px] uppercase font-mono tracking-wider text-neutral-500 block">Tujuan Pengiriman</span>
                <p class="font-medium text-obsidian">{{ $order->shipping_address['recipient_name'] ?? $order->customer_name }}</p>
                <p class="text-neutral-600">{{ $order->shipping_address['detail'] ?? '' }}, {{ $order->shipping_address['city'] ?? '' }}</p>
            </div>
        </div>
    @endif
</div>
