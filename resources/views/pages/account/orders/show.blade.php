<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Breadcrumb & Header -->
    <div class="border-b border-bone pb-6 mb-8 flex flex-col sm:flex-row sm:items-baseline justify-between gap-4">
        <div>
            <nav class="text-[10px] tracking-widest uppercase font-mono text-neutral-400 mb-2 flex items-center space-x-2">
                <a href="{{ route('account.dashboard') }}" class="hover:text-obsidian">Akun Saya</a>
                <span>/</span>
                <a href="{{ url('/akun/pesanan') }}" class="hover:text-obsidian">Pesanan Saya</a>
                <span>/</span>
                <span class="text-obsidian">#{{ $order->number }}</span>
            </nav>
            <div class="flex flex-wrap items-center gap-3">
                <h1 class="text-xl sm:text-2xl font-normal uppercase tracking-wider text-obsidian">
                    Pesanan #{{ $order->number }}
                </h1>
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
                <span class="px-2.5 py-1 text-[11px] font-mono uppercase tracking-wider border {{ $badgeStyles }}">
                    {{ $order->status->label() }}
                </span>
                <span class="px-2 py-0.5 text-[10px] font-mono uppercase tracking-wider bg-bone text-neutral-600 border border-neutral-300">
                    {{ $order->payment_status->label() }}
                </span>
            </div>
        </div>

        <a 
            href="{{ url('/akun/pesanan') }}" 
            class="text-xs uppercase font-mono tracking-wider text-botanic hover:text-bloom underline font-semibold transition-colors"
        >
            &larr; Kembali ke Daftar
        </a>
    </div>

    <!-- Flash message -->
    @if(session('status'))
        <div class="mb-6 p-4 border border-emerald-300 bg-emerald-50 text-xs text-emerald-800">
            {{ session('status') }}
        </div>
    @endif

    @if($errorMessage)
        <div class="mb-6 p-4 border border-rose-300 bg-rose-50 text-xs text-rose-800">
            {{ $errorMessage }}
        </div>
    @endif

    <!-- Payment Notice if Pending -->
    @if($order->status->value === 'pending_payment')
        <div class="mb-8 border border-amber-200 bg-amber-50/60 p-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="space-y-1 text-xs text-amber-900">
                <span class="font-mono uppercase tracking-wider font-semibold block">Menunggu Pembayaran</span>
                <p>Silakan selesaikan pembayaran sebelum <strong class="font-mono">{{ $order->expires_at?->format('d M Y, H:i') ?? '24 Jam' }} WIB</strong> agar pesanan tidak kedaluwarsa.</p>
            </div>
            <div class="flex items-center gap-3">
                <button
                    type="button"
                    wire:click="payNow"
                    wire:loading.attr="disabled"
                    class="px-5 py-2.5 bg-botanic text-white text-xs uppercase tracking-widest font-semibold hover:bg-sage-700 transition-colors whitespace-nowrap shadow-xs rounded-xs disabled:opacity-50 cursor-pointer"
                >
                    <span wire:loading.remove wire:target="payNow">Bayar Sekarang &rarr;</span>
                    <span wire:loading wire:target="payNow">Menghubungkan...</span>
                </button>
                <button
                    type="button"
                    wire:click="cancelOrder"
                    wire:confirm="Apakah Anda yakin ingin membatalkan pesanan ini? Stok yang telah direservasi akan dilepaskan kembali."
                    class="px-4 py-2.5 border border-amber-300 text-amber-950 text-xs uppercase tracking-widest font-medium hover:bg-amber-100 transition-colors whitespace-nowrap"
                >
                    Batalkan
                </button>
            </div>
        </div>
    @endif

    <!-- Timeline Status -->
    <div class="border border-sage-200/90 bg-white/95 rounded-lg shadow-2xs p-6 sm:p-8 mb-8">
        <h2 class="text-xs uppercase tracking-[0.2em] font-semibold text-botanic mb-6 pb-2 border-b border-sage-200/80 flex items-center">
            <span class="inline-block w-1.5 h-1.5 rounded-full bg-bloom mr-2"></span>
            Status Pelacakan Pesanan
        </h2>

        @if(in_array($order->status->value, ['cancelled', 'expired']))
            <div class="p-4 border border-rose-200 bg-rose-50/70 text-xs text-rose-800 space-y-1 rounded-sm">
                <span class="font-medium uppercase tracking-wider">Pesanan {{ $order->status->label() }}</span>
                <p>{{ $order->cancel_reason ?? 'Pesanan telah dibatalkan atau waktu pembayaran telah habis.' }}</p>
                <span class="text-[11px] font-mono text-rose-500">{{ $order->cancelled_at?->format('d M Y, H:i') }}</span>
            </div>
        @else
            @php
                $isPaid = !empty($order->paid_at) || in_array($order->status->value, ['paid', 'processing', 'shipped', 'completed']);
                $isProcessing = in_array($order->status->value, ['processing', 'shipped', 'completed']);
                $isShipped = !empty($order->shipped_at) || in_array($order->status->value, ['shipped', 'completed']);
                $isCompleted = !empty($order->completed_at) || $order->status->value === 'completed';
            @endphp
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 sm:gap-2 text-center text-xs">
                <!-- Step 1: Dibuat -->
                <div class="space-y-2 p-3 bg-sage-50/80 border border-botanic rounded-sm">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-botanic text-white text-[10px] font-mono font-bold shadow-2xs">1</span>
                    <p class="font-semibold text-botanic uppercase tracking-wider text-[11px]">Dibuat</p>
                    <p class="text-[10px] font-mono text-stone-500">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                </div>

                <!-- Step 2: Dibayar -->
                <div class="space-y-2 p-3 {{ $isPaid ? 'bg-sage-50/80 border border-botanic rounded-sm' : 'bg-white border border-sage-200/80 opacity-60 rounded-sm' }}">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full {{ $isPaid ? 'bg-botanic text-white shadow-2xs' : 'bg-stone-200 text-stone-600' }} text-[10px] font-mono font-bold">2</span>
                    <p class="font-semibold {{ $isPaid ? 'text-botanic' : 'text-stone-600' }} uppercase tracking-wider text-[11px]">Dibayar</p>
                    <p class="text-[10px] font-mono text-stone-500">{{ $order->paid_at?->format('d/m/Y H:i') ?? 'Menunggu' }}</p>
                </div>

                <!-- Step 3: Dikirim -->
                <div class="space-y-2 p-3 {{ $isShipped ? 'bg-sage-50/80 border border-botanic rounded-sm' : 'bg-white border border-sage-200/80 opacity-60 rounded-sm' }}">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full {{ $isShipped ? 'bg-botanic text-white shadow-2xs' : 'bg-stone-200 text-stone-600' }} text-[10px] font-mono font-bold">3</span>
                    <p class="font-semibold {{ $isShipped ? 'text-botanic' : 'text-stone-600' }} uppercase tracking-wider text-[11px]">Dikirim</p>
                    <p class="text-[10px] font-mono text-stone-500">{{ $order->shipped_at?->format('d/m/Y H:i') ?? 'Proses kemas' }}</p>
                    @if($order->tracking_number)
                        <div class="mt-1 pt-1 border-t border-sage-200/80">
                            <span class="text-[9px] font-mono text-stone-500 uppercase block">Resi ({{ $order->courier }}):</span>
                            <span class="font-mono font-bold text-botanic select-all text-[11px]">{{ $order->tracking_number }}</span>
                        </div>
                    @endif
                </div>

                <!-- Step 4: Selesai -->
                <div class="space-y-2 p-3 {{ $isCompleted ? 'bg-sage-50/80 border border-botanic rounded-sm' : 'bg-white border border-sage-200/80 opacity-60 rounded-sm' }}">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full {{ $isCompleted ? 'bg-botanic text-white shadow-2xs' : 'bg-stone-200 text-stone-600' }} text-[10px] font-mono font-bold">4</span>
                    <p class="font-semibold {{ $isCompleted ? 'text-botanic' : 'text-stone-600' }} uppercase tracking-wider text-[11px]">Selesai</p>
                    <p class="text-[10px] font-mono text-stone-500">{{ $order->completed_at?->format('d/m/Y H:i') ?? '-' }}</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Order Items & Calculation -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Items & Details -->
        <div class="lg:col-span-2 space-y-6">
            <div class="border border-bone bg-paper p-6 space-y-4">
                <h3 class="text-xs uppercase tracking-[0.2em] font-medium text-obsidian pb-2 border-b border-bone">
                    Barang yang Dipesan
                </h3>

                <div class="divide-y divide-bone">
                    @foreach($order->items as $item)
                        <div class="py-3 flex items-center justify-between text-xs">
                            <div class="space-y-1">
                                <span class="font-medium text-obsidian block">{{ $item->product_name }}</span>
                                <span class="font-mono text-[11px] text-neutral-500">{{ $item->variant_label }} &bull; {{ $item->quantity }} pcs &times; Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                            </div>
                            <span class="font-mono font-semibold text-obsidian">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Riwayat Aktivitas Status -->
            @if($order->statusHistories->isNotEmpty())
                <div class="border border-bone bg-paper p-6 space-y-4">
                    <h3 class="text-xs uppercase tracking-[0.2em] font-medium text-obsidian pb-2 border-b border-bone">
                        Catatan Log Pesanan
                    </h3>

                    <div class="space-y-3">
                        @foreach($order->statusHistories as $history)
                            <div class="flex items-start justify-between text-xs text-neutral-600 border-b border-bone/60 pb-2">
                                <div>
                                    <span class="font-medium text-obsidian">{{ $history->note ?? $history->to_status }}</span>
                                </div>
                                <span class="font-mono text-[10px] text-neutral-400 whitespace-nowrap ml-4">
                                    {{ \Carbon\Carbon::parse($history->created_at)->format('d M H:i') }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Summary & Address Column -->
        <div class="space-y-6">
            <!-- Cost Calculation -->
            <div class="border border-bone bg-paper p-6 space-y-3 text-xs">
                <h3 class="text-xs uppercase tracking-[0.2em] font-medium text-obsidian pb-2 border-b border-bone">
                    Rincian Pembayaran
                </h3>

                <div class="flex justify-between text-neutral-600">
                    <span>Subtotal Produk:</span>
                    <span class="font-mono">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>

                @if($order->discount_total > 0)
                    <div class="flex justify-between text-emerald-700 font-medium">
                        <span>Diskon ({{ $order->voucher_code }}):</span>
                        <span class="font-mono">- Rp {{ number_format($order->discount_total, 0, ',', '.') }}</span>
                    </div>
                @endif

                <div class="flex justify-between text-neutral-600">
                    <span>Ongkir ({{ $order->courier }} {{ $order->courier_service }}):</span>
                    <span class="font-mono">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                </div>

                <div class="pt-3 border-t border-bone flex justify-between items-baseline font-semibold text-obsidian">
                    <span class="uppercase tracking-wider">Total:</span>
                    <span class="font-mono text-base font-bold">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="border border-bone bg-paper p-6 space-y-2 text-xs">
                <h3 class="text-xs uppercase tracking-[0.2em] font-medium text-obsidian pb-2 border-b border-bone">
                    Alamat Pengiriman
                </h3>

                <p class="font-medium text-obsidian">{{ $order->shipping_address['recipient_name'] ?? $order->customer_name }}</p>
                <p class="font-mono text-[11px] text-neutral-500">{{ $order->shipping_address['phone'] ?? $order->customer_phone }}</p>
                <p class="text-neutral-600 text-[11px] leading-relaxed">
                    {{ $order->shipping_address['detail'] ?? '' }}<br>
                    {{ $order->shipping_address['district'] ?? '' }}{{ !empty($order->shipping_address['district']) ? ', ' : '' }}{{ $order->shipping_address['city'] ?? '' }}<br>
                    {{ $order->shipping_address['province'] ?? '' }} {{ $order->shipping_address['postal_code'] ?? '' }}
                </p>
            </div>
        </div>
    </div>
</div>
