<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Program Zakat & Infak • Zakat Impact</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: "Space Grotesk", "Inter", system-ui, sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased">
@php
    $navLinks = [
        ['label' => 'Home', 'href' => url('/')],
        ['label' => 'Program', 'href' => '#program-list'],
        ['label' => 'Artikel', 'href' => url('/#artikel')],
        ['label' => 'Tentang', 'href' => url('/#tentang')],
    ];

    $programs = [
        [
            'title' => 'Zakat Maal Pendidikan Yatim',
            'category' => 'Zakat',
            'target' => 'Rp250.000.000',
            'progress' => 0.62,
            'location' => 'Jakarta & Bekasi',
            'image' => 'https://images.unsplash.com/photo-1488190211105-8b0e65b80b4e?auto=format&fit=crop&w=900&q=80',
            'excerpt' => 'Beasiswa dan peralatan sekolah untuk 500 anak yatim dhuafa.',
        ],
        [
            'title' => 'Sedekah Pangan Ramadhan',
            'category' => 'Sedekah',
            'target' => 'Rp500.000.000',
            'progress' => 0.48,
            'location' => 'Nasional',
            'image' => 'https://images.unsplash.com/photo-1520607162513-77705c0f0d4a?auto=format&fit=crop&w=900&q=80',
            'excerpt' => 'Distribusi paket pangan untuk keluarga prasejahtera di 20 kota.',
        ],
        [
            'title' => 'Infak Kesehatan Ibu & Anak',
            'category' => 'Infak',
            'target' => 'Rp300.000.000',
            'progress' => 0.73,
            'location' => 'Nusa Tenggara Timur',
            'image' => 'https://images.unsplash.com/photo-1526256262350-7da7584cf5eb?auto=format&fit=crop&w=900&q=80',
            'excerpt' => 'Posyandu keliling, suplementasi gizi, dan edukasi kesehatan ibu & anak.',
        ],
        [
            'title' => 'Bantuan Kemanusiaan Bencana Alam',
            'category' => 'Kemanusiaan',
            'target' => 'Rp750.000.000',
            'progress' => 0.35,
            'location' => 'Kalimantan Selatan',
            'image' => 'https://images.unsplash.com/photo-1482192596544-9eb780fc7f66?auto=format&fit=crop&w=900&q=80',
            'excerpt' => 'Respons cepat logistik, shelter, dan dapur umum untuk penyintas banjir.',
        ],
        [
            'title' => 'Program Ekonomi Mikro Syariah',
            'category' => 'Zakat',
            'target' => 'Rp400.000.000',
            'progress' => 0.56,
            'location' => 'Jawa Tengah',
            'image' => 'https://images.unsplash.com/photo-1450101215322-bf5cd27642fc?auto=format&fit=crop&w=900&q=80',
            'excerpt' => 'Modal usaha mikro dan mentoring bisnis berbasis syariah untuk 150 keluarga.',
        ],
        [
            'title' => 'Sedekah Air Bersih',
            'category' => 'Sedekah',
            'target' => 'Rp200.000.000',
            'progress' => 0.8,
            'location' => 'Nusa Tenggara Barat',
            'image' => 'https://images.unsplash.com/photo-1516826435551-36b06cc5e22d?auto=format&fit=crop&w=900&q=80',
            'excerpt' => 'Pembangunan sumur bor dan pipanisasi air bersih untuk desa terdampak kekeringan.',
        ],
    ];
@endphp

