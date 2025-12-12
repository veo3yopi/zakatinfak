<x-guest-layout>
    <div class="space-y-6">
        <div class="space-y-2">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-teal-600">Daftar</p>
            <h2 class="text-2xl font-semibold text-slate-900">Buat akun donatur</h2>
            <p class="text-sm text-slate-600">Simpan data donasi, terima update program, dan dapatkan e-receipt lebih mudah.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div class="space-y-1">
                <label for="name" class="text-sm font-semibold text-slate-700">Nama Lengkap</label>
                <input id="name" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm shadow-sm focus:border-teal-300 focus:ring-2 focus:ring-emerald-200" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Nama Anda">
                <x-input-error :messages="$errors->get('name')" class="mt-1" />
            </div>

            <div class="space-y-1">
                <label for="email" class="text-sm font-semibold text-slate-700">Email</label>
                <input id="email" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm shadow-sm focus:border-teal-300 focus:ring-2 focus:ring-emerald-200" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="email@example.com">
                <x-input-error :messages="$errors->get('email')" class="mt-1" />
            </div>

            <div class="space-y-1">
                <label for="password" class="text-sm font-semibold text-slate-700">Password</label>
                <input id="password" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm shadow-sm focus:border-teal-300 focus:ring-2 focus:ring-emerald-200"
                        type="password"
                        name="password"
                        required autocomplete="new-password"
                        placeholder="Minimal 8 karakter">
                <x-input-error :messages="$errors->get('password')" class="mt-1" />
            </div>

            <div class="space-y-1">
                <label for="password_confirmation" class="text-sm font-semibold text-slate-700">Konfirmasi Password</label>
                <input id="password_confirmation" class="w-full rounded-xl border border-slate-200 px-4 py-3 text-sm shadow-sm focus:border-teal-300 focus:ring-2 focus:ring-emerald-200"
                        type="password"
                        name="password_confirmation" required autocomplete="new-password"
                        placeholder="Ulangi password">
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
            </div>

            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-emerald-400 to-teal-500 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/35 transition">
                Daftar
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M13 6l6 6-6 6"/>
                </svg>
            </button>
        </form>

        <div class="text-center text-sm text-slate-600">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="font-semibold text-teal-700 hover:text-teal-800">Masuk</a>
        </div>
    </div>
</x-guest-layout>
