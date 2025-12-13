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
    <title>{{ $program->title }} • {{ $siteTitle }}</title>
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
<body class="bg-slate-50 text-slate-900 antialiased">
@php
    $navLinks = [
        ['label' => 'Home', 'href' => url('/')],
        ['label' => 'Program', 'href' => url('/programs')],
        ['label' => 'Artikel', 'href' => url('/posts')],
        ['label' => 'Tentang', 'href' => url('/about')],
    ];
    $cover = $program->getFirstMediaUrl('cover') ?: 'https://images.unsplash.com/photo-1488190211105-8b0e65b80b4e?auto=format&fit=crop&w=1600&q=80';
    $progress = $program->target_amount && $program->target_amount > 0
        ? min(100, round(($program->collected_amount / $program->target_amount) * 100))
        : null;
    $daysLeft = $program->ends_at ? max(0, now()->diffInDays($program->ends_at, false)) : null;
    $donorCount = 0;
    $settings = $settings ?? null;
@endphp

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
                <a href="#donasi" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-teal-500 to-emerald-400 px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-teal-500/20 hover:shadow-teal-500/30 transition">
                    {{ $settings?->hero_cta_label ?? 'Donasi Sekarang' }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M13 6l6 6-6 6"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</header>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
    <section class="mt-6 rounded-3xl bg-white shadow-lg shadow-slate-200/70 p-4 sm:p-6">
        <div class="grid gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2">
                <div class="relative overflow-hidden rounded-2xl border border-slate-100 bg-slate-50">
                    <img src="{{ $cover }}" alt="{{ $program->title }}" class="h-full w-full max-h-[520px] object-cover">
                    <button type="button" class="absolute bottom-4 right-4 inline-flex items-center gap-2 rounded-xl bg-white/90 px-3 py-2 text-xs font-semibold text-slate-700 shadow-md hover:bg-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-300" data-image-viewer data-image-src="{{ $cover }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 8V6a2 2 0 012-2h2m4 0h2a2 2 0 012 2v2m0 4v2a2 2 0 01-2 2h-2m-4 0H6a2 2 0 01-2-2v-2m4-6h.01m6 0h.01M9 15h6"/></svg>
                        Lihat Banner
                    </button>
                </div>
                <div class="mt-6 space-y-3">
                    <div class="flex flex-wrap items-center gap-2 text-xs font-semibold text-slate-700">
                        <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1 text-teal-700 ring-1 ring-emerald-100">
                            {{ $program->category?->name ?? 'Program' }}
                        </span>
                        @if($program->location)
                            <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-slate-700 ring-1 ring-slate-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 21s-6-4.35-6-10a6 6 0 1112 0c0 5.65-6 10-6 10z"/><circle cx="12" cy="11" r="2.5" /></svg>
                                {{ $program->location }}
                            </span>
                        @endif
                        @if($program->published_at)
                            <span class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-slate-700 ring-1 ring-slate-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6v6l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $program->published_at->format('d M Y') }}
                            </span>
                        @endif
                    </div>
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-semibold leading-tight text-slate-900">{{ $program->title }}</h1>
                    <p class="text-slate-600 max-w-3xl">{{ $program->summary ?? 'Program kebaikan' }}</p>
                </div>
            </div>
            <div class="space-y-4" id="donasi">
                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-5">
                    <div class="text-sm text-slate-500">{{ $program->category?->name ?? 'Program' }}</div>
                    <div class="text-2xl sm:text-3xl font-semibold text-slate-900 mt-1">
                        Rp{{ number_format($program->target_amount ?? 0, 0, ',', '.') }}
                    </div>
                    <p class="text-sm text-slate-600">Target penggalangan</p>
                    @if(!is_null($progress))
                        <div class="mt-4 space-y-2">
                            <div class="flex items-center justify-between text-xs text-slate-600">
                                <span>Terkumpul</span>
                                <span class="font-semibold text-slate-800">{{ $progress }}%</span>
                            </div>
                            <div class="h-2.5 rounded-full bg-white overflow-hidden border border-slate-100">
                                <div class="h-full bg-gradient-to-r from-emerald-400 to-teal-500 transition-[width] duration-700 ease-out" style="width: {{ $progress }}%"></div>
                            </div>
                            <div class="flex items-center justify-between text-xs text-slate-600">
                                <span>Rp{{ number_format($program->collected_amount, 0, ',', '.') }}</span>
                                <span>{{ $daysLeft !== null ? $daysLeft . ' hari lagi' : 'Tanpa batas waktu' }}</span>
                            </div>
                        </div>
                    @endif
                    <div class="mt-4 flex items-center justify-between text-sm text-slate-600">
                        <span>{{ $donorCount }} Donatur</span>
                        <span>Bagikan:</span>
                    </div>
                    <div class="mt-2 flex items-center gap-3 text-slate-500">
                        <a href="#" class="hover:text-teal-600">Facebook</a>
                        <a href="#" class="hover:text-teal-600">WhatsApp</a>
                        <a href="#" class="hover:text-teal-600">Twitter</a>
                    </div>
                </div>
                @if(session('status'))
                    <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                        {{ session('status') }}
                    </div>
                @endif
                <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                    <h3 class="text-lg font-semibold text-slate-900">Donasi Sekarang</h3>
                    <p class="text-sm text-slate-600 mb-4">Isi form berikut untuk konfirmasi donasi.</p>
                    <form method="POST" action="{{ route('donations.store') }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="program_id" value="{{ $program->id }}">
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Nama Lengkap</label>
                            <input type="text" name="donor_name" value="{{ old('donor_name') }}" required class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-teal-300 focus:outline-none focus:ring-2 focus:ring-emerald-200" placeholder="Nama Anda">
                            @error('donor_name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Email (opsional)</label>
                            <input type="email" name="donor_email" value="{{ old('donor_email') }}" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-teal-300 focus:outline-none focus:ring-2 focus:ring-emerald-200" placeholder="email@example.com">
                            @error('donor_email')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Nominal Donasi (Rp)</label>
                            <div class="mt-1 relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-sm">Rp</span>
                                <input
                                    type="number"
                                    name="amount"
                                    value="{{ old('amount') }}"
                                    required
                                    min="10000"
                                    step="1000"
                                    class="w-full rounded-xl border border-slate-200 bg-white pl-10 pr-3 py-2 text-sm appearance-none focus:border-teal-300 focus:outline-none focus:ring-2 focus:ring-emerald-200"
                                    placeholder="100000">
                            </div>
                            @error('amount')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-slate-700">Pesan (opsional)</label>
                            <textarea name="note" rows="3" class="mt-1 w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:border-teal-300 focus:outline-none focus:ring-2 focus:ring-emerald-200" placeholder="Doa atau tujuan donasi">{{ old('note') }}</textarea>
                            @error('note')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-emerald-400 to-teal-500 px-4 py-3 text-sm font-semibold text-slate-900 shadow-md hover:shadow-lg transition">
                            Tunaikan Sekarang
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M13 6l6 6-6 6"/>
                            </svg>
                        </button>
                        <p class="text-xs text-slate-500 text-center">Minimal donasi Rp10.000.</p>
                    </form>
                </div>
            </div>
        </div>
        <div class="mt-8 rounded-2xl border border-slate-100 bg-white p-5">
            <div class="flex items-center gap-4 border-b border-slate-100 pb-3 text-sm font-semibold text-slate-600">
                <button class="text-teal-700">Deskripsi</button>
                <button class="text-slate-400 cursor-not-allowed" disabled>Info Terbaru</button>
                <button class="text-slate-400 cursor-not-allowed" disabled>Donatur</button>
            </div>
            <div class="pt-4 prose prose-slate max-w-none">
                {!! $program->content ?? '<p>Deskripsi program belum tersedia.</p>' !!}
            </div>
        </div>
    </section>
</main>

<div id="image-lightbox" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/80"></div>
    <div class="relative z-10 flex h-full flex-col">
        <div class="flex items-center justify-end gap-2 p-4">
            <button type="button" class="rounded-lg bg-white/80 px-3 py-2 text-xs font-semibold text-slate-700 shadow hover:bg-white" data-zoom-out>
                Zoom -
            </button>
            <button type="button" class="rounded-lg bg-white/80 px-3 py-2 text-xs font-semibold text-slate-700 shadow hover:bg-white" data-zoom-in>
                Zoom +
            </button>
            <button type="button" class="rounded-lg bg-white/80 px-3 py-2 text-xs font-semibold text-slate-700 shadow hover:bg-white" data-zoom-reset>
                Reset
            </button>
            <button type="button" class="rounded-lg bg-white/80 px-3 py-2 text-xs font-semibold text-slate-700 shadow hover:bg-white" data-close-viewer>
                Tutup
            </button>
        </div>
        <div class="relative flex-1 overflow-hidden">
            <div class="absolute inset-0 flex items-center justify-center">
                <img data-viewer-image src="" alt="Preview" class="max-h-full max-w-full object-contain transition-transform duration-200 ease-out" style="transform: scale(1);">
            </div>
        </div>
    </div>
</div>
</body>
</html>
