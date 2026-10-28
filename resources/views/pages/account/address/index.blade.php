<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Breadcrumb / Header -->
    <div class="border-b border-bone pb-6 mb-8 flex flex-col sm:flex-row sm:items-baseline justify-between gap-4">
        <div>
            <nav class="text-[10px] tracking-widest uppercase font-mono text-neutral-400 mb-2 flex items-center space-x-2">
                <a href="{{ route('account.dashboard') }}" class="hover:text-obsidian">Akun Saya</a>
                <span>/</span>
                <span class="text-obsidian">Buku Alamat</span>
            </nav>
            <h1 class="text-xl sm:text-2xl font-normal uppercase tracking-wider text-obsidian">
                Buku Alamat Pengiriman
            </h1>
        </div>
        <button 
            type="button" 
            wire:click="openCreateModal"
            class="px-5 py-2.5 bg-botanic text-white text-xs uppercase tracking-widest font-semibold hover:bg-sage-700 transition-colors rounded-xs shadow-xs"
        >
            + Tambah Alamat Baru
        </button>
    </div>

    <!-- Flash message -->
    @if(session('status'))
        <div class="mb-6 p-4 border border-sage-200 bg-sage-50 text-xs text-botanic font-medium flex justify-between items-center rounded-sm">
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <!-- Sidebar Navigation -->
        <aside class="space-y-2 text-xs uppercase tracking-wider">
            <nav class="space-y-1">
                <a href="{{ route('account.dashboard') }}" class="block p-3 border border-sage-200/80 bg-white/70 hover:border-bloom hover:text-bloom text-stone-700 transition-colors rounded-xs">
                    Dashboard
                </a>
                <a href="{{ url('/akun/pesanan') }}" class="block p-3 border border-sage-200/80 bg-white/70 hover:border-bloom hover:text-bloom text-stone-700 transition-colors rounded-xs">
                    Pesanan Saya
                </a>
                <a href="{{ url('/akun/alamat') }}" class="block p-3 border border-botanic bg-sage-50 text-botanic font-semibold rounded-xs">
                    Buku Alamat
                </a>
                <a href="{{ url('/akun/wishlist') }}" class="block p-3 border border-sage-200/80 bg-white/70 hover:border-bloom hover:text-bloom text-stone-700 transition-colors rounded-xs">
                    Wishlist
                </a>
                <a href="{{ route('profile.edit') }}" class="block p-3 border border-sage-200/80 bg-white/70 hover:border-bloom hover:text-bloom text-stone-700 transition-colors rounded-xs">
                    Pengaturan Profil
                </a>
            </nav>
        </aside>

        <!-- Address List -->
        <section class="md:col-span-3 space-y-6">
            @if($addresses->isEmpty())
                <div class="border border-sage-200/90 p-12 text-center bg-white/95 rounded-lg shadow-2xs space-y-4">
                    <span class="text-xs uppercase font-mono tracking-widest text-stone-400 block">
                        Belum Ada Alamat Tersimpan
                    </span>
                    <p class="text-xs text-stone-500 max-w-sm mx-auto">
                        Simpan alamat pengiriman utama Anda untuk mempermudah dan mempercepat proses checkout.
                    </p>
                    <button 
                        type="button" 
                        wire:click="openCreateModal"
                        class="text-botanic hover:text-bloom underline text-xs uppercase tracking-wider font-semibold transition-colors"
                    >
                        Tambah Alamat Sekarang
                    </button>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @foreach($addresses as $addr)
                        <div class="border {{ $addr->is_default ? 'border-botanic bg-sage-50/50 ring-1 ring-botanic' : 'border-sage-200/90 bg-white/95 hover:border-sage-300' }} rounded-lg p-6 flex flex-col justify-between space-y-4 shadow-2xs transition-all">
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-mono uppercase font-semibold text-stone-900 tracking-wider">
                                        {{ $addr->label }}
                                    </span>
                                    @if($addr->is_default)
                                        <span class="text-[10px] uppercase font-mono tracking-wider bg-botanic text-white px-2 py-0.5 rounded-xs font-semibold">
                                            Alamat Utama
                                        </span>
                                    @endif
                                </div>
                                <div class="text-xs text-stone-900 space-y-0.5">
                                    <p class="font-medium">{{ $addr->recipient_name }}</p>
                                    <p class="text-stone-500 font-mono">{{ $addr->phone }}</p>
                                    <p class="pt-2 text-stone-600 leading-relaxed">{{ $addr->detail }}</p>
                                    <p class="text-stone-500 font-mono text-[11px]">
                                        {{ $addr->district }}, {{ $addr->city }}, {{ $addr->province }} {{ $addr->postal_code }}
                                    </p>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-sage-100 flex items-center justify-between text-xs">
                                <div class="flex space-x-3">
                                    <button 
                                        type="button" 
                                        wire:click="openEditModal({{ $addr->id }})"
                                        class="text-botanic hover:text-bloom font-semibold underline text-[11px] transition-colors"
                                    >
                                        Edit
                                    </button>
                                    <button 
                                        type="button" 
                                        wire:click="deleteAddress({{ $addr->id }})"
                                        wire:confirm="Yakin ingin menghapus alamat ini?"
                                        class="text-stone-400 hover:text-rose-600 text-[11px] transition-colors"
                                    >
                                        Hapus
                                    </button>
                                </div>

                                @if(! $addr->is_default)
                                    <button 
                                        type="button" 
                                        wire:click="setDefault({{ $addr->id }})"
                                        class="text-[11px] underline text-botanic hover:text-bloom font-medium transition-colors"
                                    >
                                        Jadikan Utama
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>
    </div>

    <!-- Modal Form Tambah / Edit Alamat -->
    <div 
        x-data="{ open: @entangle('isFormOpen') }"
        x-show="open" 
        x-cloak 
        class="fixed inset-0 z-50 overflow-y-auto"
    >
        <div class="fixed inset-0 bg-stone-900/40 backdrop-blur-xs transition-opacity" @click="open = false; $wire.closeForm()"></div>

        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white border border-sage-200/90 w-full max-w-xl p-6 sm:p-8 shadow-2xl rounded-lg space-y-6">
                <div class="flex items-center justify-between border-b border-sage-200/80 pb-4">
                    <h2 class="text-xs uppercase tracking-[0.2em] font-semibold text-botanic flex items-center">
                        <span class="inline-block w-1.5 h-1.5 rounded-full bg-bloom mr-2"></span>
                        {{ $editingAddressId ? 'Ubah Alamat Pengiriman' : 'Tambah Alamat Baru' }}
                    </h2>
                    <button type="button" wire:click="closeForm" class="text-stone-400 hover:text-bloom text-xs font-mono transition-colors">
                        [Tutup]
                    </button>
                </div>

                <form wire:submit.prevent="save" class="space-y-4 text-xs">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="uppercase font-medium text-stone-500 text-[10px] tracking-wider block">Label Alamat</label>
                            <input type="text" wire:model="label" placeholder="mis. Rumah, Kantor" class="w-full border border-sage-200 p-2.5 bg-white focus:border-botanic focus:ring-1 focus:ring-botanic/20 rounded-xs text-xs" />
                            @error('label') <span class="text-rose-600 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-1">
                            <label class="uppercase font-medium text-stone-500 text-[10px] tracking-wider block">Nama Penerima</label>
                            <input type="text" wire:model="recipient_name" class="w-full border border-sage-200 p-2.5 bg-white focus:border-botanic focus:ring-1 focus:ring-botanic/20 rounded-xs text-xs" />
                            @error('recipient_name') <span class="text-rose-600 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="uppercase font-medium text-stone-500 text-[10px] tracking-wider block">Nomor Telepon / WA</label>
                            <input type="text" wire:model="phone" placeholder="08xxxxxxxxxx" class="w-full border border-sage-200 p-2.5 bg-white focus:border-botanic focus:ring-1 focus:ring-botanic/20 rounded-xs font-mono text-xs" />
                            @error('phone') <span class="text-rose-600 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-1">
                            <label class="uppercase font-medium text-stone-500 text-[10px] tracking-wider block">Provinsi</label>
                            <input type="text" wire:model="province" placeholder="mis. Jawa Barat" class="w-full border border-sage-200 p-2.5 bg-white focus:border-botanic focus:ring-1 focus:ring-botanic/20 rounded-xs text-xs" />
                            @error('province') <span class="text-rose-600 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="space-y-1">
                            <label class="uppercase font-medium text-stone-500 text-[10px] tracking-wider block">Kota / Kabupaten</label>
                            <input type="text" wire:model="city" placeholder="mis. Kota Bandung" class="w-full border border-sage-200 p-2.5 bg-white focus:border-botanic focus:ring-1 focus:ring-botanic/20 rounded-xs text-xs" />
                            @error('city') <span class="text-rose-600 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-1">
                            <label class="uppercase font-medium text-stone-500 text-[10px] tracking-wider block">Kecamatan</label>
                            <input type="text" wire:model="district" placeholder="mis. Coblong" class="w-full border border-sage-200 p-2.5 bg-white focus:border-botanic focus:ring-1 focus:ring-botanic/20 rounded-xs text-xs" />
                            @error('district') <span class="text-rose-600 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-1">
                            <label class="uppercase font-medium text-stone-500 text-[10px] tracking-wider block">Kode Pos</label>
                            <input type="text" wire:model="postal_code" placeholder="40132" class="w-full border border-sage-200 p-2.5 bg-white focus:border-botanic focus:ring-1 focus:ring-botanic/20 rounded-xs font-mono text-xs" />
                            @error('postal_code') <span class="text-rose-600 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="uppercase font-medium text-stone-500 text-[10px] tracking-wider block">Alamat Lengkap & Patokan</label>
                        <textarea wire:model="detail" rows="3" placeholder="Nama jalan, nomor rumah, RT/RW, patokan lokasi" class="w-full border border-sage-200 p-2.5 bg-white focus:border-botanic focus:ring-1 focus:ring-botanic/20 rounded-xs text-xs"></textarea>
                        @error('detail') <span class="text-rose-600 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-2">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" wire:model="is_default" class="text-botanic focus:ring-botanic border-sage-300 rounded-xs" />
                            <span class="text-stone-600">Jadikan sebagai alamat pengiriman utama</span>
                        </label>
                    </div>

                    <div class="pt-4 border-t border-sage-200/80 flex items-center justify-end space-x-3">
                        <button type="button" wire:click="closeForm" class="px-4 py-2 border border-sage-200 text-stone-600 hover:border-stone-400 rounded-xs transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-6 py-2 bg-botanic text-white hover:bg-sage-700 font-semibold tracking-wider uppercase rounded-xs shadow-xs transition-colors">
                            Simpan Alamat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
