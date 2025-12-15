<section class="space-y-4">
    <header class="space-y-1">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-teal-600">Identitas</p>
        <h2 class="text-xl font-semibold text-slate-900">
            {{ __('Informasi Profil') }}
        </h2>
        <p class="text-sm text-slate-600">
            {{ __("Perbarui nama dan email yang kamu gunakan untuk donasi.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('patch')

        <div class="space-y-2">
            <label for="name" class="text-sm font-semibold text-slate-700">{{ __('Nama Lengkap') }}</label>
            <input id="name" name="name" type="text" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-teal-300 focus:outline-none focus:ring-2 focus:ring-emerald-200" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            <x-input-error class="text-xs text-brand-maroon" :messages="$errors->get('name')" />
        </div>

        <div class="space-y-2">
            <label for="email" class="text-sm font-semibold text-slate-700">{{ __('Email') }}</label>
            <input id="email" name="email" type="email" class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm focus:border-teal-300 focus:outline-none focus:ring-2 focus:ring-emerald-200" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            <x-input-error class="text-xs text-brand-maroon" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="rounded-xl border border-amber-200 bg-amber-50 px-3 py-2 text-sm text-amber-800 mt-2">
                    {{ __('Email kamu belum terverifikasi.') }}
                    <button form="send-verification" class="underline font-semibold text-amber-900 hover:text-amber-700">
                        {{ __('Kirim ulang tautan verifikasi.') }}
                    </button>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-1 font-semibold text-emerald-700">
                            {{ __('Tautan verifikasi baru telah dikirim ke email kamu.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-3">
            <button class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-brand-maroon to-brand-maroonDark px-4 py-2.5 text-sm font-semibold text-white shadow-md hover:shadow-lg transition">
                {{ __('Simpan') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-emerald-700 font-semibold"
                >{{ __('Tersimpan.') }}</p>
            @endif
        </div>
    </form>
</section>
