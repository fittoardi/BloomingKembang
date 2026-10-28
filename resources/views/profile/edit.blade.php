<x-storefront-layout title="Pengaturan Profil — BloomingKembang">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Breadcrumb / Heading -->
        <div class="border-b border-sage-200/80 pb-6 mb-8 flex flex-col sm:flex-row sm:items-baseline justify-between gap-4">
            <div>
                <span class="text-[10px] tracking-widest uppercase font-mono text-botanic font-semibold flex items-center gap-1.5">
                    <span class="inline-block w-1.5 h-1.5 rounded-full bg-bloom"></span>
                    Portal Customer
                </span>
                <h1 class="text-xl sm:text-2xl font-normal uppercase tracking-wider text-stone-900 mt-1">
                    Pengaturan Profil
                </h1>
            </div>
            <div class="text-xs text-stone-500">
                Kelola data pribadi, nomor WhatsApp, dan keamanan kata sandi Anda.
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Sidebar Navigation -->
            <aside class="space-y-2 text-xs uppercase tracking-wider">
                <nav class="space-y-1">
                    <a href="{{ route('account.dashboard') }}" class="block p-3 border border-sage-200/80 bg-white/70 hover:border-bloom hover:text-bloom text-stone-700 transition-colors rounded-xs">
                        Dashboard
                    </a>
                    <a href="{{ url('/akun/pesanan') }}" class="block p-3 border border-sage-200/80 bg-white/70 hover:border-bloom hover:text-bloom text-stone-700 transition-colors rounded-xs">
                        Riwayat Pesanan
                    </a>
                    <a href="{{ url('/akun/alamat') }}" class="block p-3 border border-sage-200/80 bg-white/70 hover:border-bloom hover:text-bloom text-stone-700 transition-colors rounded-xs">
                        Buku Alamat
                    </a>
                    <a href="{{ url('/akun/wishlist') }}" class="block p-3 border border-sage-200/80 bg-white/70 hover:border-bloom hover:text-bloom text-stone-700 transition-colors rounded-xs">
                        Wishlist
                    </a>
                    <a href="{{ route('profile.edit') }}" class="block p-3 border border-botanic bg-sage-50 text-botanic font-semibold rounded-xs">
                        Pengaturan Profil
                    </a>
                </nav>

                <div class="pt-6">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left p-3 text-stone-400 hover:text-bloom transition-colors text-xs uppercase tracking-widest cursor-pointer">
                            Keluar dari Akun
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Main Content Area -->
            <section class="md:col-span-3 space-y-8">
                <!-- Informasi Akun & Kontak -->
                <div class="border border-sage-200/90 p-6 sm:p-8 bg-white/95 rounded-lg shadow-2xs">
                    <div class="max-w-xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <!-- Ubah Kata Sandi -->
                <div class="border border-sage-200/90 p-6 sm:p-8 bg-white/95 rounded-lg shadow-2xs">
                    <div class="max-w-xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <!-- Hapus Akun -->
                <div class="border border-rose-200/80 p-6 sm:p-8 bg-rose-50/20 rounded-lg shadow-2xs">
                    <div class="max-w-xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </section>
        </div>
    </div>
</x-storefront-layout>
