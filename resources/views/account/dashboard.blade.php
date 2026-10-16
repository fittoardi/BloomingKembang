<x-storefront-layout title="Akun Saya — BloomingKembang">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Breadcrumb / Heading -->
        <div class="border-b border-bone pb-6 mb-8 flex flex-col sm:flex-row sm:items-baseline justify-between gap-4">
            <div>
                <span class="text-[10px] tracking-widest uppercase font-mono text-neutral-400">Portal Customer</span>
                <h1 class="text-xl sm:text-2xl font-normal uppercase tracking-wider text-obsidian mt-1">
                    Akun Saya
                </h1>
            </div>
            <div class="text-xs text-neutral-500">
                Selamat datang kembali, <span class="text-obsidian font-medium">{{ Auth::user()->name }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Sidebar Navigation -->
            <aside class="space-y-2 text-xs uppercase tracking-wider">
                <nav class="space-y-1">
                    <a href="{{ route('account.dashboard') }}" class="block p-3 border border-obsidian bg-bone text-obsidian font-medium">
                        Dashboard
                    </a>
                    <a href="{{ url('/akun/pesanan') }}" class="block p-3 border border-bone hover:border-neutral-300 transition-colors">
                        Riwayat Pesanan
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

                <div class="pt-6">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left p-3 text-neutral-400 hover:text-obsidian transition-colors text-xs uppercase tracking-widest">
                            Keluar dari Akun
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Main Account Content -->
            <section class="md:col-span-3 space-y-8">
                <!-- Status Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                    <div class="border border-bone p-4 space-y-1 bg-paper">
                        <span class="text-[10px] uppercase font-mono text-neutral-400">Total Pesanan</span>
                        <p class="text-lg font-mono text-obsidian">0</p>
                    </div>
                    <div class="border border-bone p-4 space-y-1 bg-paper">
                        <span class="text-[10px] uppercase font-mono text-neutral-400">Pesanan Aktif</span>
                        <p class="text-lg font-mono text-obsidian">0</p>
                    </div>
                    <div class="border border-bone p-4 space-y-1 bg-paper">
                        <span class="text-[10px] uppercase font-mono text-neutral-400">Wishlist</span>
                        <p class="text-lg font-mono text-obsidian">0</p>
                    </div>
                </div>

                <!-- Account Information Summary -->
                <div class="border border-bone p-6 space-y-4 bg-paper">
                    <div class="flex items-center justify-between border-b border-bone pb-3">
                        <h2 class="text-xs uppercase tracking-widest font-medium text-obsidian">Ringkasan Profil</h2>
                        <a href="{{ route('profile.edit') }}" class="link-underlined text-xs">Ubah Profil</a>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div>
                            <span class="text-neutral-400 block text-[11px]">Nama Lengkap</span>
                            <span class="text-obsidian">{{ Auth::user()->name }}</span>
                        </div>
                        <div>
                            <span class="text-neutral-400 block text-[11px]">Email</span>
                            <span class="text-obsidian">{{ Auth::user()->email }}</span>
                        </div>
                        <div>
                            <span class="text-neutral-400 block text-[11px]">Nomor Telepon / WhatsApp</span>
                            <span class="text-obsidian">{{ Auth::user()->phone ?? 'Belum diisi' }}</span>
                        </div>
                        <div>
                            <span class="text-neutral-400 block text-[11px]">Status Akun</span>
                            <span class="text-emerald-700 font-medium">Aktif</span>
                        </div>
                    </div>
                </div>

                <!-- Default Address Snapshot -->
                <div class="border border-bone p-6 space-y-4 bg-paper">
                    <div class="flex items-center justify-between border-b border-bone pb-3">
                        <h2 class="text-xs uppercase tracking-widest font-medium text-obsidian">Alamat Pengiriman Utama</h2>
                        <a href="{{ url('/akun/alamat') }}" class="link-underlined text-xs">Kelola Alamat</a>
                    </div>
                    @php
                        $defaultAddress = Auth::user()->defaultAddress ?? Auth::user()->addresses()->first();
                    @endphp
                    @if($defaultAddress)
                        <div class="text-xs space-y-1 text-neutral-600">
                            <p class="font-medium text-obsidian">{{ $defaultAddress->recipient_name }} ({{ $defaultAddress->label }})</p>
                            <p>{{ $defaultAddress->phone }}</p>
                            <p class="leading-relaxed">{{ $defaultAddress->detail }}, {{ $defaultAddress->district }}, {{ $defaultAddress->city }}, {{ $defaultAddress->province }} {{ $defaultAddress->postal_code }}</p>
                        </div>
                    @else
                        <p class="text-xs text-neutral-400">Belum ada alamat tersimpan.</p>
                    @endif
                </div>
            </section>
        </div>
    </div>
</x-storefront-layout>
