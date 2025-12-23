<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php
        $siteTitle = $settings->site_title ?? ($settings->site_name ?? 'Tentang Kami');
        $favicon = $settings?->favicon_url;
        if ($favicon && !Str::startsWith($favicon, ['http://', 'https://'])) {
            $favicon = \Illuminate\Support\Facades\Storage::url($favicon);
        }
    @endphp
    <title>{{ $siteTitle }} • Tentang</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    @if($favicon)
        <link rel="icon" type="image/png" href="{{ $favicon }}">
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: "Space Grotesk", "Inter", system-ui, sans-serif; }
    </style>
</head>
<body class="bg-brand-offwhite text-brand-charcoal antialiased">
@php
    $navLinks = [
        ['label' => 'Home', 'href' => url('/')],
        ['label' => 'Program', 'href' => url('/programs')],
        ['label' => 'Artikel', 'href' => url('/posts')],
        ['label' => 'Tentang', 'href' => url('/about')],
    ];
    $stats = [
        ['label' => 'Penerima Manfaat', 'value' => $settings?->impact_beneficiaries ?? 12000],
        ['label' => 'Program', 'value' => $settings?->impact_programs ?? 180],
        ['label' => 'Wilayah', 'value' => $settings?->impact_regions ?? 42],
        ['label' => 'Relawan', 'value' => $settings?->impact_volunteers ?? 320],
    ];
@endphp

@include('partials.public-navbar', ['settings' => $settings, 'navLinks' => $navLinks])

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
    <section class="mt-6 overflow-hidden rounded-3xl bg-white shadow-lg shadow-slate-200/70">
        <div class="relative">
            @php
                $hero = $settings?->about_hero_url;
                if ($hero && !Str::startsWith($hero, ['http://', 'https://'])) {
                    $hero = \Illuminate\Support\Facades\Storage::url($hero);
                }
            @endphp
            <img src="{{ $hero ?? $heroImage ?? 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1600&q=80' }}" alt="Tentang" class="h-64 sm:h-80 lg:h-96 w-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent"></div>
            <div class="absolute inset-0 flex items-end p-6 sm:p-10">
                <div class="text-white drop-shadow-[0_10px_30px_rgba(0,0,0,0.35)]">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-emerald-200">Tentang</p>
                    <h1 class="text-3xl sm:text-4xl font-semibold">Tentang Kami</h1>
                </div>
            </div>
        </div>
        <div class="p-6 sm:p-8 space-y-6">
            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-5 sm:p-6 space-y-3">
                <p class="text-slate-700 leading-relaxed">
                    {{ $settings->about_summary ?? $settings->site_description ?? 'Lembaga zakat yang berkhidmat dalam pemberdayaan masyarakat melalui pemanfaatan dana zakat, infak, dan sedekah dengan transparansi dan laporan berkala.' }}
                </p>
                <p class="text-slate-700 leading-relaxed">
                    {{ $settings->about_mission ?? 'Memberdayakan umat melalui pengelolaan zakat yang profesional, amanah, dan berdampak.' }}
                </p>
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <div class="rounded-2xl bg-gradient-to-br from-brand-maroon to-brand-maroonDark p-6 text-white shadow-lg">
                    <h2 class="text-2xl font-semibold mb-3">Visi</h2>
                    <p class="text-lg leading-relaxed">{{ $settings->about_vision ?? 'Menjadi lembaga amil zakat terpercaya dan berdampak berkelanjutan.' }}</p>
                </div>
                <div class="space-y-4">
                    <details class="rounded-2xl border border-slate-100 bg-white p-4" open>
                        <summary class="text-lg font-semibold text-slate-900 cursor-pointer">Misi</summary>
                        <p class="mt-2 text-sm text-slate-600 leading-relaxed">{{ $settings->about_mission ?? 'Memberdayakan umat melalui pengelolaan zakat yang profesional dan transparan.' }}</p>
                    </details>
                    <details class="rounded-2xl border border-slate-100 bg-white p-4">
                        <summary class="text-lg font-semibold text-slate-900 cursor-pointer">Prinsip</summary>
                        <p class="mt-2 text-sm text-slate-600 leading-relaxed">{{ $settings->about_principles ?? $settings->about_values ?? 'Amanah, Transparan, Profesional, Kolaboratif.' }}</p>
                    </details>
                    <details class="rounded-2xl border border-slate-100 bg-white p-4">
                        <summary class="text-lg font-semibold text-slate-900 cursor-pointer">Tujuan</summary>
                        <p class="mt-2 text-sm text-slate-600 leading-relaxed">{{ $settings->about_goals ?? 'Meningkatkan efektivitas dana ZISWAF untuk kesejahteraan masyarakat, pendidikan, kesehatan, dan pemberdayaan ekonomi.' }}</p>
                    </details>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($stats as $stat)
                    <div class="rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                        <div class="text-2xl font-semibold text-slate-900">{{ number_format($stat['value']) }}</div>
                        <div class="text-sm text-slate-600">{{ $stat['label'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</main>

<nav class="fixed bottom-0 left-0 right-0 z-40 border-t border-slate-200 bg-white/95 backdrop-blur shadow-[0_-8px_30px_rgba(15,23,42,0.08)] md:hidden">
    <div class="relative mx-auto max-w-4xl px-4">
        <div class="grid grid-cols-4 py-3 text-xs font-semibold text-slate-600">
            @foreach ($navLinks as $link)
                <a href="{{ $link['href'] }}" class="flex flex-col items-center gap-1 rounded-lg px-2 py-1 hover:text-teal-600 transition" data-nav-link>
                    @switch($link['label'])
                        @case('Home')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9.75 12 4l9 5.75M5 10.75V19a1 1 0 001 1h4v-4h4v4h4a1 1 0 001-1v-8.25"/>
                            </svg>
                            @break
                        @case('Program')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 19h16M7 16V9m5 7v-6m5 6V7"/>
                            </svg>
                            @break
                        @case('Artikel')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h10M7 12h6m-6 5h8M6 3h12a1 1 0 011 1v16a1 1 0 01-1 1H6a1 1 0 01-1-1V4a1 1 0 011-1z"/>
                            </svg>
                            @break
                        @case('Tentang')
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6m0 6h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            @break
                        @default
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.5 6.75h15m-15 4.5h15m-15 4.5H12"/>
                            </svg>
                    @endswitch
                    <span>{{ $link['label'] }}</span>
                </a>
            @endforeach
        </div>
    </div>
</nav>

<div class="mt-16">
    <x-site-footer :settings="$settings ?? null" />
</div>

</body>
</html>
