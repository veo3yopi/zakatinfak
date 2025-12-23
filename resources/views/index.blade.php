<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php
        $siteTitle = $settings->site_title ?? ($settings->site_name ?? 'Zakat Impact');
        $favicon = $settings?->favicon_url;
        if ($favicon && !\Illuminate\Support\Str::startsWith($favicon, ['http://', 'https://'])) {
            $favicon = \Illuminate\Support\Facades\Storage::url($favicon);
        }
    @endphp
    <title>{{ $siteTitle }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    @if($favicon)
        <link rel="icon" type="image/png" href="{{ $favicon }}">
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: "Space Grotesk", "Inter", system-ui, sans-serif; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .category-pill {
            display: inline-flex;
            align-items: center;
            white-space: nowrap;
            border-radius: 9999px; /* rounded-full */
            padding: 0.5rem 1rem; /* px-4 py-2 */
            font-size: 0.875rem; /* text-sm */
            font-weight: 600; /* font-semibold */
            background-color: white;
            color: #334155; /* text-slate-700 */
            border: 1px solid #e2e8f0; /* border-slate-200 */
            box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05); /* shadow-sm */
            transition: all 0.2s ease-in-out;
        }
        .category-pill:hover {
            background-color: #f1f5f9; /* bg-slate-100 */
            border-color: #cbd5e1; /* border-slate-300 */
        }
        .category-pill.active {
            background-color: #992C31; /* brand-maroon */
            color: white;
            border-color: #992C31;
        }
        .category-pill.active svg {
            color: white;
        }
    </style>
