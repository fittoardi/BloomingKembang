<x-storefront-layout title="Pengaturan Profil — BloomingKembang">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Breadcrumb / Heading -->
        <div class="border-b border-bone pb-6 mb-8 flex flex-col sm:flex-row sm:items-baseline justify-between gap-4">
            <div>
                <span class="text-[10px] tracking-widest uppercase font-mono text-neutral-400">Portal Customer</span>
                <h1 class="text-xl sm:text-2xl font-normal uppercase tracking-wider text-obsidian mt-1">
                    Pengaturan Profil
                </h1>
            </div>
            <div class="text-xs text-neutral-500">
                Kelola data pribadi, nomor WhatsApp, dan keamanan kata sandi Anda.
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Sidebar Navigation -->
            <aside class="space-y-2 text-xs uppercase tracking-wider">
                <nav class="space-y-1">
                    <a href="{{ route('account.dashboard') }}" class="block p-3 border border-bone hover:border-neutral-300 transition-colors">
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
                    <a href="{{ route('profile.edit') }}" class="block p-3 border border-obsidian bg-bone text-obsidian font-medium">
                        Pengaturan Profil
                    </a>
                </nav>

                <div class="pt-6">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left p-3 text-neutral-400 hover:text-obsidian transition-colors text-xs uppercase tracking-widest cursor-pointer">
                            Keluar dari Akun
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Main Content Area -->
            <section class="md:col-span-3 space-y-8">
                <!-- Informasi Akun & Kontak -->
                <div class="border border-bone p-6 sm:p-8 bg-paper rounded-sm shadow-2xs">
                    <div class="max-w-xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <!-- Ubah Kata Sandi -->
                <div class="border border-bone p-6 sm:p-8 bg-paper rounded-sm shadow-2xs">
                    <div class="max-w-xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <!-- Hapus Akun -->
                <div class="border border-rose-200/80 p-6 sm:p-8 bg-rose-50/20 rounded-sm">
                    <div class="max-w-xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </section>
        </div>
    </div>
</x-storefront-layout>
