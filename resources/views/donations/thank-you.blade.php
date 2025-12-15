<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Terima kasih - Donasi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-900">
<div class="min-h-screen bg-gradient-to-b from-emerald-500/10 via-slate-50 to-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-brand-maroon to-brand-maroonDark text-white shadow-xl">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_20%,rgba(255,255,255,0.15),transparent_35%),radial-gradient(circle_at_80%_0%,rgba(255,255,255,0.12),transparent_30%)]"></div>
            <div class="relative p-8 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div class="space-y-2">
                    <p class="text-sm font-semibold uppercase tracking-[0.25em] text-emerald-50">Tahap 3/3</p>
                    <h1 class="text-3xl font-semibold">Terima kasih atas donasimu!</h1>
                    <p class="text-white/90 text-sm">Semoga menjadi amal jariyah dan keberkahan untukmu.</p>
                </div>
                <div class="rounded-2xl bg-white/15 px-4 py-3 text-sm">
                    <div class="flex items-center justify-between text-white/90 gap-4"><span>Program</span><span class="font-semibold text-right truncate max-w-[12rem]">{{ $program->title }}</span></div>
                    <div class="flex items-center justify-between text-white/90 gap-4"><span>Nominal</span><span class="font-semibold text-right">Rp{{ number_format($donation->amount, 0, ',', '.') }}</span></div>
                    <div class="flex items-center justify-between text-white/90 gap-4"><span>Status</span><span class="font-semibold text-right capitalize">{{ $donation->status }}</span></div>
                </div>
            </div>
        </div>

        <div class="rounded-3xl bg-white shadow-lg border border-slate-100 p-8 space-y-4 text-center">
            <p class="text-slate-600">Donasi untuk <span class="font-semibold text-slate-900 inline-block max-w-xs truncate align-bottom">{{ $program->title }}</span> sudah tercatat. Admin akan memverifikasi bukti transfer.</p>
            <div class="rounded-xl border border-slate-100 bg-slate-50 p-4 text-left space-y-1 text-sm text-slate-700">
                <div class="flex justify-between"><span>Nama</span><span class="font-semibold">{{ $donation->donor_name }}</span></div>
                <div class="flex justify-between"><span>Nominal</span><span class="font-semibold">Rp{{ number_format($donation->amount, 0, ',', '.') }}</span></div>
                <div class="flex justify-between"><span>Status</span><span class="font-semibold capitalize">{{ $donation->status }}</span></div>
            </div>
            <div class="flex flex-col gap-3 sm:flex-row sm:justify-center">
                <a href="{{ route('programs.show', $program->slug) }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-800 hover:border-teal-300 hover:text-teal-700 transition">Kembali ke program</a>
                <a href="{{ url('/programs') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-brand-maroon to-brand-maroonDark px-4 py-2 text-sm font-semibold text-white shadow hover:shadow-lg transition">Lihat program lain</a>
            </div>
            @guest
                <div class="mt-4 rounded-2xl border border-emerald-100 bg-emerald-50 px-4 py-4 text-left">
                    <div class="text-sm font-semibold text-emerald-800">Simpan riwayat donasi kamu</div>
                    <p class="text-sm text-emerald-700 mb-3">Buat akun gratis untuk menyimpan bukti, memantau status, dan mendapat update program.</p>
                    <div class="flex flex-col sm:flex-row gap-2">
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-brand-maroon to-brand-maroonDark px-4 py-2 text-sm font-semibold text-white shadow hover:shadow-lg transition">
                            Daftar sekarang
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M13 6l6 6-6 6"/>
                            </svg>
                        </a>
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-emerald-200 px-4 py-2 text-sm font-semibold text-emerald-800 hover:border-emerald-300 hover:text-emerald-900 transition">
                            Masuk
                        </a>
                    </div>
                </div>
            @endguest
        </div>
    </div>
</div>
</body>
</html>