<div class="bg-gradient-to-b from-slate-900 via-slate-900/30 to-slate-50 min-h-screen">
    <header class="sticky top-0 z-30 bg-white/90 backdrop-blur shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between py-4">
                <div class="flex items-center gap-2">
                    <div class="h-11 w-11 rounded-2xl overflow-hidden bg-slate-900/10 shadow-lg">
                        <img src="https://dummyimage.com/80x80/14b8a6/ffffff&text=Z" alt="Brand logo" class="h-full w-full object-cover">
                    </div>
                    <div>
                        <div class="text-lg font-semibold text-slate-900">Zakat Impact</div>
                        <div class="text-sm text-slate-500">Transparan • Amanah • Cepat</div>
                    </div>
                </div>
                <nav class="hidden md:flex items-center gap-6 text-sm font-semibold text-slate-700">
                    @foreach ($navLinks as $link)
                        <a href="{{ $link['href'] }}" class="hover:text-teal-600 transition">{{ $link['label'] }}</a>
                    @endforeach
                </nav>
                <div class="flex items-center gap-3">
                    <a href="#" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-teal-500 to-emerald-400 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-teal-500/20 hover:shadow-teal-500/30 transition">
                        Donasi Sekarang
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M13 6l6 6-6 6"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <section class="mt-8 rounded-3xl bg-white shadow-xl shadow-slate-900/10 overflow-hidden">
            <div class="relative">
                <img src="https://images.unsplash.com/photo-1455849318743-b2233052fcff?auto=format&fit=crop&w=1600&q=80" class="h-56 w-full object-cover" alt="">
                <div class="absolute inset-0 bg-gradient-to-r from-slate-900/80 via-slate-900/40 to-transparent"></div>
                <div class="absolute inset-0 p-8 sm:p-12 flex flex-col justify-center gap-3 text-white">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-emerald-200">Program</p>
                    <h1 class="text-3xl sm:text-4xl font-semibold leading-tight">Pilih program kebaikan yang ingin kamu dukung</h1>
                    <p class="text-white/80 max-w-2xl">Telusuri zakat, infak, sedekah, dan bantuan kemanusiaan dengan laporan transparan dan progres terkini.</p>
                </div>
            </div>

            <div class="p-6 sm:p-8">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm font-semibold text-teal-600">Cari Program</p>
                        <h2 class="text-2xl font-semibold text-slate-900">Temukan yang paling relevan</h2>
                    </div>
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:gap-4 w-full sm:w-auto">
                        <div class="relative w-full sm:w-72">
                            <input type="search" placeholder="Cari judul, kategori, lokasi..." class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 pr-10 text-sm text-slate-800 shadow-sm focus:border-teal-300 focus:outline-none focus:ring-2 focus:ring-emerald-200" data-program-search>
                            <svg class="absolute right-3 top-3.5 h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 15.75L19.5 19.5M4.5 10.5a6 6 0 1112 0 6 6 0 01-12 0z"/>
                            </svg>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-slate-500">Kategori:</span>
                            <div class="flex flex-wrap gap-2">
                                @php
                                    $categories = ['Semua', 'Zakat', 'Infak', 'Sedekah', 'Kemanusiaan'];
                                @endphp
                                @foreach ($categories as $category)
                                    <button class="rounded-full border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-700 hover:border-teal-300 hover:text-teal-700 transition" data-program-filter="{{ strtolower($category) }}">{{ $category }}</button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div id="program-list" class="mt-6 grid gap-6 md:grid-cols-2 xl:grid-cols-3" data-program-list>
                    @foreach ($programs as $program)
                        <article class="group flex flex-col overflow-hidden rounded-2xl bg-white border border-slate-100 shadow-sm hover:-translate-y-1 hover:shadow-xl transition" data-program-card data-program-text="{{ strtolower($program['title'].' '.$program['category'].' '.$program['location'].' '.$program['excerpt']) }}" data-program-category="{{ strtolower($program['category']) }}">
                            <div class="relative h-44 overflow-hidden">
                                <img src="{{ $program['image'] }}" alt="{{ $program['title'] }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent"></div>
                                <span class="absolute top-3 left-3 rounded-full bg-white/90 px-3 py-1 text-xs font-semibold text-slate-900">{{ $program['category'] }}</span>
                            </div>
                            <div class="flex-1 p-5 space-y-3">
                                <div class="flex items-center justify-between text-xs text-slate-500">
                                    <span>{{ $program['location'] }}</span>
                                    <span>Target {{ $program['target'] }}</span>
                                </div>
                                <h3 class="text-lg font-semibold text-slate-900">{{ $program['title'] }}</h3>
                                <p class="text-sm text-slate-600">{{ $program['excerpt'] }}</p>
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between text-xs text-slate-500">
                                        <span>Progress</span>
                                        <span>{{ intval($program['progress'] * 100) }}%</span>
                                    </div>
                                    <div class="h-2 rounded-full bg-slate-100 overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-emerald-400 to-teal-500" style="width: {{ $program['progress'] * 100 }}%"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-5 pt-0 flex items-center gap-3">
                                <a href="#" class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-emerald-400 to-teal-500 px-4 py-2 text-sm font-semibold text-slate-900 shadow-md hover:shadow-lg transition">
                                    Donasi Sekarang
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M13 6l6 6-6 6"/>
                                    </svg>
                                </a>
                                <a href="#" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:border-teal-300 hover:text-teal-700 transition">
                                    Detail
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
                <div class="hidden mt-10 text-center text-slate-500" data-program-empty>
                    Tidak ada program yang cocok dengan pencarian.
                </div>
            </div>
        </section>
    </main>
</div>

<nav class="fixed bottom-0 left-0 right-0 z-40 border-t border-slate-200 bg-white/95 backdrop-blur shadow-[0_-8px_30px_rgba(15,23,42,0.08)] md:hidden">
    <div class="mx-auto max-w-3xl px-4">
        <div class="grid grid-cols-4 py-3 text-xs font-semibold text-slate-600">
            @foreach ($navLinks as $link)
                <a href="{{ $link['href'] }}" class="flex flex-col items-center gap-1 rounded-lg px-2 py-1 hover:text-teal-600 transition">
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
</body>
</html>
