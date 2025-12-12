<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Terima kasih - Donasi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-900">
<div class="min-h-screen flex items-center justify-center px-4">
    <div class="max-w-xl w-full rounded-3xl bg-white shadow-lg border border-slate-100 p-8 space-y-4 text-center">
        <div class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100 text-emerald-600 mx-auto">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7" /></svg>
        </div>
        <h1 class="text-2xl font-semibold">Terima kasih!</h1>
        <p class="text-slate-600">Donasi kamu untuk <span class="font-semibold">{{ $program->title }}</span> sudah tercatat. Admin akan memverifikasi bukti transfer.</p>
        <div class="rounded-xl border border-slate-100 bg-slate-50 p-4 text-left space-y-1 text-sm text-slate-700">
            <div class="flex justify-between"><span>Nama</span><span class="font-semibold">{{ $donation->donor_name }}</span></div>
            <div class="flex justify-between"><span>Nominal</span><span class="font-semibold">Rp{{ number_format($donation->amount, 0, ',', '.') }}</span></div>
            <div class="flex justify-between"><span>Status</span><span class="font-semibold capitalize">{{ $donation->status }}</span></div>
        </div>
        <div class="flex flex-col gap-3 sm:flex-row sm:justify-center">
            <a href="{{ route('programs.show', $program->slug) }}" class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-800 hover:border-teal-300 hover:text-teal-700 transition">Kembali ke program</a>
            <a href="{{ url('/programs') }}" class="inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-emerald-400 to-teal-500 px-4 py-2 text-sm font-semibold text-white shadow hover:shadow-lg transition">Lihat program lain</a>
        </div>
        @guest
            <p class="text-xs text-slate-500">Buat akun untuk simpan riwayat dan bukti donasi.<br><a href="{{ route('register') }}" class="text-teal-700 font-semibold">Daftar sekarang</a></p>
        @endguest
    </div>
</div>
</body>
</html>
