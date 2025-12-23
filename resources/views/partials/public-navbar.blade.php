@php
    $logoUrl = $logo ?? $settings?->logo_url ?? null;
    if ($logoUrl && !\Illuminate\Support\Str::startsWith($logoUrl, ['http://', 'https://', '/'])) {
        $logoUrl = \Illuminate\Support\Facades\Storage::url($logoUrl);
    }
@endphp

<header class="sticky top-0 z-30 bg-white/90 backdrop-blur shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-6 py-4">
            <div class="flex items-center gap-4 flex-1">
                <a href="{{ url('/') }}" class="flex items-center gap-2">
                    <div class="h-11 w-11 rounded-2xl overflow-hidden bg-slate-900/10 shadow-lg">
                        <img src="{{ $logoUrl ?? 'https://dummyimage.com/80x80/14b8a6/ffffff&text=Z' }}" alt="Brand logo" class="h-full w-full object-cover">
                    </div>
                    <div>
                        <div class="text-lg font-semibold text-slate-900">{{ $settings->site_name ?? 'Zakat Impact' }}</div>
                        <div class="text-sm text-slate-500">{{ $settings->site_tagline ?? 'Transparan • Amanah • Cepat' }}</div>
                    </div>
                </a>
                <form action="{{ route('search') }}" method="GET" class="hidden md:flex flex-1 max-w-xl items-center">
                    <label class="sr-only" for="search-navbar">Cari</label>
                    <div class="relative w-full">
                        <input id="search-navbar" type="text" name="q" value="{{ request('q') }}" placeholder="Cari..." class="w-full rounded-xl border border-slate-200 bg-slate-100/80 pl-10 pr-3 py-2 text-sm focus:border-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-200" />
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15z"/>
                            </svg>
                        </span>
                    </div>
                </form>
            </div>
            <nav class="hidden lg:flex items-center gap-5 text-sm font-semibold text-slate-700">
                @foreach ($navLinks as $link)
                    <a href="{{ $link['href'] }}" class="hover:text-brand-maroon transition {{ request()->url() === $link['href'] ? 'text-brand-maroon' : '' }}" data-nav-link>{{ $link['label'] }}</a>
                @endforeach
            </nav>
            <div class="hidden md:flex items-center gap-3">
                @if(auth()->check())
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 rounded-xl bg-brand-maroon px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-brand-maroonDark transition">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-xl bg-brand-maroon px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-brand-maroonDark transition">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 rounded-xl border border-brand-maroon px-4 py-2 text-sm font-semibold text-brand-maroon hover:bg-brand-cream/70 transition">
                        Daftar
                    </a>
                @endif
            </div>
        </div>
    </div>
</header>
