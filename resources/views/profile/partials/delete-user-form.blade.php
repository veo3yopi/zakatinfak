<section class="space-y-4">
    <header class="space-y-1">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-brand-maroon">Bahaya</p>
        <h2 class="text-xl font-semibold text-slate-900">
            {{ __('Hapus Akun') }}
        </h2>
        <p class="text-sm text-slate-600">
            {{ __('Akun dan seluruh data terkait akan dihapus permanen. Pastikan sudah mengunduh data penting sebelum melanjutkan.') }}
        </p>
    </header>

    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-brand-maroon to-brand-maroonDark px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-brand-maroon/30 hover:shadow-xl transition"
    >
        {{ __('Hapus Akun') }}
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-semibold text-slate-900">
                {{ __('Yakin hapus akun?') }}
            </h2>

            <p class="mt-1 text-sm text-slate-600">
                {{ __('Aksi ini tidak bisa dibatalkan. Masukkan kata sandi untuk konfirmasi.') }}
            </p>

            <div class="mt-4 space-y-2">
                <label for="password" class="text-sm font-semibold text-slate-700">{{ __('Kata sandi') }}</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-brand-maroon/50 focus:outline-none focus:ring-2 focus:ring-brand-maroon/20"
                    placeholder="{{ __('Password') }}"
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="text-xs text-brand-maroon" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:border-slate-300 transition">
                    {{ __('Batal') }}
                </button>

                <button class="inline-flex items-center justify-center rounded-xl bg-gradient-to-r from-brand-maroon to-brand-maroonDark px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-brand-maroon/30 hover:shadow-xl transition">
                    {{ __('Hapus Akun') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
