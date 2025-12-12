<x-guest-layout>
    <div class="space-y-6">
        <div class="space-y-2">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-teal-600">Masuk</p>
            <h2 class="text-2xl font-semibold text-slate-900">Selamat datang kembali</h2>
            <p class="text-sm text-slate-600">Akses riwayat donasi, simpan preferensi, dan ikuti progres program.</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div class="space-y-1">
                <label for="email" class="text-sm font-semibold text-slate-700">Email</label>
                <input id="email" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm shadow-sm focus:border-teal-300 focus:ring-2 focus:ring-emerald-200" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="email@example.com">
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <div class="space-y-1">
                <label for="password" class="text-sm font-semibold text-slate-700">Password</label>
                <input id="password" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm shadow-sm focus:border-teal-300 focus:ring-2 focus:ring-emerald-200"
                        type="password"
                        name="password"
                        required autocomplete="current-password"
                        placeholder="••••••••">
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <div class="flex items-center justify-between text-sm">
                <label for="remember_me" class="inline-flex items-center gap-2 text-slate-600">
                    <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-teal-600 shadow-sm focus:ring-teal-500" name="remember">
                    Ingat saya
                </label>
                @if (Route::has('password.request'))
                    <a class="text-teal-700 hover:text-teal-800 font-semibold" href="{{ route('password.request') }}">
                        Lupa password?
                    </a>
                @endif
            </div>

            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-emerald-400 to-teal-500 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/35 transition">
                Masuk
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M13 6l6 6-6 6"/>
                </svg>
            </button>
        </form>

        <div class="text-center text-sm text-slate-600">
            Belum punya akun?
            <a href="{{ route('register') }}" class="font-semibold text-teal-700 hover:text-teal-800">Daftar sekarang</a>
        </div>
    </div>
</x-guest-layout>
