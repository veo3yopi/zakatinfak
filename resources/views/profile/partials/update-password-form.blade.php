<section class="space-y-4">
    <header class="space-y-1">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-teal-600">Keamanan</p>
        <h2 class="text-xl font-semibold text-slate-900">
            {{ __('Perbarui Kata Sandi') }}
        </h2>
        <p class="text-sm text-slate-600">
            {{ __('Gunakan kata sandi yang kuat untuk menjaga keamanan akun donasi kamu.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('put')

        <div class="space-y-2">
            <label for="update_password_current_password" class="text-sm font-semibold text-slate-700">{{ __('Kata sandi saat ini') }}</label>
            <input id="update_password_current_password" name="current_password" type="password" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-teal-300 focus:outline-none focus:ring-2 focus:ring-emerald-200" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="text-xs text-brand-maroon" />
        </div>

        <div class="space-y-2">
            <label for="update_password_password" class="text-sm font-semibold text-slate-700">{{ __('Kata sandi baru') }}</label>
            <input id="update_password_password" name="password" type="password" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-teal-300 focus:outline-none focus:ring-2 focus:ring-emerald-200" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="text-xs text-brand-maroon" />
        </div>

        <div class="space-y-2">
            <label for="update_password_password_confirmation" class="text-sm font-semibold text-slate-700">{{ __('Konfirmasi kata sandi') }}</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-teal-300 focus:outline-none focus:ring-2 focus:ring-emerald-200" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="text-xs text-brand-maroon" />
        </div>

        <div class="flex items-center gap-3">
            <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-brand-maroon to-brand-maroonDark px-4 py-2.5 text-sm font-semibold text-white shadow-md hover:shadow-lg transition">
                {{ __('Simpan') }}
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm font-semibold text-emerald-700"
                >{{ __('Tersimpan.') }}</p>
            @endif
        </div>
    </form>
</section>