</head>
<body class="bg-brand-offwhite text-brand-charcoal antialiased">
@php
    $navLinks = [
        ['label' => 'Home', 'href' => '#home'],
        ['label' => 'Program', 'href' => url('/programs')],
        ['label' => 'Artikel', 'href' => url('/posts')],
        ['label' => 'Tentang', 'href' => url('/about')],
    ];

    $settings = $settings ?? null;

    $heroSlides = $heroSlides ?? [
        [
            'title' => 'Bersihkan harta, sucikan jiwa',
            'subtitle' => 'Salurkan zakat maal dengan transparan dan tepat sasaran.',
            'cta' => 'Zakat Sekarang',
            'image' => 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=1600&q=80',
            'tag' => 'Zakat Maal',
            'url' => url('/programs'),
        ],
        [
            'title' => 'Sedekah menyambung harapan',
            'subtitle' => 'Dukung program pendidikan, kesehatan, dan ekonomi umat.',
            'cta' => 'Pilih Program',
            'image' => 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=1600&q=80',
            'tag' => 'Sedekah',
            'url' => url('/programs'),
        ],
        [
            'title' => 'Infak mudah, impact besar',
            'subtitle' => 'Infak digital dengan laporan real-time dan update rutin.',
            'cta' => 'Mulai Berbagi',
            'image' => 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1600&q=80',
            'tag' => 'Infak',
            'url' => url('/programs'),
        ],
    ];

    $serviceCards = [
        ['title' => 'Konsultasi', 'desc' => 'Sampaikan pertanyaan kamu kepada tim layanan kami.', 'icon' => 'chat', 'href' => '#'],
        ['title' => 'Kalkulator Zakat', 'desc' => 'Hitung kewajiban zakatmu secara otomatis.', 'icon' => 'calculator', 'href' => '#'],
        ['title' => 'Laman Donasi', 'desc' => 'Pilih beragam tema zakat, infak, dan sedekah.', 'icon' => 'tablet', 'href' => '#'],
    ];

    $defaultPillars = [
        ['name' => 'Pendidikan', 'description' => 'Dukungan beasiswa, sekolah, dan pelatihan.', 'color' => 'from-emerald-500 to-teal-600', 'icon' => 'graduation'],
        ['name' => 'Ekonomi', 'description' => 'Penguatan UMKM, modal usaha, dan pendampingan.', 'color' => 'from-amber-500 to-orange-600', 'icon' => 'chart'],
        ['name' => 'Kesehatan', 'description' => 'Layanan kesehatan, gizi, dan fasilitas medis.', 'color' => 'from-brand-maroon to-brand-maroonDark', 'icon' => 'heart'],
        ['name' => 'Kemanusiaan', 'description' => 'Respon bencana, logistik, dan bantuan darurat.', 'color' => 'from-blue-500 to-sky-600', 'icon' => 'hand'],
    ];
    $colorPalette = ['from-emerald-500 to-teal-600', 'from-amber-500 to-orange-600', 'from-brand-maroon to-brand-maroonDark', 'from-blue-500 to-sky-600', 'from-purple-500 to-indigo-600'];
    $iconPalette = ['graduation', 'chart', 'heart', 'hand', 'globe'];
    $pillars = isset($pillars) && $pillars->count() > 0
        ? $pillars->values()->map(function ($pillar, $index) use ($colorPalette, $iconPalette) {
            return [
                'name' => $pillar->name,
                'description' => $pillar->description ?? 'Program prioritas dengan laporan berkala.',
                'color' => $colorPalette[$index % count($colorPalette)],
                'icon' => $iconPalette[$index % count($iconPalette)],
            ];
        })->toArray()
        : $defaultPillars;

    $news = [
        [
            'title' => 'Gerakan Sedekah Subuh untuk Beasiswa Santri',
            'excerpt' => 'Program beasiswa untuk santri berprestasi melalui sedekah subuh kolektif.',
            'date' => '5 Mei 2025',
            'image' => 'https://images.unsplash.com/photo-1523580846011-d3a5bc25702b?auto=format&fit=crop&w=900&q=80',
        ],
        [
            'title' => 'Distribusi Paket Pangan Ramadhan Nasional',
            'excerpt' => 'Kolaborasi dengan relawan daerah menyalurkan paket pangan untuk dhuafa.',
            'date' => '3 Mei 2025',
            'image' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=900&q=80',
        ],
        [
            'title' => 'Pelatihan UMKM Berbasis Syariah untuk Ibu Rumah Tangga',
            'excerpt' => 'Pendampingan usaha kecil agar naik kelas melalui akses modal zakat.',
            'date' => '1 Mei 2025',
            'image' => 'https://images.unsplash.com/photo-1520607162513-77705c0f0d4a?auto=format&fit=crop&w=900&q=80',
        ],
    ];

    $posts = $posts ?? array_merge($news, [
        [
            'title' => 'Siapa yang Berhak Menerima Zakat? Panduan 8 Asnaf',
            'excerpt' => 'Mengurai kriteria mustahik dan cara memastikan distribusi tepat sasaran.',
            'date' => '5 Mei 2025',
            'image' => 'https://images.unsplash.com/photo-1516637090014-cb1ab0d08fc7?auto=format&fit=crop&w=900&q=80',
        ],
        [
            'title' => 'Perbedaan Zakat Maal, Zakat Profesi, dan Infak',
            'excerpt' => 'Kenali perhitungan, nisab, serta penyaluran masing-masing instrumen.',
            'date' => '4 Mei 2025',
            'image' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=900&q=80',
        ],
        [
            'title' => 'Digitalisasi Donasi: Transparansi dan Audit Trail',
            'excerpt' => 'Bagaimana teknologi memastikan donasi dilaporkan secara real-time.',
            'date' => '2 Mei 2025',
            'image' => 'https://images.unsplash.com/photo-1520607162513-77705c0f0d4a?auto=format&fit=crop&w=900&q=80',
        ],
    ]);
    $aboutStats = [
        ['label' => 'Penerima Manfaat', 'value' => $settings?->impact_beneficiaries ?? 12000],
        ['label' => 'Program', 'value' => $settings?->impact_programs ?? 180],
        ['label' => 'Wilayah', 'value' => $settings?->impact_regions ?? 42],
        ['label' => 'Relawan', 'value' => $settings?->impact_volunteers ?? 320],
    ];
    $impactStats = $aboutStats;
    $testimonials = [
        [
            'name' => 'Hamba Allah',
            'role' => 'Donatur',
            'quote' => 'Laporannya rapi, saya merasa tenang menitipkan zakat di sini.',
        ],
        [
            'name' => 'Siti Aisyah',
            'role' => 'Penerima manfaat pendidikan',
            'quote' => 'Beasiswa membantu saya melanjutkan sekolah. Terima kasih para donatur.',
        ],
        [
            'name' => 'Andi Pratama',
            'role' => 'Relawan',
            'quote' => 'Distribusi program terencana dan transparan, timnya profesional.',
        ],
    ];
    $transparencyItems = [
        ['title' => 'Metode Pembayaran', 'desc' => 'Transfer bank, VA, QRIS, e-wallet', 'cta' => 'Lihat panduan'],
        ['title' => 'Laporan & Audit', 'desc' => 'Akses laporan keuangan dan program', 'cta' => 'Unduh laporan'],
        ['title' => 'Kebijakan Privasi', 'desc' => 'Perlindungan data donatur & mustahik', 'cta' => 'Baca selengkapnya'],
    ];

    $partners = $partners ?? collect();

    $homeHeroShowTitle = (bool) ($settings?->program_hero_show_title ?? true);
    $homeHeroShowSummary = (bool) ($settings?->program_hero_show_summary ?? true);
    $homeHeroShowCta = (bool) ($settings?->program_hero_show_cta ?? true);
    $homeHeroShowCategories = (bool) ($settings?->program_show_categories ?? true);

