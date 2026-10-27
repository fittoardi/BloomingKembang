<section>
    <header>
        <h2 class="text-sm uppercase tracking-wider font-semibold text-obsidian">
            Perbarui Kata Sandi
        </h2>

        <p class="mt-1 text-xs text-neutral-500">
            Pastikan akun Anda menggunakan kombinasi kata sandi yang aman dan tidak digunakan di situs lain.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-xs uppercase tracking-wider text-neutral-500 font-medium mb-1">Kata Sandi Saat Ini</label>
            <input id="update_password_current_password" name="current_password" type="password" class="w-full text-xs border border-bone bg-white p-2.5 focus:border-obsidian focus:ring-0" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1.5" />
        </div>

        <div>
            <label for="update_password_password" class="block text-xs uppercase tracking-wider text-neutral-500 font-medium mb-1">Kata Sandi Baru</label>
            <input id="update_password_password" name="password" type="password" class="w-full text-xs border border-bone bg-white p-2.5 focus:border-obsidian focus:ring-0" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1.5" />
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-xs uppercase tracking-wider text-neutral-500 font-medium mb-1">Konfirmasi Kata Sandi Baru</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="w-full text-xs border border-bone bg-white p-2.5 focus:border-obsidian focus:ring-0" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1.5" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="px-6 py-2.5 bg-botanic text-paper text-xs uppercase tracking-widest font-medium hover:bg-sage-700 transition-colors shadow-xs rounded-xs cursor-pointer">
                Ubah Kata Sandi
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2500)"
                    class="text-xs text-emerald-700 font-medium font-mono"
                >✓ Kata sandi berhasil diperbarui.</p>
            @endif
        </div>
    </form>
</section>
