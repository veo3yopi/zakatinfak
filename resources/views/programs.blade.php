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
    <title>{{ $siteTitle }} • Program</title>
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
        ['label' => 'Program', 'href' => '#program-list'],
        ['label' => 'Artikel', 'href' => url('/posts')],
        ['label' => 'Tentang', 'href' => url('/about')],
    ];

    $categories = $categories ?? collect();
    $programs = $programs ?? collect();
    $filters = $filters ?? ['category' => '', 'q' => ''];
    $fallbackCategories = ['Zakat', 'Infak', 'Sedekah', 'Kemanusiaan'];
    $settings = $settings ?? null;
    $programBanner = $settings?->program_banner_url;
    $hasCustomProgramBanner = filled($programBanner);
    if ($hasCustomProgramBanner && !\Illuminate\Support\Str::startsWith($programBanner, ['http://', 'https://'])) {
        $programBanner = \Illuminate\Support\Facades\Storage::url($programBanner);
    }
    if (! $hasCustomProgramBanner) {
        $programBanner = null;
    }
    $programShowCategories = (bool) ($settings?->program_show_categories ?? true);
@endphp

<div class="bg-gradient-to-b from-brand-cream/80 via-brand-offwhite to-white min-h-screen">
    <header class="sticky top-0 z-30 bg-white/90 backdrop-blur shadow-sm">
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
                <nav class="hidden md:flex items-center gap-6 text-sm font-semibold text-slate-700">
                    @foreach ($navLinks as $link)
                        <a href="{{ $link['href'] }}" class="hover:text-teal-600 transition">{{ $link['label'] }}</a>
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
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <section class="mt-8 rounded-3xl bg-white shadow-xl shadow-slate-900/10 overflow-hidden">
            <div class="{{ $hasCustomProgramBanner ? 'relative' : 'bg-white flex items-center' }} h-[16.25rem] sm:h-[17.5rem]">
                @if($hasCustomProgramBanner)
                    <img src="{{ $programBanner }}" class="h-[16.25rem] sm:h-[17.5rem] w-full object-cover" alt="Banner Program">
                    <div class="absolute inset-0 p-8 sm:p-12 flex flex-col justify-center gap-3 text-white">
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-emerald-200" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.5);">Program</p>
                        <h1 class="text-3xl sm:text-4xl font-semibold leading-tight" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.5);">Pilih program kebaikan yang ingin kamu dukung</h1>
                        <p class="text-white/80 max-w-2xl" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.5);">Telusuri zakat, infak, sedekah, dan bantuan kemanusiaan dengan laporan transparan dan progres terkini.</p>
                    </div>
                @else
                    <div class="p-8 sm:p-12 flex flex-col justify-center gap-3 text-slate-900 h-full">
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-teal-600">Program</p>
                        <h1 class="text-3xl sm:text-4xl font-semibold leading-tight text-slate-900">Pilih program kebaikan yang ingin kamu dukung</h1>
                        <p class="text-slate-600 max-w-2xl">Telusuri zakat, infak, sedekah, dan bantuan kemanusiaan dengan laporan transparan dan progres terkini.</p>
                    </div>
                @endif
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
                        @if($programShowCategories)
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
                        @endif
                    </div>
                </form>

                <div id="program-list" class="mt-6 grid grid-cols-2 md:grid-cols-3 gap-4">
                    @forelse ($programs as $program)
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
                                    <span class="sm:hidden">Donasi</span>
                                    <span class="hidden sm:inline">Donasi Sekarang</span>
                                </a>
                            </div>
                        </article>
                    @empty
                        <div class="text-center text-slate-500 col-span-2 md:col-span-3 py-10">
                            Tidak ada program yang cocok dengan pencarian Anda.
                        </div>
                    @endforelse
                </div>
                <div class="mt-10">
                    {{ $programs->links() }}
                </div>
            </div>
        </section>
    </main>

    <div class="mt-16">
        <x-site-footer :settings="$settings ?? null" />
    </div>
</div>

<nav class="fixed bottom-0 left-0 right-0 z-40 border-t border-slate-200 bg-white/95 backdrop-blur shadow-[0_-8px_30px_rgba(15,23,42,0.08)] md:hidden">
    <div class="relative mx-auto max-w-4xl px-4">
        <div class="grid grid-cols-4 py-3 text-xs font-semibold text-slate-600">
            @foreach ($navLinks as $link)
                <a href="{{ $link['href'] }}"
                   class="flex flex-col items-center gap-1 rounded-lg px-2 py-1 hover:text-teal-600 transition">
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