@endphp

<div class="bg-gradient-to-b from-brand-cream/60 via-brand-offwhite to-white min-h-screen">
    <header class="sticky top-0 z-30 bg-white/80 backdrop-blur shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between py-4">
                <a href="{{ url('/') }}" class="flex items-center gap-2">
                    <div class="h-11 w-11 rounded-2xl overflow-hidden bg-slate-900/10 shadow-lg">
                        @php
                            $logo = $settings?->logo_url;
                            if ($logo && !\Illuminate\Support\Str::startsWith($logo, ['http://', 'https://'])) {
                                $logo = \Illuminate\Support\Facades\Storage::url($logo);
                            }
                        @endphp
                        <img src="{{ $logo ?? 'https://dummyimage.com/80x80/14b8a6/ffffff&text=Z' }}" alt="Brand logo" class="h-full w-full object-cover">
                    </div>
                    <div>
                        <div class="text-lg font-semibold text-slate-900">{{ $settings->site_name ?? 'Zakat Impact' }}</div>
                        <div class="text-sm text-slate-500">{{ $settings->site_tagline ?? 'Transparan • Amanah • Cepat' }}</div>
                    </div>
                </a>
                <nav class="hidden md:flex items-center gap-6 text-sm font-medium text-slate-700">
                    @foreach ($navLinks as $link)
                        <a href="{{ $link['href'] }}" class="hover:text-teal-600 transition" data-nav-link>{{ $link['label'] }}</a>
                    @endforeach
                </nav>
                <div class="flex items-center justify-center gap-3">
                    @if(auth()->check())
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:border-teal-200 hover:text-teal-700 transition">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:border-teal-200 hover:text-teal-700 transition">
                            Masuk
                        </a>
                    @endif
                    <form action="{{ route('search') }}" method="GET" class="hidden lg:flex items-center">
                        <label class="sr-only" for="search-hero">Cari</label>
                        <div class="relative">
                            <input id="search-hero" type="text" name="q" value="{{ request('q') }}" placeholder="Cari program atau artikel" class="w-56 rounded-xl border border-slate-200 pl-10 pr-3 py-2 text-sm focus:border-teal-300 focus:outline-none focus:ring-2 focus:ring-emerald-200" />
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15z"/></svg>
                            </span>
                        </div>
                    </form>
                    <a href="{{ $settings?->hero_cta_url ?? url('/programs#donasi') }}" class="hidden sm:inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-brand-maroon to-brand-maroonDark px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-brand-maroon/20 hover:shadow-brand-maroon/30 transition">
                        {{ $settings?->hero_cta_label ?? 'Donasi Sekarang' }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M13 6l6 6-6 6"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-28 lg:pb-0">
        <section id="home" class="relative mt-6 overflow-hidden rounded-3xl bg-brand-maroon text-white shadow-2xl h-[16.25rem] sm:h-[18rem] lg:h-[20rem]" data-carousel="hero">
            <div data-hero-bg class="absolute inset-0 bg-cover bg-center opacity-100" style="background-image:url('{{ $heroSlides[0]['image'] ?? 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=1600&q=80' }}'); opacity:1;"></div>
            <div class="relative h-full p-8 sm:p-12 flex flex-col gap-5">
                @foreach ($heroSlides as $index => $slide)
                    <div class="{{ $index === 0 ? 'block' : 'hidden' }}" data-slide data-image="{{ $slide['image'] }}">
                        @if($homeHeroShowCategories)
                            <div class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.5);">
                                <span class="h-2 w-2 rounded-full bg-brand-accent"></span> {{ $slide['tag'] }}
                            </div>
                        @endif
                        @if($homeHeroShowTitle)
                            <h1 class="mt-4 text-3xl sm:text-4xl lg:text-5xl font-bold leading-tight" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.5);">
                                {{ $slide['title'] }}
                            </h1>
                        @endif
                        @if($homeHeroShowSummary)
                            <p class="text-lg text-slate-100/90 max-w-2xl" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.5);">{{ $slide['subtitle'] }}</p>
                        @endif
                        @if($homeHeroShowCta)
                            <div class="flex flex-wrap items-center gap-3 mt-4">
                                <a href="{{ $slide['url'] ?? url('/programs#donasi') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-brand-maroon to-brand-maroonDark px-6 py-3 text-base font-semibold text-white shadow-lg shadow-brand-maroon/30 hover:translate-y-[-2px] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-maroon transition">
                                    {{ $slide['cta'] }}
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M13 6l6 6-6 6"/>
                                    </svg>
                                </a>
                            </div>
                        @endif
                    </div>
                @endforeach
                <div class="mt-6 flex items-center gap-3">
                    <button type="button" class="flex h-11 w-11 items-center justify-center rounded-full border border-white/20 bg-white/5 hover:bg-white/10 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-maroon transition" data-prev>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                        </svg>
                    </button>
                    <button type="button" class="flex h-11 w-11 items-center justify-center rounded-full border border-white/20 bg-white/5 hover:bg-white/10 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-maroon transition" data-next>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                        </svg>
                    </button>
                    <div class="flex items-center gap-2" data-dots>
                        @foreach ($heroSlides as $index => $slide)
                            <span class="h-2.5 w-2.5 rounded-full bg-white/30"></span>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <section id="program-categories" class="mt-16">
            <div class="text-center space-y-3">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-teal-600">Telusuri Program</p>
                <h2 class="text-3xl font-semibold text-slate-900">Kategori Program</h2>
            </div>
            <div class="relative mt-8">
                <div id="category-scroll" class="flex items-center gap-3 overflow-x-auto no-scrollbar scroll-smooth snap-x snap-mandatory pb-3 -mx-4 px-4 sm:px-0 sm:mx-0" data-scroll-container data-drag-scroll>
                    {{-- Tombol untuk 'Semua Kategori' --}}
                    <a href="{{ url('/programs') }}" class="category-pill flex-shrink-0 active">
                        <svg class="w-5 h-5 mr-2 text-slate-600 transition group-hover:text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.5 6.75h15m-15 4.5h15m-15 4.5H12"/>
                        </svg>
                        <span>Semua</span>
                    </a>
                    @foreach ($pillars as $category)
                        <a href="{{ url('/programs?category=' . $category['name']) }}" class="category-pill flex-shrink-0">
                            @if ($category['icon'] === 'graduation')
                                <svg class="w-5 h-5 mr-2 text-slate-600 group-hover:text-teal-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4l8 4-8 4-8-4 8-4zm0 8v6m-4 0h8"/>
                                </svg>
                            @elseif ($category['icon'] === 'chart')
                                <svg class="w-5 h-5 mr-2 text-slate-600 group-hover:text-teal-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 19h16M7 16V9m5 7v-6m5 6V7"/>
                                </svg>
                            @elseif ($category['icon'] === 'heart')
                                <svg class="w-5 h-5 mr-2 text-slate-600 group-hover:text-teal-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 21s-6.75-4.35-8.25-9A5.25 5.25 0 0112 5.25 5.25 5.25 0 0120.25 12c-1.5 4.65-8.25 9-8.25 9z"/>
                                </svg>
                            @else
                                <svg class="w-5 h-5 mr-2 text-slate-600 group-hover:text-teal-600 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 11l3 3 7-7m-2 8v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-6a2 2 0 012-2h5"/>
                                </svg>
                            @endif
                            <span>{{ $category['name'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="mt-16">
            <div class="rounded-3xl bg-white shadow-lg shadow-slate-200/60 p-4 sm:p-6 md:p-8 relative overflow-hidden">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,rgba(153,44,49,0.06),transparent_32%),radial-gradient(circle_at_80%_0%,rgba(241,232,184,0.25),transparent_45%)]"></div>
                <div class="relative space-y-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="space-y-2">
                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-brand-maroon">Program Unggulan</p>
                            <h2 class="text-3xl font-semibold text-brand-charcoal">Pilihan program prioritas untuk kamu dukung</h2>
                            <p class="text-slate-600">Fokus pada program berdampak besar dengan laporan transparan.</p>
                        </div>
                        <a href="{{ url('/programs') }}" class="btn-ghost">Lihat semua program</a>
                    </div>

                    {{-- Grid 2 kolom di mobile, 3 di desktop --}}
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        @forelse($programs as $program)
                            @php
                                $image = $program->getFirstMediaUrl('cover') ?: 'https://images.unsplash.com/photo-1523580846011-d3a5bc25702b?auto=format&fit=crop&w=900&q=80';
                                $title = $program->title;
                                $summary = $program->summary;
                                $target = $program->target_amount;
                                $collected = $program->collected_amount;
                                $progress = $target > 0 ? round(($collected / $target) * 100) : 0;
                                $url = route('programs.show', $program->slug);
                                $categoryName = $program->category?->name ?? 'Program';
                            @endphp
                            <article class="group flex flex-col overflow-hidden rounded-xl bg-white border border-slate-100 shadow-sm hover:-translate-y-1 hover:shadow-lg transition-all duration-300">
                                <div class="relative h-28 sm:h-36 overflow-hidden">
                                    <img src="{{ $image }}" alt="{{ $title }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/50 via-transparent"></div>
                                    <span class="absolute top-2 left-2 rounded-full bg-black/40 px-2 py-1 text-xs font-semibold text-white">{{ $categoryName }}</span>
                                </div>
                                <div class="flex-1 p-3 space-y-2">
                                    <h3 class="text-sm sm:text-base font-semibold text-slate-800 leading-snug line-clamp-2 group-hover:text-teal-600">{{ $title }}</h3>
                                    @if($target)
                                    <div class="space-y-1">
                                        <div class="flex items-center justify-between text-xs text-slate-500">
                                            <span class="font-semibold text-slate-700">Terkumpul</span>
                                            <span class="font-semibold text-teal-600">{{ $progress }}%</span>
                                        </div>
                                        <div class="h-1.5 rounded-full bg-slate-200 overflow-hidden">
                                            <div class="h-full bg-gradient-to-r from-teal-400 to-emerald-500" style="width: {{ $progress }}%"></div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="px-3 pb-3 pt-1 flex items-center">
                                    <a href="{{ $url }}" class="w-full inline-flex items-center justify-center rounded-xl bg-brand-maroon px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-brand-maroonDark transition-colors">
                                        Donasi Sekarang
                                    </a>
                                </div>
                            </article>
                        @empty
                            <div class="text-center text-slate-500 col-span-2 md:col-span-3 py-10">
                                Belum ada program unggulan yang tersedia saat ini.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>

        <section id="artikel" class="mt-16">
            <div class="text-center space-y-3">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-teal-600">Artikel & Berita</p>
                <h2 class="text-3xl font-semibold text-slate-900">Kabar Terkini & Wawasan</h2>
                <p class="text-slate-600">Update kegiatan, insight zakat, infak, sedekah, dan wakaf.</p>
                <div class="mt-4">
                    <a href="{{ url('/posts') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-brand-maroon to-brand-maroonDark px-5 py-3 text-sm font-semibold text-white shadow-md hover:shadow-lg transition">
                        Lihat semua artikel
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M13 6l6 6-6 6"/>
                        </svg>
                    </a>
                </div>
            </div>
            <div class="mt-8 grid gap-6 lg:grid-cols-3">
                @foreach ($posts as $item)
                    <article class="flex flex-col overflow-hidden rounded-2xl bg-white shadow-lg shadow-slate-200/60 transition hover:-translate-y-1 hover:shadow-xl">
                        <div class="relative h-52 overflow-hidden">
                            <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" class="h-full w-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent"></div>
                            <span class="absolute bottom-3 left-3 rounded-full bg-white/90 px-3 py-1 text-xs font-semibold text-slate-900">{{ $item['date'] }}</span>
                        </div>
                        <div class="flex-1 p-6 space-y-3">
                            <h3 class="text-xl font-semibold text-slate-900">{{ $item['title'] }}</h3>
                            <p class="text-sm text-slate-600">{{ $item['excerpt'] }}</p>
                        </div>
                        <div class="px-6 pb-5">
                            <a href="{{ $item['url'] ?? '#' }}" class="inline-flex items-center gap-2 text-teal-600 font-semibold hover:text-teal-700">
                                Selengkapnya
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M13 6l6 6-6 6"/>
                                </svg>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="mt-16">
            <div class="text-center space-y-3">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-teal-600">Testimoni</p>
                <h2 class="text-3xl font-semibold text-slate-900">Kata Mereka</h2>
                <p class="text-slate-600">Apa kata donatur, penerima manfaat, dan relawan.</p>
            </div>
            <div class="mt-8 grid gap-6 lg:grid-cols-3">
                @foreach ($testimonials as $item)
                    <div class="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm">
                        <div class="text-sm text-slate-500">{{ $item['role'] }}</div>
                        <div class="text-lg font-semibold text-slate-900">{{ $item['name'] }}</div>
                        <p class="mt-3 text-slate-600 leading-relaxed">“{{ $item['quote'] }}”</p>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- <section id="mitra" class="mt-16">
            <div class="text-center space-y-3">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-teal-600">Mitra</p>
                <h2 class="text-3xl font-semibold text-slate-900">Kolaborasi Strategis</h2>
                <p class="text-slate-600">Bersama mitra untuk menyalurkan kebaikan lebih luas.</p>
            </div>
            <div class="relative mt-8">
                <div class="flex items-center gap-3">
                    <button class="hidden md:inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white shadow-sm hover:border-teal-200 hover:text-teal-600 transition" data-scroll-prev data-scroll-step="260" data-scroll-target="#partner-scroll" data-partner-prev>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                        </svg>
                    </button>
                    <div id="partner-scroll" class="flex gap-4 overflow-x-auto scroll-smooth snap-x snap-mandatory pb-3" data-scroll-container>
                        @forelse ($partners as $partner)
                            @php
                                $logo = $partner->logo_path;
                                if ($logo && !\Illuminate\Support\Str::startsWith($logo, ['http://', 'https://'])) {
                                    $logo = \Illuminate\Support\Facades\Storage::url($logo);
                                }
                            @endphp
                            <div class="snap-center min-w-[180px] flex flex-col items-center text-center gap-2 px-2 py-3">
                                <div class="flex items-center justify-center h-14 w-40">
                                    <img src="{{ $logo ?? 'https://dummyimage.com/140x50/0f172a/ffffff&text=Partner' }}" alt="{{ $partner->name }}" class="h-10 w-36 object-contain" title="{{ $partner->name }}">
                                </div>
                                <p class="text-sm font-semibold text-slate-900">{{ $partner->name }}</p>
                                @if($partner->website)
                                    <a href="{{ $partner->website }}" class="text-xs text-teal-600 hover:underline" target="_blank" rel="noopener">Kunjungi situs</a>
                                @endif
                            </div>
                        @empty
                            <div class="text-center text-slate-500">Belum ada mitra.</div>
                        @endforelse
                    </div>
                    <button class="hidden md:inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white shadow-sm hover:border-teal-200 hover:text-teal-600 transition" data-scroll-next data-scroll-step="260" data-scroll-target="#partner-scroll" data-partner-next>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                        </svg>
                    </button>
                </div>
                <div class="mt-6 flex justify-center">
                    <a href="#" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-brand-maroon to-brand-maroonDark px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-brand-maroon/25 hover:shadow-brand-maroon/35 transition">
                        Ajukan Kemitraan
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M13 6l6 6-6 6"/>
                        </svg>
                    </a>
                </div>
            </div>
        </section> --}}

        <section class="mt-16">
            <div class="text-center space-y-3">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-teal-600">Transparansi</p>
                <h2 class="text-3xl font-semibold text-slate-900">Pembayaran & Laporan</h2>
                <p class="text-slate-600">Pahami alur donasi, laporan, dan kebijakan privasi.</p>
            </div>
            <div class="mt-8 grid gap-4 md:grid-cols-3">
                @foreach ($transparencyItems as $item)
                    <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                        <div class="text-lg font-semibold text-slate-900">{{ $item['title'] }}</div>
                        <p class="mt-2 text-sm text-slate-600">{{ $item['desc'] }}</p>
                        <a href="#" class="mt-4 inline-flex items-center gap-2 text-teal-600 text-sm font-semibold">
                            {{ $item['cta'] }}
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </a>
                    </div>
                @endforeach
            </div>
        </section>

    </main>

    @if($homeHeroShowCta)
        <a href="{{ $settings?->hero_cta_url ?? url('/programs#donasi') }}" class="sm:hidden fixed bottom-5 right-5 z-40 inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-brand-maroon to-brand-maroonDark px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-brand-maroon/30 hover:shadow-teal-500/40 transition">
            Donasi
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M13 6l6 6-6 6"/>
            </svg>
        </a>
    @endif

    <nav class="fixed bottom-0 left-0 right-0 z-40 border-t border-slate-200 bg-white/95 backdrop-blur shadow-[0_-8px_30px_rgba(15,23,42,0.08)] md:hidden">
        <div class="mx-auto max-w-3xl px-4">
            <div class="grid grid-cols-4 py-3 text-xs font-semibold text-slate-600">
                @foreach ($navLinks as $link)
                    <a href="{{ $link['href'] }}"
                       class="flex flex-col items-center gap-1 rounded-lg px-2 py-1 hover:text-teal-600 transition"
                       data-nav-link>
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
    <x-site-footer :settings="$settings" />
