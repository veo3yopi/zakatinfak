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
        $logo = $settings?->logo_url;
        if ($logo && !\Illuminate\Support\Str::startsWith($logo, ['http://', 'https://'])) {
            $logo = \Illuminate\Support\Facades\Storage::url($logo);
        }
        $navLinks = [
            ['label' => 'Home', 'href' => url('/')],
            ['label' => 'Program', 'href' => url('/programs')],
            ['label' => 'Artikel', 'href' => url('/posts')],
            ['label' => 'Tentang', 'href' => url('/about')],
        ];
    @endphp
    <title>Pencarian: {{ $q }} • {{ $siteTitle }}</title>
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
<header class="sticky top-0 z-30 bg-white/90 backdrop-blur shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between py-4">
            <a href="{{ url('/') }}" class="flex items-center gap-2">
                <div class="h-11 w-11 rounded-2xl overflow-hidden bg-slate-900/10 shadow-lg">
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
                <a href="{{ route('programs') }}" class="hidden sm:inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-brand-maroon to-brand-maroonDark px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-brand-maroon/20 hover:shadow-brand-maroon/30 transition">
                    Lihat Program
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M13 6l6 6-6 6"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</header>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
    <section class="mt-8 rounded-3xl bg-white shadow-lg shadow-slate-200/70 p-6 sm:p-8 space-y-6">
        <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-teal-600">Pencarian</p>
                <h1 class="text-3xl sm:text-4xl font-bold text-slate-900">Hasil untuk: “{{ $q }}”</h1>
                <p class="text-slate-600 mt-2">Menampilkan program dan artikel yang sesuai kata kunci.</p>
            </div>
            <form action="{{ route('search') }}" method="GET" class="w-full md:w-auto flex gap-3">
                <div class="relative flex-1 md:w-80">
                    <input type="text" name="q" value="{{ $q }}" placeholder="Cari program atau artikel" class="w-full rounded-xl border border-slate-200 pl-10 pr-3 py-2.5 text-sm focus:border-teal-300 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15z"/></svg>
                    </span>
                </div>
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-brand-maroon to-brand-maroonDark px-4 py-2.5 text-sm font-semibold text-white shadow-md hover:shadow-lg transition">
                    Cari
                </button>
            </form>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-5 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-teal-600">Program</p>
                        <h2 class="text-xl font-semibold text-slate-900">Program ditemukan</h2>
                    </div>
                    <a href="{{ route('programs', ['q' => $q]) }}" class="text-sm font-semibold text-teal-700 hover:text-teal-800">Lihat semua</a>
                </div>
                @if($programs->count())
                    <div class="space-y-4">
                        @foreach($programs as $program)
                            @php
                                $cover = $program->getFirstMediaUrl('cover') ?: 'https://images.unsplash.com/photo-1488190211105-8b0e65b80b4e?auto=format&fit=crop&w=900&q=80';
                                $progress = ($program->target_amount > 0) ? min(100, round($program->collected_amount / $program->target_amount * 100)) : null;
                            @endphp
                            <a href="{{ route('programs.show', $program->slug) }}" class="flex gap-3 rounded-xl bg-white border border-slate-100 p-3 hover:-translate-y-0.5 hover:shadow-md transition">
                                <div class="h-20 w-24 overflow-hidden rounded-lg bg-slate-100">
                                    <img src="{{ $cover }}" alt="{{ $program->title }}" class="h-full w-full object-cover">
                                </div>
                                <div class="flex-1 space-y-1">
                                    <div class="flex flex-wrap items-center gap-2 text-[11px] font-semibold text-slate-600">
                                        @if($program->category)
                                            <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-teal-700 ring-1 ring-emerald-100">
                                                {{ $program->category->name }}
                                            </span>
                                        @endif
                                        @if($program->location)
                                            <span class="text-slate-500">{{ $program->location }}</span>
                                        @endif
                                    </div>
                                    <h3 class="text-sm font-semibold text-slate-900 line-clamp-2">{{ $program->title }}</h3>
                                    <p class="text-xs text-slate-600 line-clamp-2">{{ $program->summary ?? 'Program kebaikan' }}</p>
                                    @if(!is_null($progress))
                                        <div class="mt-1">
                                            <div class="h-1.5 rounded-full bg-slate-100 overflow-hidden">
                                                <div class="h-full bg-gradient-to-r from-brand-maroon to-brand-maroonDark" style="width: {{ $progress }}%"></div>
                                            </div>
                                            <div class="text-[11px] text-slate-500 mt-1">Terkumpul {{ $progress }}%</div>
                                        </div>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-slate-600">Tidak ada program yang cocok.</p>
                @endif
            </div>

            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-5 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-teal-600">Artikel</p>
                        <h2 class="text-xl font-semibold text-slate-900">Artikel & berita ditemukan</h2>
                    </div>
                    <a href="{{ route('posts.index', ['q' => $q]) }}" class="text-sm font-semibold text-teal-700 hover:text-teal-800">Lihat semua</a>
                </div>
                @if($posts->count())
                    <div class="space-y-4">
                        @foreach($posts as $post)
                            @php
                                $cover = $post->getFirstMediaUrl('cover') ?: 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=900&q=80';
                            @endphp
                            <a href="{{ route('posts.show', $post->slug) }}" class="flex gap-3 rounded-xl bg-white border border-slate-100 p-3 hover:-translate-y-0.5 hover:shadow-md transition">
                                <div class="h-20 w-24 overflow-hidden rounded-lg bg-slate-100">
                                    <img src="{{ $cover }}" alt="{{ $post->title }}" class="h-full w-full object-cover">
                                </div>
                                <div class="flex-1 space-y-1">
                                    <div class="flex flex-wrap items-center gap-2 text-[11px] font-semibold text-slate-600">
                                        @if($post->category)
                                            <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-teal-700 ring-1 ring-emerald-100">
                                                {{ $post->category->name }}
                                            </span>
                                        @endif
                                        <span class="text-slate-500">{{ optional($post->published_at ?? $post->created_at)->format('d M Y') }}</span>
                                    </div>
                                    <h3 class="text-sm font-semibold text-slate-900 line-clamp-2">{{ $post->title }}</h3>
                                    <p class="text-xs text-slate-600 line-clamp-2">{{ $post->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($post->content), 90) }}</p>
                                    @if($post->tags->isNotEmpty())
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($post->tags->take(3) as $tag)
                                                <span class="text-[11px] rounded-full bg-slate-100 px-2 py-1 text-slate-600">#{{ $tag->name }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-slate-600">Tidak ada artikel yang cocok.</p>
                @endif
            </div>
        </div>
    </section>
</main>

<div class="mt-16">
    <x-site-footer :settings="$settings ?? null" />
</div>

</body>
</html>
