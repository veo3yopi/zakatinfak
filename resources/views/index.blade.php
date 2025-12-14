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
        ['name' => 'Kesehatan', 'description' => 'Layanan kesehatan, gizi, dan fasilitas medis.', 'color' => 'from-rose-500 to-red-600', 'icon' => 'heart'],
        ['name' => 'Kemanusiaan', 'description' => 'Respon bencana, logistik, dan bantuan darurat.', 'color' => 'from-blue-500 to-sky-600', 'icon' => 'hand'],
    ];
    $colorPalette = ['from-emerald-500 to-teal-600', 'from-amber-500 to-orange-600', 'from-rose-500 to-red-600', 'from-blue-500 to-sky-600', 'from-purple-500 to-indigo-600'];
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
                <div class="flex items-center gap-3">
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
                    <a href="{{ $settings?->hero_cta_url ?? url('/programs#donasi') }}" class="hidden sm:inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-teal-500 to-emerald-400 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-teal-500/20 hover:shadow-teal-500/30 transition">
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
        <section id="home" class="relative mt-6 overflow-hidden rounded-3xl bg-brand-maroon text-white shadow-2xl" data-carousel="hero">
            <div data-hero-bg class="absolute inset-0 bg-cover bg-center opacity-100" style="background-image:url('{{ $heroSlides[0]['image'] ?? 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=1600&q=80' }}'); opacity:1;"></div>
            <div class="relative p-8 sm:p-12 flex flex-col gap-6">
                @foreach ($heroSlides as $index => $slide)
                    <div class="{{ $index === 0 ? 'block' : 'hidden' }}" data-slide data-image="{{ $slide['image'] }}">
                        <div class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-wide" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.5);">
                            <span class="h-2 w-2 rounded-full bg-brand-accent"></span> {{ $slide['tag'] }}
                        </div>
                        <h1 class="mt-4 text-3xl sm:text-4xl lg:text-5xl font-bold leading-tight" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.5);">
                            {{ $slide['title'] }}
                        </h1>
                        <p class="text-lg text-slate-100/90 max-w-2xl" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.5);">{{ $slide['subtitle'] }}</p>
                        <div class="flex flex-wrap items-center gap-3 mt-4">
                            <a href="{{ $slide['url'] ?? url('/programs#donasi') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-emerald-400 to-teal-500 px-6 py-3 text-base font-semibold text-slate-900 shadow-lg shadow-emerald-500/30 hover:translate-y-[-2px] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-300 transition">
                                {{ $slide['cta'] }}
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M13 6l6 6-6 6"/>
                                </svg>
                            </a>
                            <a href="{{ $slide['url'] ?? url('/programs#donasi') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/25 px-4 py-3 text-base font-semibold hover:bg-white/10 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-300 transition">
                                Lihat Program
                            </a>
                        </div>
                    </div>
                @endforeach
                <div class="mt-6 flex items-center gap-3">
                    <button type="button" class="flex h-11 w-11 items-center justify-center rounded-full border border-white/20 bg-white/5 hover:bg-white/10 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-300 transition" data-prev>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                        </svg>
                    </button>
                    <button type="button" class="flex h-11 w-11 items-center justify-center rounded-full border border-white/20 bg-white/5 hover:bg-white/10 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-300 transition" data-next>
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

        <section class="mt-16">
            <div class="rounded-3xl bg-white shadow-lg shadow-slate-200/60 p-8 sm:p-10 relative overflow-hidden">
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

                    <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
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
                            <article class="group flex flex-col overflow-hidden rounded-2xl bg-white border border-slate-100 shadow-sm hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                                <div class="relative h-48 overflow-hidden">
                                    <img src="{{ $image }}" alt="{{ $title }}" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-slate-900/10 to-transparent"></div>
                                    <span class="absolute top-3 left-3 rounded-full bg-white/90 px-3 py-1 text-xs font-semibold text-slate-900 backdrop-blur-sm">{{ $categoryName }}</span>
                                </div>
                                <div class="flex-1 p-5 space-y-4">
                                    <h3 class="text-lg font-semibold text-slate-900 leading-snug line-clamp-2">{{ $title }}</h3>
                                    <p class="text-sm text-slate-600 line-clamp-3">{{ $summary }}</p>
                                    @if($target)
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between text-xs text-slate-500">
                                            <span class="font-semibold text-slate-800">Terkumpul</span>
                                            <span class="font-semibold text-teal-600">{{ $progress }}%</span>
                                        </div>
                                        <div class="h-2 rounded-full bg-slate-100 overflow-hidden">
                                            <div class="h-full bg-gradient-to-r from-emerald-400 to-teal-500" style="width: {{ $progress }}%"></div>
                                        </div>
                                        <div class="flex items-center justify-between text-xs text-slate-500">
                                            <span class="font-medium">Rp{{ number_format($collected, 0, ',', '.') }}</span>
                                            <span class="text-right">dari Rp{{ number_format($target, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                <div class="px-5 pb-5 pt-2 flex items-center">
                                    <a href="{{ $url }}" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-emerald-400 to-teal-500 px-4 py-2.5 text-sm font-semibold text-slate-900 shadow-md hover:shadow-lg hover:opacity-90 transition-all">
                                        Donasi Sekarang
                                    </a>
                                </div>
                            </article>
                        @empty
                            <div class="text-center text-slate-500 md:col-span-3 py-10">
                                Belum ada program unggulan yang tersedia saat ini.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>

        <section class="mt-14">
            <div class="rounded-3xl bg-white shadow-lg shadow-slate-200/60 p-8 sm:p-10 relative overflow-hidden">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_80%_0%,rgba(16,185,129,0.08),transparent_30%),radial-gradient(circle_at_0%_40%,rgba(59,130,246,0.08),transparent_30%)]"></div>
                <div class="relative">
                    <div class="text-center space-y-3">
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-teal-600">Butuh Bantuan?</p>
                        <h2 class="text-3xl font-semibold text-slate-900">Masih Bingung Untuk Berzakat?</h2>
                        <p class="text-slate-600">Untuk membantu kamu, pilih tombol di bawah ini.</p>
                    </div>
                    <div class="mt-8 grid gap-5 sm:grid-cols-3">
                        @foreach ($serviceCards as $card)
                            <a href="{{ $card['href'] }}" class="group relative overflow-hidden rounded-2xl border border-slate-100 bg-white/70 p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
                                <div class="absolute inset-0 bg-gradient-to-br from-white to-slate-50 opacity-0 group-hover:opacity-100 transition"></div>
                                <div class="relative flex flex-col gap-4">
                                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-400 to-teal-500 text-white shadow-lg shadow-teal-500/30">
                                        @if ($card['icon'] === 'chat')
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 8h10M7 12h6m6 4.5v-9A2.5 2.5 0 0016.5 5h-9A2.5 2.5 0 005 7.5v9l2.4-1.6a2 2 0 011.1-.35H16a2 2 0 011.6.8L19 18z"/>
                                            </svg>
                                        @elseif ($card['icon'] === 'calculator')
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <rect x="4" y="3" width="16" height="18" rx="2" ry="2" stroke-width="1.5"></rect>
                                                <path stroke-width="1.5" d="M8 7h8M8 11h8M8 15h1.5M12 15h1.5M15 15h1M8 18h1.5M12 18h1.5M15 18h1"></path>
                                            </svg>
                                        @else
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <rect x="6" y="3" width="12" height="18" rx="2" ry="2" stroke-width="1.5"></rect>
                                                <path stroke-width="1.5" d="M10 18h4"></path>
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="space-y-2">
                                        <h3 class="text-lg font-semibold text-slate-900">{{ $card['title'] }}</h3>
                                        <p class="text-sm text-slate-600">{{ $card['desc'] }}</p>
                                    </div>
                                    <div class="text-teal-600 font-semibold inline-flex items-center gap-2">
                                        Selengkapnya
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M13 6l6 6-6 6"/>
                                        </svg>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                    <div class="mt-8 flex justify-center">
                        <a href="{{ url('/programs') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-emerald-400 to-teal-500 px-5 py-3 text-sm font-semibold text-slate-900 shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/35 transition">
                            Lihat Semua Program
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M13 6l6 6-6 6"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <section id="program" class="mt-16">
            <div class="text-center space-y-3">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-teal-600">Program Utama</p>
                <h2 class="text-3xl font-semibold text-slate-900">Pilar Program</h2>
                <p class="text-slate-600">Dukung program prioritas dengan dampak nyata.</p>
            </div>
            <div class="relative mt-8">
                <div class="flex items-center gap-3">
                    <button class="hidden md:inline-flex h-11 w-11 items-center justify-center rounded-full border border-slate-200 bg-white shadow-sm hover:border-teal-200 hover:text-teal-600 transition" data-scroll-prev data-scroll-step="320" data-scroll-target="#program-scroll">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                        </svg>
                    </button>
                    <div id="program-scroll" class="flex gap-4 overflow-x-auto no-scrollbar scroll-smooth snap-x snap-mandatory pb-3 cursor-grab active:cursor-grabbing" data-scroll-container data-drag-scroll>
                        @foreach ($pillars as $program)
                            <div class="snap-center min-w-[240px] flex-1 rounded-2xl bg-gradient-to-br {{ $program['color'] }} p-6 text-white shadow-lg shadow-slate-900/15">
                                <div class="h-12 w-12 rounded-xl bg-white/20 flex items-center justify-center mb-4">
                                    @if ($program['icon'] === 'graduation')
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4l8 4-8 4-8-4 8-4zm0 8v6m-4 0h8"/>
                                        </svg>
                                    @elseif ($program['icon'] === 'chart')
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 19h16M7 16V9m5 7v-6m5 6V7"/>
                                        </svg>
                                    @elseif ($program['icon'] === 'heart')
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 21s-6.75-4.35-8.25-9A5.25 5.25 0 0112 5.25 5.25 5.25 0 0120.25 12c-1.5 4.65-8.25 9-8.25 9z"/>
                                        </svg>
                                    @else
                                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 11l3 3 7-7m-2 8v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-6a2 2 0 012-2h5"/>
                                        </svg>
                                    @endif
                                </div>
                                <div class="text-xl font-semibold">{{ $program['name'] }}</div>
                                <p class="mt-2 text-sm text-white/80">{{ $program['description'] ?? 'Program prioritas dengan pelaporan berkala dan indikator kinerja.' }}</p>
                            </div>
                        @endforeach
                    </div>
                    <button class="hidden md:inline-flex h-11 w-11 items-center justify-center rounded-full border border-slate-200 bg-white shadow-sm hover:border-teal-200 hover:text-teal-600 transition" data-scroll-next data-scroll-step="320" data-scroll-target="#program-scroll">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                        </svg>
                    </button>
                </div>
                <div class="mt-6 flex justify-center">
                    <a href="#" class="inline-flex items-center gap-2 rounded-xl bg-slate-900 text-white px-5 py-3 text-sm font-semibold shadow-lg hover:bg-slate-800 transition">
                        Selengkapnya
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M13 6l6 6-6 6"/>
                        </svg>
                    </a>
                </div>
            </div>
        </section>

        <section id="artikel" class="mt-16">
            <div class="text-center space-y-3">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-teal-600">Artikel & Berita</p>
                <h2 class="text-3xl font-semibold text-slate-900">Kabar Terkini & Wawasan</h2>
                <p class="text-slate-600">Update kegiatan, insight zakat, infak, sedekah, dan wakaf.</p>
                <div class="mt-4">
                    <a href="{{ url('/posts') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-emerald-400 to-teal-500 px-5 py-3 text-sm font-semibold text-slate-900 shadow-md hover:shadow-lg transition">
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

        <section id="mitra" class="mt-16">
            <div class="text-center space-y-3">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-teal-600">Mitra</p>
                <h2 class="text-3xl font-semibold text-slate-900">Kolaborasi Strategis</h2>
                <p class="text-slate-600">Bersama mitra untuk menyalurkan kebaikan lebih luas.</p>
            </div>
            <div class="relative mt-8">
                <div class="flex items-center gap-3">
                    <button class="hidden md:inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white shadow-sm hover:border-teal-200 hover:text-teal-600 transition" data-scroll-prev data-scroll-step="260" data-scroll-target="#partner-scroll">
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
                            <div class="snap-center min-w-[220px] flex-1 rounded-2xl border border-slate-100 bg-white px-6 py-5 shadow-sm">
                                <div class="flex items-center justify-center h-12">
                                    <img src="{{ $logo ?? 'https://dummyimage.com/140x50/0f172a/ffffff&text=Partner' }}" alt="{{ $partner->name }}" class="max-h-10 w-auto object-contain" title="{{ $partner->name }}">
                                </div>
                                <div class="mt-3 text-center">
                                    <p class="text-sm font-semibold text-slate-900">{{ $partner->name }}</p>
                                    @if($partner->website)
                                        <a href="{{ $partner->website }}" class="text-xs text-teal-600 hover:underline" target="_blank" rel="noopener">Kunjungi situs</a>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-slate-500">Belum ada mitra.</div>
                        @endforelse
                    </div>
                    <button class="hidden md:inline-flex h-10 w-10 items-center justify-center rounded-full border border-slate-200 bg-white shadow-sm hover:border-teal-200 hover:text-teal-600 transition" data-scroll-next data-scroll-step="260" data-scroll-target="#partner-scroll">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                        </svg>
                    </button>
                </div>
                <div class="mt-6 flex justify-center">
                    <a href="#" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-emerald-400 to-teal-500 px-5 py-3 text-sm font-semibold text-slate-900 shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/35 transition">
                        Ajukan Kemitraan
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M13 6l6 6-6 6"/>
                        </svg>
                    </a>
                </div>
            </div>
        </section>

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

    <a href="{{ $settings?->hero_cta_url ?? url('/programs#donasi') }}" class="sm:hidden fixed bottom-5 right-5 z-40 inline-flex items-center gap-2 rounded-full bg-gradient-to-r from-teal-500 to-emerald-400 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-teal-500/30 hover:shadow-teal-500/40 transition">
        Donasi
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M13 6l6 6-6 6"/>
        </svg>
    </a>

    <nav class="fixed bottom-0 left-0 right-0 z-40 border-t border-slate-200 bg-white/95 backdrop-blur shadow-[0_-8px_30px_rgba(15,23,42,0.08)] md:hidden">
        <div class="mx-auto max-w-3xl px-4">
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

    <footer id="tentang" class="mt-16 bg-slate-900 text-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 grid gap-10 lg:grid-cols-4">
            <div class="space-y-4">
                <div class="flex items-center gap-2">
                    <div class="h-11 w-11 rounded-2xl bg-gradient-to-br from-teal-500 to-emerald-400 flex items-center justify-center text-white font-semibold shadow-lg">Z</div>
                    <div>
                        <div class="text-lg font-semibold text-white">Zakat Impact</div>
                        <div class="text-sm text-slate-400">Lembaga zakat & infak amanah.</div>
                    </div>
                </div>
                <p class="text-sm text-slate-400 leading-relaxed">
                    Berkhidmat dalam pemberdayaan masyarakat melalui zakat, infak, sedekah, dan wakaf dengan pelaporan transparan serta audit berkala.
                </p>
            </div>
            <div class="space-y-3">
                <h4 class="text-lg font-semibold text-white">Alamat</h4>
                <p class="text-sm text-slate-400">Jl. Amanah No. 5, Jakarta Pusat 10430</p>
                <p class="text-sm text-slate-400">info@zakatimpact.org</p>
                <p class="text-sm text-slate-400">+62 812-3456-7890</p>
            </div>
            <div class="space-y-3">
                <h4 class="text-lg font-semibold text-white">Selengkapnya</h4>
                <ul class="space-y-2 text-sm text-slate-400">
                    <li><a href="#" class="hover:text-white transition">FAQ</a></li>
                    <li><a href="#" class="hover:text-white transition">Laporan & Publikasi</a></li>
                    <li><a href="#" class="hover:text-white transition">Kebijakan Privasi</a></li>
                </ul>
            </div>
            <div class="space-y-3">
                <h4 class="text-lg font-semibold text-white">Media Sosial</h4>
                <div class="flex gap-3">
                    @php
                        $socials = ['Instagram', 'Facebook', 'YouTube', 'LinkedIn'];
                    @endphp
                    @foreach ($socials as $social)
                        <a href="#" class="flex h-11 w-11 items-center justify-center rounded-xl bg-white/10 text-white hover:bg-white/20 transition" aria-label="{{ $social }}">
                            <span class="text-sm font-semibold">{{ substr($social, 0, 1) }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="border-t border-white/10 py-5 text-center text-sm text-slate-500">
            Copyright © {{ now()->year }} Zakat Impact. Semua hak dilindungi.
        </div>
    </footer>
</div>
</body>
</html>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const carousel = document.querySelector('[data-carousel="hero"]');
    if (!carousel) return;

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
});
</script>