</div>
</div>
</body>
</html>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const carousel = document.querySelector('[data-carousel="hero"]');

    if (carousel) {
        const slides = carousel.querySelectorAll('[data-slide]');
        const prevBtn = carousel.querySelector('[data-prev]');
        const nextBtn = carousel.querySelector('[data-next]');
        const dotsContainer = carousel.querySelector('[data-dots]');
        const heroBg = carousel.querySelector('[data-hero-bg]');
        let currentSlide = 0;
        let interval;

        const showSlide = (index) => {
            slides.forEach((slide, i) => {
                slide.classList.toggle('hidden', i !== index);
            });
            if (dotsContainer) {
                Array.from(dotsContainer.children).forEach((dot, i) => {
                    dot.classList.toggle('bg-white', i === index);
                    dot.classList.toggle('bg-white/30', i !== index);
                });
            }
            if (heroBg) {
                const imageUrl = slides[index].getAttribute('data-image');
                if (imageUrl) {
                    heroBg.style.backgroundImage = `url('${imageUrl}')`;
                }
            }
            currentSlide = index;
        };

        const nextSlide = () => {
            showSlide((currentSlide + 1) % slides.length);
        };

        const prevSlide = () => {
            showSlide((currentSlide - 1 + slides.length) % slides.length);
        };

        const startCarousel = () => {
            interval = setInterval(nextSlide, 5000); // Change slide every 5 seconds
        };

        const stopCarousel = () => {
            clearInterval(interval);
        };

        if (slides.length > 1) {
            prevBtn?.addEventListener('click', () => {
                stopCarousel();
                prevSlide();
                startCarousel();
            });

            nextBtn?.addEventListener('click', () => {
                stopCarousel();
                nextSlide();
                startCarousel();
            });

            if (dotsContainer) {
                Array.from(dotsContainer.children).forEach((dot, i) => {
                    dot.addEventListener('click', () => {
                        stopCarousel();
                        showSlide(i);
                        startCarousel();
                    });
                });
            }

            carousel.addEventListener('mouseenter', stopCarousel);
            carousel.addEventListener('mouseleave', startCarousel);

            startCarousel();
            showSlide(0);
        }
    }

    const partnerScroll = document.querySelector('#partner-scroll');
    if (partnerScroll) {
        const partnerPrev = document.querySelector('[data-partner-prev]');
        const partnerNext = document.querySelector('[data-partner-next]');
        const togglePartnerControls = () => {
            const hasOverflow = partnerScroll.scrollWidth - partnerScroll.clientWidth > 2;
            [partnerPrev, partnerNext].forEach((btn) => {
                if (!btn) return;
                btn.style.display = hasOverflow ? '' : 'none';
            });
        };
        togglePartnerControls();
        window.addEventListener('resize', togglePartnerControls);
        window.addEventListener('load', togglePartnerControls);
    }
});
</script>
