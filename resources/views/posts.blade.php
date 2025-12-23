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
    <title>Artikel & Berita • {{ $siteTitle }}</title>
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
    $logo = $settings?->logo_url;
    if ($logo && !\Illuminate\Support\Str::startsWith($logo, ['http://', 'https://'])) {
        $logo = \Illuminate\Support\Facades\Storage::url($logo);
    }
@endphp

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
                    <a href="{{ $link['href'] }}" class="hover:text-teal-600 transition {{ request()->url() === $link['href'] ? 'text-teal-700' : '' }}">{{ $link['label'] }}</a>
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
    <section class="mt-8 rounded-3xl bg-white shadow-lg shadow-slate-200/70 p-6 sm:p-8">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-teal-600">Artikel & Berita</p>
                <h1 class="text-3xl sm:text-4xl font-bold text-slate-900 mt-2">Inspirasi, update program, dan cerita kebaikan.</h1>
                <p class="text-slate-600 mt-3 max-w-2xl">Jelajahi tulisan terbaru seputar zakat, infak, sedekah, serta kabar perjalanan program yang sedang kamu dukung.</p>
            </div>
            <form method="GET" class="grid gap-3 w-full lg:w-auto lg:grid-cols-[1fr,1fr] items-end">
                <div>
                    <label class="text-sm font-semibold text-slate-700">Cari artikel</label>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Ketik judul atau kata kunci" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-teal-300 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                </div>
                <div>
                    <label class="text-sm font-semibold text-slate-700">Kategori</label>
                    <select name="category" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-teal-300 focus:outline-none focus:ring-2 focus:ring-emerald-200">
                        <option value="">Semua</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="lg:hidden inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-brand-maroon to-brand-maroonDark px-4 py-2 text-sm font-semibold text-white shadow-md hover:shadow-lg transition">
                    Cari
                </button>
            </form>
        </div>

    </section>

    <section class="mt-8">
        @if($posts->count())
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                @foreach($posts as $post)
                    @php
                        $cover = $post->getFirstMediaUrl('cover') ?: 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=1200&q=80';
                    @endphp
                    <article class="group h-full overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-sm hover:-translate-y-1 hover:shadow-lg transition">
                        <div class="aspect-[4/3] overflow-hidden bg-slate-100">
                            <img src="{{ $cover }}" alt="{{ $post->title }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                        </div>
                        <div class="p-5 space-y-3">
                            <div class="flex flex-wrap items-center gap-2 text-xs font-semibold text-slate-600">
                                @if($post->category)
                                    <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-teal-700 ring-1 ring-emerald-100">
                                        {{ $post->category->name }}
                                    </span>
                                @endif
                                <span class="text-slate-500">{{ optional($post->published_at ?? $post->created_at)->format('d M Y') }}</span>
                            </div>
                            <h3 class="text-xl font-semibold text-slate-900 leading-tight">{{ $post->title }}</h3>
                            <p class="text-sm text-slate-600 line-clamp-3">{{ $post->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($post->content), 120) }}</p>
                            <a href="{{ route('posts.show', $post->slug) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-teal-700 hover:text-teal-800">
                                Baca selengkapnya
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M13 6l6 6-6 6"/>
                                </svg>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
            <div class="mt-8">
                {{ $posts->links('pagination::tailwind') }}
            </div>
        @else
            <div class="rounded-2xl border border-slate-100 bg-white p-8 text-center text-slate-600 shadow-sm">
                Belum ada artikel yang bisa ditampilkan.
            </div>
        @endif
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
