<x-filament-panels::page>
    <div class="space-y-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-gray-900">
            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-500 dark:text-gray-400">Laporan Donasi</p>
                    <h2 class="mt-2 text-2xl font-semibold text-gray-900 dark:text-white">Ringkasan & Detail Transaksi</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Atur periode, status, dan opsi masking sebelum export.</p>
                </div>
            </div>
            <div class="mt-6">
                {{ $this->form }}
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-500 dark:text-gray-400">Total Donasi</p>
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v8m4-4H8"/>
                        </svg>
                    </span>
                </div>
                <p class="mt-2 text-2xl font-semibold text-gray-900 dark:text-white">
                    Rp{{ number_format($summary['total_amount'] ?? 0, 0, ',', '.') }}
                </p>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-500 dark:text-gray-400">Total Transaksi</p>
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-blue-50 text-blue-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 7h16M4 12h16M4 17h16"/>
                        </svg>
                    </span>
                </div>
                <p class="mt-2 text-2xl font-semibold text-gray-900 dark:text-white">
                    {{ number_format($summary['total_count'] ?? 0) }}
                </p>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-500 dark:text-gray-400">Confirmed</p>
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </span>
                </div>
                <p class="mt-2 text-2xl font-semibold text-emerald-600">
                    {{ number_format($summary['confirmed'] ?? 0) }}
                </p>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-gray-500 dark:text-gray-400">Pending + Rejected</p>
                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-amber-50 text-amber-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </span>
                </div>
                <p class="mt-2 text-2xl font-semibold text-amber-600">
                    {{ number_format(($summary['pending'] ?? 0) + ($summary['rejected'] ?? 0)) }}
                </p>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-4 dark:border-gray-800 dark:bg-gray-900">
            {{ $this->table }}
        </div>
    </div>
</x-filament-panels::page>
