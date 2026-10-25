<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
    <div class="border border-bone bg-paper p-8 sm:p-12 space-y-8">
        <!-- Top Banner -->
        <div class="text-center space-y-3 pb-8 border-b border-bone">
            <span class="text-xs uppercase font-mono tracking-widest text-emerald-700 bg-emerald-50 px-3 py-1 inline-block">
                Pesanan Berhasil Dibuat
            </span>
            <h1 class="text-2xl sm:text-3xl font-normal text-obsidian uppercase tracking-wider">
                Terima Kasih atas Pesanan Anda
            </h1>
            <p class="text-xs sm:text-sm text-neutral-500 max-w-md mx-auto">
                Nomor Referensi Pesanan: <strong class="font-mono text-obsidian">{{ $order->number }}</strong>
            </p>
            <p class="text-[11px] text-neutral-400 font-mono">
                Batas waktu penyelesaian pembayaran: {{ $order->expires_at?->translatedFormat('d F Y, H:i') ?? '24 Jam' }} WIB
            </p>

            @if($errorMessage)
                <div class="mt-4 p-3 border border-rose-300 bg-rose-50 text-xs text-rose-700 max-w-md mx-auto">
                    {{ $errorMessage }}
                </div>
            @endif

            @if($order->status->value === 'pending_payment')
                <div class="pt-2">
                    <button
                        type="button"
                        wire:click="payNow"
                        wire:loading.attr="disabled"
                        class="px-8 py-3 bg-obsidian text-paper text-xs uppercase tracking-widest font-medium hover:bg-neutral-800 transition-colors shadow-md disabled:opacity-50"
                    >
                        <span wire:loading.remove wire:target="payNow">Bayar Sekarang Via Midtrans &rarr;</span>
                        <span wire:loading wire:target="payNow">Menyiapkan Pembayaran...</span>
                    </button>
                </div>
            @endif
        </div>

        <!-- Detail Rincian Pesanan -->
        <div class="space-y-6">
            <h2 class="text-xs uppercase tracking-[0.2em] font-medium text-obsidian">
                Rincian Barang yang Dipesan
            </h2>

            <div class="divide-y divide-bone border-y border-bone">
                @foreach($order->items as $item)
                    <div class="py-3 flex items-center justify-between text-xs">
                        <div>
                            <span class="font-medium text-obsidian">{{ $item->product_name }}</span>
                            <span class="text-neutral-500 font-mono text-[11px] block">{{ $item->variant_name }} &bull; {{ $item->quantity }} pcs</span>
                        </div>
                        <span class="font-mono font-medium text-obsidian">
                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                        </span>
                    </div>
                @endforeach
            </div>

            <!-- Kalkulasi Biaya -->
            <div class="space-y-2 text-xs pt-2">
                <div class="flex justify-between text-neutral-500">
                    <span>Subtotal:</span>
                    <span class="font-mono">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                @if($order->discount_total > 0)
                    <div class="flex justify-between text-emerald-700 font-medium">
                        <span>Diskon (Voucher {{ $order->voucher_code }}):</span>
                        <span class="font-mono">- Rp {{ number_format($order->discount_total, 0, ',', '.') }}</span>
                    </div>
                @endif
                <div class="flex justify-between text-neutral-500">
                    <span>Ongkos Kirim ({{ $order->courier }} {{ $order->courier_service }}):</span>
                    <span class="font-mono">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-baseline pt-3 border-t border-bone text-obsidian font-semibold">
                    <span class="uppercase tracking-widest text-xs">Total yang Harus Dibayar:</span>
                    <span class="font-mono text-lg font-bold">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Alamat Kirim -->
            <div class="border border-bone p-4 bg-bone text-xs space-y-1">
                <span class="text-[10px] uppercase font-mono tracking-wider text-neutral-500 block">Tujuan Pengiriman</span>
                <p class="font-medium text-obsidian">{{ $order->shipping_address['recipient_name'] ?? $order->customer_name }} ({{ $order->shipping_address['phone'] ?? $order->customer_phone }})</p>
                <p class="text-neutral-600">{{ $order->shipping_address['detail'] ?? '' }}, {{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['province'] ?? '' }} {{ $order->shipping_address['postal_code'] ?? '' }}</p>
            </div>
        </div>

        <div class="pt-6 border-t border-bone flex flex-col sm:flex-row items-center justify-between gap-4">
            <a href="{{ url('/koleksi') }}" class="link-underlined text-xs uppercase tracking-wider">
                &larr; Kembali Berbelanja
            </a>
            @auth
                <a href="{{ url('/akun') }}" class="px-6 py-3 bg-obsidian text-paper text-xs uppercase tracking-widest font-medium hover:bg-neutral-800 transition-colors">
                    Lihat Akun & Pesanan
                </a>
            @endauth
        </div>
    </div>

    <!-- Purchase Analytics Event Tracking (GA4 & Meta Pixel) -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof window.trackEcommerce === 'function') {
                window.trackEcommerce('purchase', {
                    transaction_id: '{{ $order->number }}',
                    value: {{ (int) $order->grand_total }},
                    currency: 'IDR',
                    shipping: {{ (int) $order->shipping_cost }},
                    coupon: '{{ $order->voucher_code ?? "" }}',
                    items: [
                        @foreach($order->items as $item)
                        {
                            item_id: '{{ $item->sku }}',
                            item_name: '{{ addslashes($item->product_name) }}',
                            price: {{ (int) $item->price }},
                            quantity: {{ (int) $item->quantity }}
                        }@if(!$loop->last),@endif
                        @endforeach
                    ]
                });
            }
        });
    </script>
</div>
