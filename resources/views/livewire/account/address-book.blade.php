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
            class="px-4 py-2.5 bg-obsidian text-paper text-xs uppercase tracking-widest font-medium hover:bg-neutral-800 transition-colors"
        >
            + Tambah Alamat Baru
        </button>
    </div>

    <!-- Flash message -->
    @if(session('status'))
        <div class="mb-6 p-4 border border-bone bg-bone text-xs text-obsidian flex justify-between items-center">
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <!-- Sidebar Navigation -->
        <aside class="space-y-2 text-xs uppercase tracking-wider">
            <nav class="space-y-1">
                <a href="{{ route('account.dashboard') }}" class="block p-3 border border-bone hover:border-neutral-300 transition-colors">
                    Dashboard
                </a>
                <a href="{{ url('/akun/alamat') }}" class="block p-3 border border-obsidian bg-bone text-obsidian font-medium">
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

        <!-- Address List -->
        <section class="md:col-span-3 space-y-6">
            @if($addresses->isEmpty())
                <div class="border border-bone p-12 text-center bg-paper space-y-4">
                    <span class="text-xs uppercase font-mono tracking-widest text-neutral-400 block">
                        Belum Ada Alamat Tersimpan
                    </span>
                    <p class="text-xs text-neutral-500 max-w-sm mx-auto">
                        Simpan alamat pengiriman utama Anda untuk mempermudah dan mempercepat proses checkout.
                    </p>
                    <button 
                        type="button" 
                        wire:click="openCreateModal"
                        class="link-underlined text-xs uppercase tracking-wider font-medium"
                    >
                        Tambah Alamat Sekarang
                    </button>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    @foreach($addresses as $addr)
                        <div class="border {{ $addr->is_default ? 'border-obsidian bg-paper ring-1 ring-obsidian' : 'border-bone bg-paper' }} p-6 flex flex-col justify-between space-y-4">
                            <div class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-mono uppercase font-medium text-obsidian tracking-wider">
                                        {{ $addr->label }}
                                    </span>
                                    @if($addr->is_default)
                                        <span class="text-[10px] uppercase font-mono tracking-wider bg-obsidian text-paper px-2 py-0.5">
                                            Alamat Utama
                                        </span>
                                    @endif
                                </div>
                                <div class="text-xs text-obsidian space-y-0.5">
                                    <p class="font-medium">{{ $addr->recipient_name }}</p>
                                    <p class="text-neutral-500 font-mono">{{ $addr->phone }}</p>
                                    <p class="pt-2 text-neutral-600 leading-relaxed">{{ $addr->detail }}</p>
                                    <p class="text-neutral-500 font-mono text-[11px]">
                                        {{ $addr->district }}, {{ $addr->city }}, {{ $addr->province }} {{ $addr->postal_code }}
                                    </p>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-bone flex items-center justify-between text-xs">
                                <div class="flex space-x-3">
                                    <button 
                                        type="button" 
                                        wire:click="openEditModal({{ $addr->id }})"
                                        class="link-underlined text-[11px]"
                                    >
                                        Edit
                                    </button>
                                    <button 
                                        type="button" 
                                        wire:click="deleteAddress({{ $addr->id }})"
                                        wire:confirm="Yakin ingin menghapus alamat ini?"
                                        class="text-neutral-400 hover:text-red-600 text-[11px] transition-colors"
                                    >
                                        Hapus
                                    </button>
                                </div>

                                @if(! $addr->is_default)
                                    <button 
                                        type="button" 
                                        wire:click="setDefault({{ $addr->id }})"
                                        class="text-[11px] underline text-neutral-500 hover:text-obsidian"
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
        <div class="fixed inset-0 bg-obsidian/40 backdrop-blur-sm transition-opacity" @click="open = false; $wire.closeForm()"></div>

        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-paper border border-bone w-full max-w-xl p-6 sm:p-8 shadow-2xl space-y-6">
                <div class="flex items-center justify-between border-b border-bone pb-4">
                    <h2 class="text-xs uppercase tracking-[0.2em] font-medium text-obsidian">
                        {{ $editingAddressId ? 'Ubah Alamat Pengiriman' : 'Tambah Alamat Baru' }}
                    </h2>
                    <button type="button" wire:click="closeForm" class="text-neutral-400 hover:text-obsidian text-xs font-mono">
                        [Tutup]
                    </button>
                </div>

                <form wire:submit.prevent="save" class="space-y-4 text-xs">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="uppercase font-medium text-neutral-500 text-[10px] tracking-wider block">Label Alamat</label>
                            <input type="text" wire:model="label" placeholder="mis. Rumah, Kantor" class="w-full border border-bone p-2.5 bg-white focus:border-obsidian focus:ring-0" />
                            @error('label') <span class="text-red-600 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-1">
                            <label class="uppercase font-medium text-neutral-500 text-[10px] tracking-wider block">Nama Penerima</label>
                            <input type="text" wire:model="recipient_name" class="w-full border border-bone p-2.5 bg-white focus:border-obsidian focus:ring-0" />
                            @error('recipient_name') <span class="text-red-600 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="uppercase font-medium text-neutral-500 text-[10px] tracking-wider block">Nomor Telepon / WA</label>
                            <input type="text" wire:model="phone" placeholder="08xxxxxxxxxx" class="w-full border border-bone p-2.5 bg-white focus:border-obsidian focus:ring-0 font-mono" />
                            @error('phone') <span class="text-red-600 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-1">
                            <label class="uppercase font-medium text-neutral-500 text-[10px] tracking-wider block">Provinsi</label>
                            <input type="text" wire:model="province" placeholder="mis. Jawa Barat" class="w-full border border-bone p-2.5 bg-white focus:border-obsidian focus:ring-0" />
                            @error('province') <span class="text-red-600 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="space-y-1">
                            <label class="uppercase font-medium text-neutral-500 text-[10px] tracking-wider block">Kota / Kabupaten</label>
                            <input type="text" wire:model="city" placeholder="mis. Kota Bandung" class="w-full border border-bone p-2.5 bg-white focus:border-obsidian focus:ring-0" />
                            @error('city') <span class="text-red-600 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-1">
                            <label class="uppercase font-medium text-neutral-500 text-[10px] tracking-wider block">Kecamatan</label>
                            <input type="text" wire:model="district" placeholder="mis. Coblong" class="w-full border border-bone p-2.5 bg-white focus:border-obsidian focus:ring-0" />
                            @error('district') <span class="text-red-600 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-1">
                            <label class="uppercase font-medium text-neutral-500 text-[10px] tracking-wider block">Kode Pos</label>
                            <input type="text" wire:model="postal_code" placeholder="40132" class="w-full border border-bone p-2.5 bg-white focus:border-obsidian focus:ring-0 font-mono" />
                            @error('postal_code') <span class="text-red-600 text-[10px]">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="uppercase font-medium text-neutral-500 text-[10px] tracking-wider block">Alamat Lengkap & Patokan</label>
                        <textarea wire:model="detail" rows="3" placeholder="Nama jalan, nomor rumah, RT/RW, patokan lokasi" class="w-full border border-bone p-2.5 bg-white focus:border-obsidian focus:ring-0"></textarea>
                        @error('detail') <span class="text-red-600 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-2">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" wire:model="is_default" class="text-obsidian focus:ring-0 border-neutral-300" />
                            <span class="text-neutral-600">Jadikan sebagai alamat pengiriman utama</span>
                        </label>
                    </div>

                    <div class="pt-4 border-t border-bone flex items-center justify-end space-x-3">
                        <button type="button" wire:click="closeForm" class="px-4 py-2 border border-bone text-neutral-500 hover:text-obsidian">
                            Batal
                        </button>
                        <button type="submit" class="px-6 py-2 bg-obsidian text-paper hover:bg-neutral-800 font-medium tracking-wider uppercase">
                            Simpan Alamat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
