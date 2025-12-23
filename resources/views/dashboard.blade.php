<x-app-layout>
    @php
        $logo = $settings?->logo_url ?? null;
        if ($logo && !\Illuminate\Support\Str::startsWith($logo, ['http://', 'https://'])) {
            $logo = \Illuminate\Support\Facades\Storage::url($logo);
        }
    @endphp

    {{-- Mobile top bar --}}
    <div class="sm:hidden sticky top-0 z-30 bg-white/95 backdrop-blur border-b border-slate-200">
        <div class="flex items-center justify-between px-4 py-3">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-2xl bg-emerald-500 text-white flex items-center justify-center font-semibold shadow">
                    @if($logo)
                        <img src="{{ $logo }}" alt="Logo" class="h-full w-full object-cover rounded-2xl">
                    @else
                        Z
                    @endif
                </div>
                <div class="leading-tight">
                    <div class="text-base font-semibold text-slate-900">{{ $settings->site_name ?? 'Zakat Impact' }}</div>
                    <div class="text-xs text-slate-500">{{ $settings->site_tagline ?? 'Transparan • Amanah • Cepat' }}</div>
                </div>
            </div>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-xs font-semibold text-slate-800 shadow-sm hover:border-teal-300 hover:text-teal-700 transition">
                Dashboard
            </a>
        </div>
    </div>

    <x-slot name="header">
        <div class="flex flex-col gap-2">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-emerald-600">Dashboard Donatur</p>
            <h2 class="font-semibold text-2xl text-slate-900 leading-tight">Selamat datang, {{ auth()->user()->name }}!</h2>
            <p class="text-sm text-slate-500">Pantau riwayat donasi, status verifikasi, dan program yang kamu dukung.</p>
        </div>
    </x-slot>

    <div class="py-10 pb-24 sm:pb-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            <div class="rounded-3xl bg-gradient-to-r from-brand-maroon to-brand-maroonDark text-white p-6 sm:p-8 shadow-lg flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-emerald-100">Ringkasan</p>
                    <h3 class="text-2xl font-semibold">Terima kasih sudah berdonasi.</h3>
                    <p class="text-white/80 text-sm">Lihat progres donasi dan lanjutkan kebaikanmu.</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ url('/programs') }}" class="inline-flex items-center gap-2 rounded-xl bg-white/10 px-4 py-3 text-sm font-semibold text-white border border-white/20 hover:bg-white/15 transition">
                        Lihat Program
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M13 6l6 6-6 6"/>
                        </svg>
                    </a>
                    <a href="{{ url('/programs#donasi') }}" class="inline-flex items-center gap-2 rounded-xl bg-white text-slate-900 px-4 py-3 text-sm font-semibold shadow">
                        Donasi Lagi
                    </a>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($stats as $stat)
                    <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                        <div class="text-sm font-semibold text-slate-600">{{ $stat['label'] }}</div>
                        <div class="mt-2 text-2xl font-semibold text-slate-900">
                            @if(is_numeric($stat['value']))
                                {{ $stat['label'] === 'Total Donasi' ? 'Rp' . number_format($stat['value'], 0, ',', '.') : number_format($stat['value']) }}
                            @else
                                {{ $stat['value'] }}
                            @endif
                        </div>
                        <p class="mt-1 text-sm text-slate-500">{{ $stat['desc'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                <div class="lg:col-span-2 rounded-2xl border border-slate-100 bg-white shadow-sm">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                        <div>
                            <p class="text-sm font-semibold text-slate-600">Riwayat Donasi</p>
                            <h3 class="text-lg font-semibold text-slate-900">Transaksi Terbaru</h3>
                        </div>
                        <a href="{{ url('/programs') }}" class="text-sm font-semibold text-teal-700 hover:text-teal-800">Donasi lagi</a>
                    </div>
                    <div class="p-6 space-y-4">
                        @if ($donations->isEmpty())
                            <p class="text-sm text-slate-500">Belum ada donasi. Yuk mulai berkontribusi!</p>
                        @else
                            @foreach ($donations as $item)
                                <div class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                                    <div>
                                        <div class="font-semibold text-slate-900">{{ $item->program?->title ?? 'Program' }}</div>
                                        <div class="text-xs text-slate-500">{{ $item->created_at->format('d M Y') }} • {{ ucfirst($item->status) }}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-semibold text-slate-900">Rp{{ number_format($item->amount, 0, ',', '.') }}</div>
                                        @if($item->proof_path)
                                            <a href="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($item->proof_path) }}" target="_blank" class="text-xs font-semibold text-teal-700 hover:text-teal-800">Lihat bukti</a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-100 bg-white shadow-sm p-6 space-y-4">
                    <div class="space-y-1">
                        <p class="text-sm font-semibold text-slate-600">Aksi Cepat</p>
                        <h3 class="text-lg font-semibold text-slate-900">Mulai Donasi</h3>
                    </div>
                    <div class="grid gap-3">
                        <a href="{{ url('/programs#donasi') }}" class="inline-flex items-center justify-between rounded-xl bg-gradient-to-r from-brand-maroon to-brand-maroonDark px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-brand-maroon/25 hover:shadow-brand-maroon/35 transition">
                            Donasi Cepat
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M13 6l6 6-6 6"/>
                            </svg>
                        </a>
                        <a href="{{ url('/programs') }}" class="inline-flex items-center justify-between rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-800 hover:border-teal-300 hover:text-teal-700 transition">
                            Lihat Program
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M13 6l6 6-6 6"/>
                            </svg>
                        </a>
                        <a href="{{ route('profile.edit') }}" class="inline-flex items-center justify-between rounded-xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-800 hover:border-teal-300 hover:text-teal-700 transition">
                            Ubah Profil
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 20.5l8-8.5-4.5-4.5-11 11.5L4 21l5.5-1.5zM13 7l4 4"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-slate-100 bg-white shadow-sm">
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                    <div>
                        <p class="text-sm font-semibold text-slate-600">Program Rekomendasi</p>
                        <h3 class="text-lg font-semibold text-slate-900">Dukung program pilihan</h3>
                    </div>
                    <a href="{{ url('/programs') }}" class="text-sm font-semibold text-teal-700 hover:text-teal-800">Lihat semua</a>
                </div>
                <div class="p-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @forelse ($recommendedPrograms as $program)
                        <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm hover:-translate-y-1 hover:shadow-lg transition">
                            <div class="relative h-36 overflow-hidden">
                                <img src="{{ $program->getFirstMediaUrl('cover') ?: 'https://images.unsplash.com/photo-1523580846011-d3a5bc25702b?auto=format&fit=crop&w=900&q=80' }}" alt="{{ $program->title }}" class="h-full w-full object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/50 via-transparent to-transparent"></div>
                            </div>
                            <div class="p-4 space-y-2">
                                <h4 class="text-lg font-semibold text-slate-900">{{ $program->title }}</h4>
                                <p class="text-sm text-slate-600">{{ $program->summary ?? 'Program kebaikan' }}</p>
                                <div class="text-xs font-semibold text-slate-700">Target: Rp{{ number_format($program->target_amount ?? 0, 0, ',', '.') }}</div>
                                <a href="{{ route('programs.show', $program->slug) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-teal-700 hover:text-teal-800">
                                    Dukung Program
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M13 6l6 6-6 6"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Belum ada rekomendasi program.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom navigation (mobile) --}}
    <div class="sm:hidden fixed bottom-0 inset-x-0 z-30 bg-white/95 backdrop-blur border-t border-slate-200 shadow-lg">
        <div class="relative mx-auto max-w-4xl px-4">
            <div class="grid grid-cols-4 text-center text-sm font-semibold text-slate-600">
            <a href="{{ url('/') }}" class="py-4 flex flex-col items-center gap-1 {{ request()->is('/') ? 'text-teal-700' : '' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9.75 12 4l9 5.75V19a1 1 0 0 1-1 1h-5.5v-5.25h-5V20H4a1 1 0 0 1-1-1V9.75z"/></svg>
                <span class="text-xs">Home</span>
            </a>
            <a href="{{ url('/programs') }}" class="py-4 flex flex-col items-center gap-1 {{ request()->is('programs*') ? 'text-teal-700' : '' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 4.5h7.5v7.5h-7.5zM12.75 4.5h7.5v4.5h-7.5zM12.75 12h7.5v7.5h-7.5zM3.75 14.25h7.5v5.25h-7.5z"/></svg>
                <span class="text-xs">Program</span>
            </a>
            <a href="{{ url('/posts') }}" class="py-4 flex flex-col items-center gap-1 {{ request()->is('posts*') ? 'text-teal-700' : '' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 4.5h14M5 9h14M5 13.5h8M5 18h4"/></svg>
                <span class="text-xs">Artikel</span>
            </a>
            <a href="{{ url('/about') }}" class="py-4 flex flex-col items-center gap-1 {{ request()->is('about') ? 'text-teal-700' : '' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5zM12 18v-8.25"/></svg>
                <span class="text-xs">Tentang</span>
            </a>
            </div>
        </div>
    </div>
</x-app-layout>
