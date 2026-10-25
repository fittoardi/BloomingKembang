<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Breadcrumb / Header -->
    <div class="border-b border-bone pb-6 mb-8 flex flex-col sm:flex-row sm:items-baseline justify-between gap-4">
        <div>
            <nav class="text-[10px] tracking-widest uppercase font-mono text-neutral-400 mb-2 flex items-center space-x-2">
                <a href="{{ route('account.dashboard') }}" class="hover:text-obsidian">Akun Saya</a>
                <span>/</span>
                <span class="text-obsidian">Pesanan Saya</span>
            </nav>
            <h1 class="text-xl sm:text-2xl font-normal uppercase tracking-wider text-obsidian">
                Riwayat Pesanan
            </h1>
        </div>
        <a 
            href="{{ url('/koleksi') }}"
            class="px-4 py-2.5 bg-obsidian text-paper text-xs uppercase tracking-widest font-medium hover:bg-neutral-800 transition-colors text-center"
        >
            Belanja Lagi
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <!-- Sidebar Navigation -->
        <aside class="space-y-2 text-xs uppercase tracking-wider">
            <nav class="space-y-1">
                <a href="{{ route('account.dashboard') }}" class="block p-3 border border-bone hover:border-neutral-300 transition-colors">
                    Dashboard
                </a>
                <a href="{{ url('/akun/pesanan') }}" class="block p-3 border border-obsidian bg-bone text-obsidian font-medium">
                    Pesanan Saya
                </a>
                <a href="{{ url('/akun/alamat') }}" class="block p-3 border border-bone hover:border-neutral-300 transition-colors">
                    Buku Alamat
                </a>
                <a href="{{ url('/akun/wishlist') }}" class="block p-3 border border-bone hover:border-neutral-300 transition-colors">
                    Wishlist
                </a>
                <a href="{{ route('profile.edit') }}" class="block p-3 border border-bone hover:border-neutral-300 transition-colors">
                    Pengaturan Profil
                </a>
            </nav>
        </aside>

        <!-- Orders Section -->
        <section class="md:col-span-3 space-y-6">
            <!-- Filter Tabs -->
            <div class="flex items-center gap-2 overflow-x-auto pb-2 border-b border-bone text-xs font-mono uppercase tracking-wider">
                @php
                    $tabs = [
                        'all' => 'Semua',
                        'pending_payment' => 'Menunggu Pembayaran',
                        'processing' => 'Diproses',
                        'shipped' => 'Dikirim',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                    ];
                @endphp
                @foreach($tabs as $key => $label)
                    <button
                        type="button"
                        wire:click="setFilter('{{ $key }}')"
                        class="px-3 py-1.5 whitespace-nowrap transition-colors {{ $statusFilter === $key ? 'bg-obsidian text-paper font-medium' : 'bg-paper text-neutral-600 hover:bg-bone border border-bone' }}"
                    >
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            <!-- Orders List -->
            @if($orders->isEmpty())
                <div class="border border-bone p-12 text-center bg-paper space-y-4">
                    <span class="text-xs uppercase font-mono tracking-widest text-neutral-400 block">
                        Belum Ada Pesanan
                    </span>
                    <p class="text-xs text-neutral-500 max-w-sm mx-auto">
                        Anda belum memiliki pesanan dalam kategori status ini. Temukan koleksi hijab elegan kami dan mulai berbelanja.
                    </p>
                    <a 
                        href="{{ url('/koleksi') }}"
                        class="inline-block px-6 py-2.5 bg-obsidian text-paper text-xs uppercase tracking-widest font-medium hover:bg-neutral-800 transition-colors"
                    >
                        Jelajahi Koleksi
                    </a>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($orders as $order)
                        <div class="border border-bone bg-paper p-5 hover:border-neutral-400 transition-colors space-y-4">
                            <!-- Card Header -->
                            <div class="flex flex-wrap items-center justify-between gap-2 pb-3 border-b border-bone text-xs">
                                <div class="flex items-center gap-3">
                                    <span class="font-mono font-bold text-obsidian">#{{ $order->number }}</span>
                                    <span class="text-neutral-400">&bull;</span>
                                    <span class="text-neutral-500 font-mono text-[11px]">{{ $order->created_at->format('d M Y, H:i') }}</span>
                                </div>
                                <div>
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
                                </div>
                            </div>

                            <!-- Items Summary -->
                            <div class="divide-y divide-bone/50">
                                @foreach($order->items as $item)
                                    <div class="py-2 flex items-center justify-between text-xs">
                                        <div class="space-y-0.5">
                                            <p class="font-medium text-obsidian">{{ $item->product_name }}</p>
                                            <p class="text-[11px] font-mono text-neutral-500">{{ $item->variant_label }} &times; {{ $item->quantity }} pcs</p>
                                        </div>
                                        <span class="font-mono text-obsidian">
                                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Card Footer -->
                            <div class="flex flex-wrap items-center justify-between gap-4 pt-3 border-t border-bone text-xs">
                                <div>
                                    <span class="text-[11px] uppercase tracking-wider font-mono text-neutral-400 block">Total Pesanan</span>
                                    <span class="font-mono font-bold text-obsidian text-sm">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    @if($order->status->value === 'pending_payment')
                                        <a 
                                            href="{{ url('/akun/pesanan/' . $order->number) }}"
                                            class="px-4 py-2 bg-obsidian text-paper text-[11px] uppercase tracking-wider font-medium hover:bg-neutral-800 transition-colors"
                                        >
                                            Bayar Sekarang
                                        </a>
                                    @endif
                                    <a 
                                        href="{{ url('/akun/pesanan/' . $order->number) }}"
                                        class="px-4 py-2 border border-bone text-obsidian text-[11px] uppercase tracking-wider font-medium hover:bg-bone transition-colors"
                                    >
                                        Rincian &rarr;
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="pt-4">
                    {{ $orders->links() }}
                </div>
            @endif
        </section>
    </div>
</div>
