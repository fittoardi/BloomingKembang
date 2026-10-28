<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16">
    <!-- Header Checkout -->
    <div class="border-b border-bone pb-6 mb-10">
        <nav class="text-[10px] tracking-widest uppercase font-mono text-neutral-400 mb-2 flex items-center space-x-2">
            <a href="{{ url('/') }}" class="hover:text-obsidian">Beranda</a>
            <span>/</span>
            <span class="text-neutral-400">Keranjang</span>
            <span>/</span>
            <span class="text-obsidian">Checkout</span>
        </nav>
        <h1 class="text-2xl sm:text-3xl font-normal uppercase tracking-wider text-obsidian">
            Checkout Pesanan
        </h1>
    </div>

    @error('checkout_error')
        <div class="mb-8 p-4 border border-red-300 bg-red-50 text-red-800 text-xs">
            <strong>Terjadi Kendala:</strong> {{ $message }}
        </div>
    @enderror

    <form wire:submit.prevent="placeOrder">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-start">
            <!-- Left Column: Customer, Address & Courier -->
            <div class="lg:col-span-7 space-y-8">
                <!-- 1. Data Pemesan -->
                <div class="border border-sage-200/80 p-6 sm:p-8 bg-white/90 backdrop-blur-xs space-y-4 rounded-sm shadow-2xs">
                    <div class="flex items-center justify-between border-b border-sage-100 pb-3">
                        <h2 class="text-xs uppercase tracking-[0.2em] font-semibold text-obsidian flex items-center gap-2">
                            <span class="w-5 h-5 rounded-full bg-sage-100 text-botanic font-mono font-bold inline-flex items-center justify-center text-[11px] border border-sage-200">1</span>
                            <span>Data Kontak Pemesan</span>
                        </h2>
                        @guest
                            <a href="{{ route('login') }}" class="link-underlined text-xs">
                                Masuk ke Akun
                            </a>
                        @endguest
                    </div>

                    <div class="space-y-4 text-xs">
                        <div class="space-y-1">
                            <label class="uppercase font-medium text-neutral-500 text-[10px] tracking-wider block">Nama Lengkap</label>
                            <input type="text" wire:model="name" class="w-full border border-bone p-2.5 bg-white focus:border-obsidian focus:ring-0" />
                            @error('name') <span class="text-red-600 text-[10px]">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="uppercase font-medium text-neutral-500 text-[10px] tracking-wider block">Alamat Email (Untuk Bukti & Invoice)</label>
                                <input type="email" wire:model="email" class="w-full border border-bone p-2.5 bg-white focus:border-obsidian focus:ring-0" />
                                @error('email') <span class="text-red-600 text-[10px]">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="uppercase font-medium text-neutral-500 text-[10px] tracking-wider block">Nomor WhatsApp / HP</label>
                                <input type="text" wire:model="phone" placeholder="08xxxxxxxxxx" class="w-full border border-bone p-2.5 bg-white focus:border-obsidian focus:ring-0 font-mono" />
                                @error('phone') <span class="text-red-600 text-[10px]">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Alamat Pengiriman -->
                <div class="border border-sage-200/80 p-6 sm:p-8 bg-white/90 backdrop-blur-xs space-y-4 rounded-sm shadow-2xs">
                    <div class="flex items-center justify-between border-b border-sage-100 pb-3">
                        <h2 class="text-xs uppercase tracking-[0.2em] font-semibold text-obsidian flex items-center gap-2">
                            <span class="w-5 h-5 rounded-full bg-sage-100 text-botanic font-mono font-bold inline-flex items-center justify-center text-[11px] border border-sage-200">2</span>
                            <span>Alamat Tujuan Pengiriman</span>
                        </h2>
                        @auth
                            @if($savedAddresses->isNotEmpty())
                                <button type="button" wire:click="toggleNewAddress" class="link-underlined text-xs">
                                    {{ $isNewAddress ? 'Pilih Alamat Tersimpan' : '+ Gunakan Alamat Lain' }}
                                </button>
                            @endif
                        @endauth
                    </div>

                    <!-- Pilihan Alamat Tersimpan untuk Auth User -->
                    @auth
                        @if($savedAddresses->isNotEmpty() && ! $isNewAddress)
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach($savedAddresses as $addr)
                                    <div 
                                        wire:click="selectAddress({{ $addr->id }})"
                                        class="cursor-pointer border p-4 transition-all text-xs rounded-xs {{ $selectedAddressId === $addr->id ? 'border-botanic bg-sage-50/60 ring-1 ring-botanic' : 'border-sage-200/90 hover:border-bloom bg-white' }}"
                                    >
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="font-mono uppercase font-medium text-[11px]">{{ $addr->label }}</span>
                                            @if($addr->is_default)
                                                <span class="text-[9px] uppercase font-mono bg-obsidian text-paper px-1.5 py-0.5">Utama</span>
                                            @endif
                                        </div>
                                        <p class="font-medium text-obsidian">{{ $addr->recipient_name }}</p>
                                        <p class="text-neutral-500 font-mono text-[11px]">{{ $addr->phone }}</p>
                                        <p class="text-neutral-600 text-[11px] line-clamp-2 mt-1">{{ $addr->detail }}, {{ $addr->city }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endauth

                    <!-- Form Input Alamat Baru / Guest -->
                    @if($isNewAddress || $savedAddresses->isEmpty())
                        <div class="space-y-4 text-xs pt-2">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-1">
                                    <label class="uppercase font-medium text-neutral-500 text-[10px] tracking-wider block">Nama Penerima Paket</label>
                                    <input type="text" wire:model="recipient_name" class="w-full border border-bone p-2.5 bg-white focus:border-obsidian focus:ring-0" />
                                    @error('recipient_name') <span class="text-red-600 text-[10px]">{{ $message }}</span> @enderror
                                </div>
                                <div class="space-y-1">
                                    <label class="uppercase font-medium text-neutral-500 text-[10px] tracking-wider block">Nomor Telepon Penerima</label>
                                    <input type="text" wire:model="recipient_phone" placeholder="08xxxxxxxxxx" class="w-full border border-bone p-2.5 bg-white focus:border-obsidian focus:ring-0 font-mono" />
                                    @error('recipient_phone') <span class="text-red-600 text-[10px]">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="space-y-1">
                                    <label class="uppercase font-medium text-neutral-500 text-[10px] tracking-wider block">Provinsi</label>
                                    <input type="text" wire:model.live.debounce.500ms="province" placeholder="Jawa Barat" class="w-full border border-bone p-2.5 bg-white focus:border-obsidian focus:ring-0" />
                                    @error('province') <span class="text-red-600 text-[10px]">{{ $message }}</span> @enderror
                                </div>
                                <div class="space-y-1">
                                    <label class="uppercase font-medium text-neutral-500 text-[10px] tracking-wider block">Kota / Kabupaten</label>
                                    <input type="text" wire:model.live.debounce.500ms="city" placeholder="Kota Bandung" class="w-full border border-bone p-2.5 bg-white focus:border-obsidian focus:ring-0" />
                                    @error('city') <span class="text-red-600 text-[10px]">{{ $message }}</span> @enderror
                                </div>
                                <div class="space-y-1">
                                    <label class="uppercase font-medium text-neutral-500 text-[10px] tracking-wider block">Kecamatan</label>
                                    <input type="text" wire:model="district" placeholder="Coblong" class="w-full border border-bone p-2.5 bg-white focus:border-obsidian focus:ring-0" />
                                    @error('district') <span class="text-red-600 text-[10px]">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="space-y-1 sm:col-span-2">
                                    <label class="uppercase font-medium text-neutral-500 text-[10px] tracking-wider block">Alamat Lengkap (Nama Jalan, No. Rumah, RT/RW)</label>
                                    <textarea wire:model="detail" rows="2" class="w-full border border-bone p-2.5 bg-white focus:border-obsidian focus:ring-0"></textarea>
                                    @error('detail') <span class="text-red-600 text-[10px]">{{ $message }}</span> @enderror
                                </div>
                                <div class="space-y-1">
                                    <label class="uppercase font-medium text-neutral-500 text-[10px] tracking-wider block">Kode Pos</label>
                                    <input type="text" wire:model="postal_code" placeholder="40132" class="w-full border border-bone p-2.5 bg-white focus:border-obsidian focus:ring-0 font-mono" />
                                    @error('postal_code') <span class="text-red-600 text-[10px]">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- 3. Pilihan Ekspedisi Pengiriman -->
                <div class="border border-sage-200/80 p-6 sm:p-8 bg-white/90 backdrop-blur-xs space-y-4 rounded-sm shadow-2xs">
                    <div class="flex items-center justify-between border-b border-sage-100 pb-3">
                        <h2 class="text-xs uppercase tracking-[0.2em] font-semibold text-obsidian flex items-center gap-2">
                            <span class="w-5 h-5 rounded-full bg-sage-100 text-botanic font-mono font-bold inline-flex items-center justify-center text-[11px] border border-sage-200">3</span>
                            <span>Layanan Kurir Pengiriman</span>
                        </h2>
                        <span class="text-[11px] font-mono text-neutral-400">
                            Berat: {{ number_format($totalWeight, 0, ',', '.') }} gram
                        </span>
                    </div>

                    <div class="space-y-3">
                        @foreach($shippingRates as $rate)
                            @php
                                $valKey = "{$rate['courier']}|{$rate['service']}|{$rate['cost']}";
                            @endphp
                            <label class="flex items-center justify-between p-4 border cursor-pointer transition-colors text-xs rounded-xs {{ $selectedCourierKey === $valKey ? 'border-botanic bg-sage-50/60 ring-1 ring-botanic' : 'border-sage-200/90 hover:border-bloom bg-white' }}">
                                <div class="flex items-center space-x-3">
                                    <input type="radio" wire:model.live="selectedCourierKey" value="{{ $valKey }}" class="text-botanic focus:ring-botanic border-sage-300" />
                                    <div>
                                        <span class="font-medium text-obsidian">{{ $rate['name'] }}</span>
                                        <span class="text-[11px] text-neutral-400 block font-mono">Estimasi tiba: {{ $rate['etd'] }}</span>
                                    </div>
                                </div>
                                <span class="font-mono font-medium text-obsidian">
                                    Rp {{ number_format($rate['cost'], 0, ',', '.') }}
                                </span>
                            </label>
                        @endforeach
                        @error('selectedCourierKey') <span class="text-red-600 text-[10px]">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- 4. Catatan Pesanan -->
                <div class="border border-sage-200/80 p-6 bg-white/90 backdrop-blur-xs space-y-2 rounded-sm shadow-2xs">
                    <label class="uppercase font-medium text-neutral-500 text-[10px] tracking-wider block">Catatan untuk Penjual / Ekspedisi (Opsional)</label>
                    <input type="text" wire:model="customerNote" placeholder="mis. Titipkan ke satpam jika sedang keluar rumah" class="w-full text-xs border border-bone p-2.5 bg-white focus:border-obsidian focus:ring-0" />
                </div>
            </div>

            <!-- Right Column: Ringkasan Order & Checkout CTA -->
            <div class="lg:col-span-5 space-y-8 sticky top-24">
                <div class="border border-sage-200/80 p-6 sm:p-8 bg-white/95 backdrop-blur-xs space-y-6 rounded-sm shadow-2xs">
                    <h2 class="text-xs uppercase tracking-[0.2em] font-medium text-obsidian pb-3 border-b border-sage-100">
                        Ringkasan Belanja
                    </h2>

                    <!-- Items in Cart -->
                    <div class="space-y-4 max-h-72 overflow-y-auto pr-1">
                        @foreach($items as $item)
                            @php
                                $var = $item->variant;
                                $prod = $var?->product;
                            @endphp
                            <div class="flex items-center justify-between text-xs pb-3 border-b border-bone gap-3">
                                <div class="flex items-center space-x-3 min-w-0">
                                    <div class="w-10 h-12 bg-bone border border-bone flex-shrink-0 flex items-center justify-center font-mono text-[9px] text-neutral-400">
                                        {{ $item->quantity }}x
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-normal text-obsidian truncate">{{ $prod?->name ?? 'Produk' }}</p>
                                        <p class="text-[10px] text-neutral-500 font-mono">{{ $var?->color }} {{ $var?->size ? '&bull; ' . $var->size : '' }}</p>
                                    </div>
                                </div>
                                <span class="font-mono font-medium text-obsidian flex-shrink-0">
                                    Rp {{ number_format(($var?->price ?? 0) * $item->quantity, 0, ',', '.') }}
                                </span>
                            </div>
                        @endforeach
                    </div>

                    {{-- Kode Voucher Promo Sementara Dinonaktifkan --}}

                    <!-- Rincian Biaya -->
                    <div class="space-y-2 text-xs pt-4 border-t border-bone">
                        <div class="flex justify-between text-neutral-600">
                            <span>Subtotal Belanja:</span>
                            <span class="font-mono">Rp {{ number_format($pricing['subtotal'], 0, ',', '.') }}</span>
                        </div>
                        @if($pricing['discount_total'] > 0)
                            <div class="flex justify-between text-emerald-700 font-medium">
                                <span>Diskon Voucher:</span>
                                <span class="font-mono">- Rp {{ number_format($pricing['discount_total'], 0, ',', '.') }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between text-neutral-600">
                            <span>Ongkos Kirim:</span>
                            <span class="font-mono">Rp {{ number_format($pricing['shipping_cost'], 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-baseline pt-3 border-t border-sage-100 text-obsidian">
                            <span class="uppercase tracking-widest text-xs font-semibold">Total Pembayaran:</span>
                            <span class="font-mono text-lg sm:text-xl font-bold text-botanic">Rp {{ number_format($pricing['grand_total'], 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Tombol Submit Order -->
                    <div class="pt-4">
                        <button 
                            type="submit" 
                            wire:loading.attr="disabled"
                            wire:target="placeOrder"
                            class="w-full py-4 bg-botanic text-paper text-xs uppercase tracking-widest font-medium hover:bg-sage-700 transition-colors shadow-sm text-center rounded-xs disabled:opacity-60 cursor-pointer flex items-center justify-center space-x-2"
                        >
                            <span wire:loading.remove wire:target="placeOrder">Buat Pesanan & Bayar &rarr;</span>
                            <span wire:loading wire:target="placeOrder" class="inline-flex items-center space-x-2">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                                </svg>
                                <span>Memproses Pesanan Anda...</span>
                            </span>
                        </button>
                        <p class="text-[10px] text-neutral-400 text-center mt-3 leading-relaxed">
                            Dengan mengklik tombol di atas, Anda menyetujui syarat & ketentuan pemesanan di BloomingKembang.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Begin Checkout Analytics Event Tracking (GA4 & Meta Pixel) -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof window.trackEcommerce === 'function') {
                window.trackEcommerce('begin_checkout', {
                    currency: 'IDR',
                    value: {{ (int) $pricing['grand_total'] }},
                    coupon: '{{ $appliedVoucherCode ?? "" }}',
                    items: [
                        @foreach($items as $it)
                        {
                            item_id: '{{ $it->variant?->sku }}',
                            item_name: '{{ addslashes($it->variant?->product?->name ?? "Produk") }}',
                            price: {{ (int) ($it->variant?->price ?? 0) }},
                            quantity: {{ (int) $it->quantity }}
                        }@if(!$loop->last),@endif
                        @endforeach
                    ]
                });
            }
        });
    </script>
</div>
