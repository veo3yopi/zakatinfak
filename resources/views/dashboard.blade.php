<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-emerald-600">Dashboard Donatur</p>
            <h2 class="font-semibold text-2xl text-slate-900 leading-tight">Selamat datang, {{ auth()->user()->name }}!</h2>
            <p class="text-sm text-slate-500">Pantau riwayat donasi, program favorit, dan progres terbaru.</p>
        </div>
    </x-slot>

    @php
        $stats = [
            ['label' => 'Total Donasi', 'value' => 'Rp0', 'desc' => 'Akumulasi donasi sukses'],
            ['label' => 'Program Didukung', 'value' => '0', 'desc' => 'Jumlah program yang kamu bantu'],
            ['label' => 'Pembayaran Pending', 'value' => '0', 'desc' => 'Menunggu konfirmasi'],
        ];

        $recentDonations = [];

        $recommendedPrograms = ($programs ?? collect())->take(3);
        if ($recommendedPrograms->isEmpty()) {
            $recommendedPrograms = collect([
                [
                    'title' => 'Program Pendidikan',
                    'summary' => 'Beasiswa santri dan kelas keterampilan.',
                    'target' => 'Rp 150.000.000',
                    'image' => 'https://images.unsplash.com/photo-1523580846011-d3a5bc25702b?auto=format&fit=crop&w=900&q=80',
                ],
                [
                    'title' => 'Bantuan Kesehatan',
                    'summary' => 'Layanan kesehatan dan gizi dhuafa.',
                    'target' => 'Rp 90.000.000',
                    'image' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=900&q=80',
                ],
                [
                    'title' => 'Respon Kemanusiaan',
                    'summary' => 'Logistik dan shelter darurat bencana.',
                    'target' => 'Rp 200.000.000',
                    'image' => 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=900&q=80',
                ],
            ]);
        }
    @endphp

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($stats as $stat)
                    <div class="rounded-2xl border border-slate-100 bg-white p-5 shadow-sm">
                        <div class="text-sm font-semibold text-slate-600">{{ $stat['label'] }}</div>
                        <div class="mt-2 text-2xl font-semibold text-slate-900">{{ $stat['value'] }}</div>
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
                        @if (empty($recentDonations))
                            <p class="text-sm text-slate-500">Belum ada donasi. Yuk mulai berkontribusi!</p>
                        @else
                            @foreach ($recentDonations as $item)
                                <div class="flex items-center justify-between rounded-xl border border-slate-100 bg-slate-50 px-4 py-3">
                                    <div>
                                        <div class="font-semibold text-slate-900">{{ $item['program'] }}</div>
                                        <div class="text-xs text-slate-500">{{ $item['date'] }} • {{ $item['status'] }}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-semibold text-slate-900">{{ $item['amount'] }}</div>
                                        <a href="#" class="text-xs font-semibold text-teal-700 hover:text-teal-800">Lihat bukti</a>
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
                        <a href="{{ url('/programs#donasi') }}" class="inline-flex items-center justify-between rounded-xl bg-gradient-to-r from-emerald-400 to-teal-500 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/35 transition">
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
                    @foreach ($recommendedPrograms as $program)
                        <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm hover:-translate-y-1 hover:shadow-lg transition">
                            <div class="relative h-36 overflow-hidden">
                                <img src="{{ $program['image'] ?? ($program->getFirstMediaUrl('cover') ?? 'https://images.unsplash.com/photo-1523580846011-d3a5bc25702b?auto=format&fit=crop&w=900&q=80') }}" alt="{{ $program['title'] ?? $program->title }}" class="h-full w-full object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/50 via-transparent to-transparent"></div>
                            </div>
                            <div class="p-4 space-y-2">
                                <h4 class="text-lg font-semibold text-slate-900">{{ $program['title'] ?? $program->title }}</h4>
                                <p class="text-sm text-slate-600">{{ $program['summary'] ?? $program->summary ?? 'Program kebaikan' }}</p>
                                <div class="text-xs font-semibold text-slate-700">Target: {{ $program['target'] ?? ('Rp ' . number_format($program->target_amount ?? 0, 0, ',', '.')) }}</div>
                                <a href="{{ isset($program['title']) ? url('/programs') : route('programs.show', $program->slug) }}" class="inline-flex items-center gap-2 text-sm font-semibold text-teal-700 hover:text-teal-800">
                                    Dukung Program
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M13 6l6 6-6 6"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
