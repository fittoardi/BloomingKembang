<section>
    <header>
        <h2 class="text-sm uppercase tracking-wider font-semibold text-obsidian">
            Informasi Profil & Kontak
        </h2>

        <p class="mt-1 text-xs text-neutral-500">
            Perbarui nama lengkap, alamat email, dan nomor WhatsApp aktif Anda untuk memudahkan konfirmasi pesanan.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="block text-xs uppercase tracking-wider text-neutral-500 font-medium mb-1">Nama Lengkap</label>
            <input id="name" name="name" type="text" class="w-full text-xs border border-bone bg-white p-2.5 focus:border-obsidian focus:ring-0" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            <x-input-error class="mt-1.5" :messages="$errors->get('name')" />
        </div>

        <div>
            <label for="email" class="block text-xs uppercase tracking-wider text-neutral-500 font-medium mb-1">Alamat Email</label>
            <input id="email" name="email" type="email" class="w-full text-xs border border-bone bg-white p-2.5 focus:border-obsidian focus:ring-0" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            <x-input-error class="mt-1.5" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 p-3 bg-amber-50 border border-amber-200 text-xs text-amber-800 rounded-xs">
                    <p>
                        Email Anda belum diverifikasi.
                        <button form="send-verification" class="underline hover:text-amber-900 font-medium ml-1">
                            Kirim ulang tautan verifikasi.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-1.5 font-medium text-emerald-700">
                            Tautan verifikasi baru telah dikirimkan ke alamat email Anda.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <label for="phone" class="block text-xs uppercase tracking-wider text-neutral-500 font-medium mb-1">Nomor WhatsApp / Telepon</label>
            <input id="phone" name="phone" type="text" placeholder="Contoh: 081234567890" class="w-full text-xs border border-bone bg-white p-2.5 focus:border-obsidian focus:ring-0" value="{{ old('phone', $user->phone) }}" autocomplete="tel" />
            <span class="text-[11px] text-neutral-400 mt-1 block">Digunakan oleh kurir dan layanan pelanggan untuk koordinasi pengiriman pesanan.</span>
            <x-input-error class="mt-1.5" :messages="$errors->get('phone')" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="px-6 py-2.5 bg-botanic text-paper text-xs uppercase tracking-widest font-medium hover:bg-sage-700 transition-colors shadow-xs rounded-xs cursor-pointer">
                Simpan Perubahan
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2500)"
                    class="text-xs text-emerald-700 font-medium font-mono"
                >✓ Data profil berhasil diperbarui.</p>
            @endif
        </div>
    </form>
</section>
