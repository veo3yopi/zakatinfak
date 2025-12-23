<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pembayaran Donasi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @if(config('midtrans.client_key'))
        <script src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
    @endif
</head>
<body class="bg-slate-50 text-slate-900">
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-6">
    @if(session('status'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
            {{ session('status') }}
        </div>
    @endif

    <div class="rounded-3xl bg-white shadow-lg border border-slate-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div class="space-y-1">
                <p class="text-sm font-semibold text-emerald-600 uppercase tracking-[0.2em]">Tahap 2/3</p>
                <h1 class="text-2xl font-semibold text-slate-900">Pembayaran Donasi</h1>
                <p class="text-sm text-slate-600">Pilih metode pembayaran otomatis atau gunakan transfer manual sebagai cadangan.</p>
            </div>
            <a href="{{ route('programs.show', $program->slug) }}" class="text-sm font-semibold text-brand-maroon hover:text-brand-maroonDark md:self-auto self-start">Kembali ke program</a>
        </div>

        @php($hasSnap = (bool) $snapToken)
        <div class="p-6 grid gap-6 {{ $hasSnap ? '' : 'md:grid-cols-2' }}">
            <div class="space-y-3">
                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4">
                    <div class="text-sm font-semibold text-slate-600">Program</div>
                    <div class="text-lg font-semibold text-slate-900 leading-snug break-words">{{ $program->title }}</div>
                    <div class="text-sm text-slate-600">Nominal: <span class="font-semibold">Rp{{ number_format($donation->amount, 0, ',', '.') }}</span></div>
                </div>
                @if($hasSnap)
                    <div class="rounded-2xl border border-emerald-100 bg-emerald-50/60 p-4 space-y-2">
                        <p class="text-sm font-semibold text-emerald-700">Pembayaran Otomatis</p>
                        <p class="text-sm text-slate-600">Bayar via VA bank, e-wallet, atau QRIS dengan Midtrans Snap.</p>
                        <button type="button" id="pay-button" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-brand-maroon to-brand-maroonDark px-4 py-2 text-sm font-semibold text-white shadow hover:shadow-lg transition">
                            Bayar Sekarang
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M13 6l6 6-6 6" />
                            </svg>
                        </button>
                        <p class="text-xs text-slate-500">Pembayaran manual hanya tersedia jika gateway tidak dapat diakses.</p>
                    </div>
                @else
                    <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">
                        {{ $snapError ?? 'Pembayaran otomatis sedang tidak tersedia. Silakan gunakan transfer manual.' }}
                    </div>
                @endif
                @unless($hasSnap)
                    <div class="space-y-2">
                        <p class="text-sm font-semibold text-slate-700">Instruksi Transfer Manual</p>
                        <ol class="list-decimal list-inside text-sm text-slate-600 space-y-1">
                            <li>Pilih salah satu rekening donasi di bawah.</li>
                            <li>Transfer sesuai nominal: <strong>Rp{{ number_format($donation->amount, 0, ',', '.') }}</strong>.</li>
                            <li>Setelah transfer, unggah bukti transaksi.</li>
                        </ol>
                    </div>
                    <div class="space-y-3">
                        <p class="text-sm font-semibold text-slate-700">Rekening Donasi</p>
                        @forelse ($bankAccounts as $account)
                            <div class="rounded-xl border border-slate-100 bg-white p-3 shadow-sm">
                                <div class="font-semibold text-slate-900">{{ $account->bank_name }}</div>
                                <div class="text-sm text-slate-700">{{ $account->account_number }}</div>
                                <div class="text-xs text-slate-500">a.n {{ $account->account_name }}</div>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">Belum ada rekening terdaftar.</p>
                        @endforelse
                    </div>
                @endunless
            </div>
            @unless($hasSnap)
                <div class="space-y-4">
                    <p class="text-sm font-semibold text-slate-700">Unggah Bukti Transfer</p>
                    <form method="POST" action="{{ route('donations.uploadProof', $donation) }}" enctype="multipart/form-data" class="space-y-3 rounded-2xl border border-slate-100 bg-white p-4 shadow-sm">
                        @csrf
                        <input type="file" name="proof" accept=".jpg,.jpeg,.png,.pdf" class="w-full text-sm text-slate-700">
                        @error('proof')<p class="text-xs text-brand-maroon">{{ $message }}</p>@enderror
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-brand-maroon to-brand-maroonDark px-4 py-2 text-sm font-semibold text-white shadow hover:shadow-lg transition">
                            Kirim Bukti
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 12h14M13 6l6 6-6 6" />
                            </svg>
                        </button>
                    </form>
                </div>
            @endunless
        </div>
    </div>
</div>

<div class="mt-16">
    <x-site-footer :settings="$settings ?? null" />
</div>

@if($snapToken)
<script>
document.addEventListener('DOMContentLoaded', () => {
    const payButton = document.getElementById('pay-button');
    if (!payButton || !window.snap) return;

    payButton.addEventListener('click', () => {
        window.snap.pay(@json($snapToken), {
            onSuccess: function () {
                window.location.href = @json(route('donations.thankyou', $donation));
            },
            onPending: function () {
                window.location.href = @json(route('donations.thankyou', $donation));
            },
            onError: function () {
                payButton.disabled = false;
                payButton.classList.remove('opacity-60', 'cursor-not-allowed');
            },
            onClose: function () {
                payButton.disabled = false;
                payButton.classList.remove('opacity-60', 'cursor-not-allowed');
            }
        });
        payButton.disabled = true;
        payButton.classList.add('opacity-60', 'cursor-not-allowed');
    });
});
</script>
@endif

</body>
</html>
