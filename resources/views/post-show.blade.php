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
    <title>{{ $post->title }} • {{ $siteTitle }}</title>
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
    $cover = $post->getFirstMediaUrl('cover') ?: 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=1600&q=80';
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
                <a href="{{ route('programs') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:border-teal-200 hover:text-teal-700 transition">
                    Program
                </a>
                <a href="{{ route('donations.store') }}" class="hidden"></a>
                <a href="{{ route('programs') }}" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-teal-500 to-emerald-400 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-teal-500/20 hover:shadow-teal-500/30 transition">
                    Donasi Sekarang
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M13 6l6 6-6 6"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</header>

<main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
    <section class="mt-8 rounded-3xl bg-white shadow-lg shadow-slate-200/70 overflow-hidden">
        <div class="relative">
            <img src="{{ $cover }}" alt="{{ $post->title }}" class="w-full max-h-[480px] object-cover">
        </div>
        <div class="p-6 sm:p-8 space-y-6">
            <div class="rounded-2xl border border-slate-100 bg-slate-50 p-5 sm:p-6 shadow-sm">
                <div class="flex flex-wrap items-center gap-2 text-[11px] font-semibold tracking-wide text-slate-700">
                    @if($post->category)
                        <span class="inline-flex items-center gap-2 rounded-full bg-white px-3 py-1 text-slate-900 shadow-sm ring-1 ring-slate-200">
                            {{ $post->category->name }}
                        </span>
                    @endif
                    <span class="inline-flex items-center gap-2 rounded-full bg-white px-3 py-1 text-slate-900 shadow-sm ring-1 ring-slate-200">
                        {{ optional($post->published_at ?? $post->created_at)->format('d M Y') }}
                    </span>
                    @if($post->is_featured)
                        <span class="inline-flex items-center gap-2 rounded-full bg-amber-400 text-amber-900 px-3 py-1 shadow-sm">
                            Unggulan
                        </span>
                    @endif
                </div>
                <h1 class="mt-3 text-3xl sm:text-4xl font-semibold leading-tight text-slate-900">{{ $post->title }}</h1>
                <p class="text-sm text-slate-600">
                    Oleh {{ $post->author?->name ?? 'Tim' }}
                    @if($post->tags->isNotEmpty())
                        • @foreach($post->tags as $tag)<span class="mr-1">#{{ $tag->name }}</span>@endforeach
                    @endif
                </p>
            </div>
            <article class="prose prose-slate max-w-none">
                {!! $post->content !!}
            </article>
            @if($post->tags->isNotEmpty())
                <div class="mt-2 flex flex-wrap gap-2">
                    @foreach($post->tags as $tag)
                        <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">#{{ $tag->name }}</span>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    @if($related->isNotEmpty())
        <section class="mt-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-slate-900">Artikel terkait</h2>
                <a href="{{ route('posts.index') }}" class="text-sm font-semibold text-teal-700 hover:text-teal-800">Lihat semua</a>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                @foreach($related as $item)
                    @php
                        $itemCover = $item->getFirstMediaUrl('cover') ?: 'https://images.unsplash.com/photo-1488190211105-8b0e65b80b4e?auto=format&fit=crop&w=1200&q=80';
                    @endphp
                    <a href="{{ route('posts.show', $item->slug) }}" class="flex gap-4 rounded-2xl border border-slate-100 bg-white p-3 shadow-sm hover:-translate-y-1 hover:shadow-lg transition">
                        <div class="h-24 w-28 overflow-hidden rounded-xl bg-slate-100">
                            <img src="{{ $itemCover }}" alt="{{ $item->title }}" class="h-full w-full object-cover">
                        </div>
                        <div class="flex-1 space-y-1">
                            <div class="text-xs font-semibold text-slate-500">{{ optional($item->published_at ?? $item->created_at)->format('d M Y') }}</div>
                            <h3 class="text-sm font-semibold text-slate-900 line-clamp-2">{{ $item->title }}</h3>
                            <p class="text-xs text-slate-600 line-clamp-2">{{ $item->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($item->content), 80) }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
</main>
</body>
</html>
