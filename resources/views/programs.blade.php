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

    $categories = $categories ?? collect();
    $programs = $programs ?? collect();
    $filters = $filters ?? ['category' => '', 'q' => ''];
    $fallbackCategories = ['Zakat', 'Infak', 'Sedekah', 'Kemanusiaan'];
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
                <form method="GET" action="{{ route('programs') }}" class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm font-semibold text-teal-600">Cari Program</p>
                        <h2 class="text-2xl font-semibold text-slate-900">Temukan yang paling relevan</h2>
                    </div>
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:gap-4 w-full sm:w-auto">
                        <div class="relative w-full sm:w-72">
                            <input
                                type="search"
                                name="q"
                                value="{{ $filters['q'] }}"
                                placeholder="Cari judul, kategori, lokasi..."
                                class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 pr-10 text-sm text-slate-800 shadow-sm focus:border-teal-300 focus:outline-none focus:ring-2 focus:ring-emerald-200"
                            >
                            <svg class="absolute right-3 top-3.5 h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.75 15.75L19.5 19.5M4.5 10.5a6 6 0 1112 0 6 6 0 01-12 0z"/>
                            </svg>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-slate-500">Kategori:</span>
                            <div class="flex flex-wrap gap-2">
                                <button type="submit" name="category" value="" class="rounded-full border border-slate-200 px-3 py-1 text-xs font-semibold {{ $filters['category'] === '' ? 'border-emerald-200 text-teal-700 bg-emerald-50' : 'text-slate-700 hover:border-teal-300 hover:text-teal-700' }} transition">Semua</button>
                                @forelse ($categories as $category)
                                    <button type="submit" name="category" value="{{ $category->slug }}" class="rounded-full border border-slate-200 px-3 py-1 text-xs font-semibold {{ $filters['category'] === $category->slug ? 'border-emerald-200 text-teal-700 bg-emerald-50' : 'text-slate-700 hover:border-teal-300 hover:text-teal-700' }} transition">
                                        {{ $category->name }}
                                    </button>
                                @empty
                                    @foreach ($fallbackCategories as $fallback)
                                        @php $slug = \Illuminate\Support\Str::slug($fallback); @endphp
                                        <button type="submit" name="category" value="{{ $slug }}" class="rounded-full border border-slate-200 px-3 py-1 text-xs font-semibold {{ $filters['category'] === $slug ? 'border-emerald-200 text-teal-700 bg-emerald-50' : 'text-slate-700 hover:border-teal-300 hover:text-teal-700' }} transition">
                                            {{ $fallback }}
                                        </button>
                                    @endforeach
                                @endforelse
                            </div>
                        </div>
                    </div>
                </form>

                <div id="program-list" class="mt-6 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                    @forelse ($programs as $program)
                        @php
                            $image = $program->getFirstMediaUrl('cover') ?: 'https://images.unsplash.com/photo-1488190211105-8b0e65b80b4e?auto=format&fit=crop&w=900&q=80';
                            $categoryName = $program->category?->name ?? 'Program';
                            $categorySlug = $program->category?->slug ?? 'program';
                            $progress = $program->target_amount && $program->target_amount > 0
                                ? min(100, round(($program->collected_amount / $program->target_amount) * 100))
                                : null;
                        @endphp
                        <article class="group flex flex-col overflow-hidden rounded-2xl bg-white border border-slate-100 shadow-sm hover:-translate-y-1 hover:shadow-xl transition" data-program-card data-program-text="{{ strtolower($program->title.' '.$categoryName.' '.$program->location.' '.$program->summary) }}" data-program-category="{{ $categorySlug }}">
                            <div class="relative h-44 overflow-hidden">
                                <img src="{{ $image }}" alt="{{ $program->title }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 via-transparent to-transparent"></div>
                                <span class="absolute top-3 left-3 rounded-full bg-white/90 px-3 py-1 text-xs font-semibold text-slate-900">{{ $categoryName }}</span>
                            </div>
                            <div class="flex-1 p-5 space-y-3">
                                <div class="flex items-center justify-between text-xs text-slate-500">
                                    <span>{{ $program->location ?? 'Lokasi tidak tersedia' }}</span>
                                    <span>Target {{ $program->target_amount ? 'Rp'.number_format($program->target_amount, 0, ',', '.') : '-' }}</span>
                                </div>
                                <h3 class="text-lg font-semibold text-slate-900">{{ $program->title }}</h3>
                                <p class="text-sm text-slate-600 line-clamp-3">{{ $program->summary ?? 'Deskripsi belum tersedia.' }}</p>
                                @if(!is_null($progress))
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between text-xs text-slate-500">
                                            <span>Progress</span>
                                            <span>{{ $progress }}%</span>
                                        </div>
                                        <div class="h-2 rounded-full bg-slate-100 overflow-hidden">
                                            <div class="h-full bg-gradient-to-r from-emerald-400 to-teal-500" style="width: {{ $progress }}%"></div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="p-5 pt-0 flex items-center gap-3">
                                <a href="{{ route('programs.show', $program->slug) }}#donasi" class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-emerald-400 to-teal-500 px-4 py-2 text-sm font-semibold text-slate-900 shadow-md hover:shadow-lg transition">
                                    Donasi Sekarang
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M13 6l6 6-6 6"/>
                                    </svg>
                                </a>
                                <a href="{{ route('programs.show', $program->slug) }}#donasi" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:border-teal-300 hover:text-teal-700 transition">
                                    Detail
                                </a>
                            </div>
                        </article>
                    @empty
                        <div class="col-span-full text-center text-slate-500">
                            Belum ada program tersedia.
                        </div>
                    @endforelse
                </div>
                <div class="mt-10">
                    {{ $programs->links() }}
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
