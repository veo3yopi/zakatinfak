<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Zakat Impact') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: "Space Grotesk", "Inter", system-ui, sans-serif; }
    </style>
</head>
<body class="bg-brand-offwhite text-brand-charcoal antialiased">
    <div class="min-h-screen relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-brand-maroon via-brand-maroonDark to-brand-charcoal"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,rgba(244,232,184,0.25),transparent_35%),radial-gradient(circle_at_80%_10%,rgba(241,132,29,0.25),transparent_30%),radial-gradient(circle_at_50%_80%,rgba(153,44,49,0.22),transparent_40%)]"></div>

        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="grid gap-10 lg:grid-cols-2 items-center">
                <div class="text-white/90 space-y-6">
                    <a href="{{ url('/') }}" class="inline-flex items-center gap-3">
                        <div class="h-12 w-12 rounded-2xl bg-white/10 border border-white/15 flex items-center justify-center text-lg font-bold text-white shadow-lg shadow-brand-maroon/20">
                            Z
                        </div>
                        <div>
                            <div class="text-xl font-semibold">{{ config('app.name', 'Zakat Impact') }}</div>
                            <div class="text-sm text-white/70">Transparan • Amanah • Cepat</div>
                        </div>
                    </a>
                    <div class="space-y-3">
                        <p class="text-sm font-semibold uppercase tracking-[0.25em] text-emerald-200">Zakat & Infak</p>
                        <h1 class="text-3xl lg:text-4xl font-semibold text-white leading-tight">Masuk untuk memantau donasi dan program pilihanmu.</h1>
                        <p class="text-white/80 max-w-xl">Kelola riwayat donasi, simpan data profil, dan dapatkan update terbaru program tanpa kehilangan jejak.</p>
                        <div class="flex flex-wrap gap-3 text-sm text-white/80">
                            <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 border border-white/15">Transparansi real-time</span>
                            <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 border border-white/15">Notifikasi program</span>
                            <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 border border-white/15">E-receipt donasi</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white/90 backdrop-blur rounded-3xl shadow-2xl shadow-brand-maroon/15 border border-white/60">
                    <div class="p-6 sm:p-8">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-10">
        <x-site-footer :settings="$settings ?? null" />
    </div>
</body>
</html>
