<section class="space-y-4">
    <header>
        <h2 class="text-sm uppercase tracking-wider font-semibold text-rose-800">
            Hapus Akun Pengguna
        </h2>

        <p class="mt-1 text-xs text-neutral-500">
            Setelah akun Anda dihapus, semua data profil, riwayat alamat, dan wishlist akan dihapus secara permanen. Tindakan ini tidak dapat dibatalkan.
        </p>
    </header>

    <div>
        <button
            type="button"
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="px-5 py-2.5 bg-rose-700 text-white text-xs uppercase tracking-widest font-medium hover:bg-rose-800 transition-colors shadow-xs rounded-xs cursor-pointer"
        >
            Hapus Akun Saya
        </button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 sm:p-8 bg-paper">
            @csrf
            @method('delete')

            <h2 class="text-sm uppercase tracking-wider font-semibold text-obsidian">
                Apakah Anda yakin ingin menghapus akun ini?
            </h2>

            <p class="mt-2 text-xs text-neutral-500 leading-relaxed">
                Harap masukkan kata sandi Anda saat ini untuk mengonfirmasi bahwa Anda benar-benar ingin menghapus akun secara permanen.
            </p>

            <div class="mt-4">
                <label for="password" class="sr-only">Kata Sandi</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    class="w-full text-xs border border-bone bg-white p-2.5 focus:border-obsidian focus:ring-0"
                    placeholder="Masukkan kata sandi untuk konfirmasi"
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1.5" />
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button 
                    type="button" 
                    x-on:click="$dispatch('close')" 
                    class="px-4 py-2 border border-neutral-300 text-neutral-700 text-xs uppercase tracking-wider hover:bg-neutral-100 transition-colors cursor-pointer"
                >
                    Batal
                </button>

                <button 
                    type="submit" 
                    class="px-5 py-2 bg-rose-700 text-white text-xs uppercase tracking-wider font-medium hover:bg-rose-800 transition-colors cursor-pointer"
                >
                    Konfirmasi Hapus Akun
                </button>
            </div>
        </form>
    </x-modal>
</section>
