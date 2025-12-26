<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\BankAccount;
use App\Models\Program;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DonationController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $enabledChannels = $this->getEnabledChannels();
        $midtransAvailable = config('midtrans.server_key') && config('midtrans.client_key');
        $data = $request->validate([
            'program_id' => ['required', 'exists:programs,id'],
            'donor_name' => ['required', 'string', 'max:255'],
            'donor_email' => ['required', 'email', 'max:255'],
            'donor_phone' => ['nullable', 'string', 'max:50'],
            'amount' => ['required', 'numeric', 'min:10000'],
            'payment_channel' => $midtransAvailable
                ? ['required', 'string', Rule::in($enabledChannels)]
                : ['nullable', 'string'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $program = Program::findOrFail($data['program_id']);
        $paymentChannel = $midtransAvailable ? $data['payment_channel'] : null;
        $feeAmount = $this->calculateAdminFee($paymentChannel, (int) $data['amount']);
        $grossAmount = (int) $data['amount'] + $feeAmount;

        $donation = Donation::create([
            'program_id' => $program->id,
            'user_id' => auth()->id(),
            'donor_name' => $data['donor_name'],
            'donor_email' => $data['donor_email'] ?? null,
            'donor_phone' => $data['donor_phone'] ?? null,
            'amount' => $data['amount'],
            'payment_channel' => $paymentChannel,
            'admin_fee_amount' => $feeAmount,
            'midtrans_gross_amount' => $grossAmount,
            'access_token' => Str::random(48),
            'payment_method' => 'manual_transfer',
            'status' => 'pending',
            'note' => $data['note'] ?? null,
        ]);

        return redirect()
            ->route('donations.payment', ['donation' => $donation, 'token' => $donation->access_token])
            ->with('status', 'Donasi tercatat. Silakan lanjutkan ke instruksi pembayaran.');
    }

    public function thankyou(Donation $donation)
    {
        // Pastikan user terkait atau guest yang baru submit bisa melihat halaman ini
        if ($donation->user_id && auth()->id() !== $donation->user_id) {
            abort(403);
        }
        if (! $donation->user_id && ! $donation->access_token) {
            $donation->update(['access_token' => Str::random(48)]);
            return redirect()->route('donations.thankyou', ['donation' => $donation, 'token' => $donation->access_token]);
        }
        if (! $donation->user_id && request('token') !== $donation->access_token) {
            abort(403);
        }

        $manualProofSubmitted = $donation->payment_method === 'manual_transfer' && filled($donation->proof_path);
        if ($donation->status !== 'confirmed' && ! $manualProofSubmitted) {
            return redirect()
                ->route('donations.payment', ['donation' => $donation, 'token' => $donation->access_token])
                ->with('status', 'Pembayaran masih pending. Silakan selesaikan pembayaran terlebih dahulu.');
        }

        $program = $donation->program;

        return view('donations.thank-you', compact('donation', 'program'));
    }

    public function payment(Donation $donation)
    {
        if ($donation->user_id && auth()->id() !== $donation->user_id) {
            abort(403);
        }
        if (! $donation->user_id && ! $donation->access_token) {
            $donation->update(['access_token' => Str::random(48)]);
            return redirect()->route('donations.payment', ['donation' => $donation, 'token' => $donation->access_token]);
        }
        if (! $donation->user_id && request('token') !== $donation->access_token) {
            abort(403);
        }
        if ($donation->status === 'confirmed') {
            return redirect()->route('donations.thankyou', ['donation' => $donation, 'token' => $donation->access_token]);
        }

        $program = $donation->program;
        $bankAccounts = BankAccount::where('is_active', true)->orderBy('sort_order')->get();
        $snapToken = null;
        $snapError = null;
        $feeAmount = null;
        $grossAmount = null;
        $channelLabel = null;
        $channelLabels = [
            'bank_transfer' => 'Virtual Account',
            'qris' => 'QRIS',
            'gopay' => 'GoPay',
            'shopeepay' => 'ShopeePay',
            'dana' => 'DANA',
            'credit_card' => 'Kartu Kredit',
            'minimarket' => 'Minimarket',
            'akulaku' => 'Akulaku Paylater',
            'kredivo' => 'Kredivo',
        ];
        if ($donation->payment_channel) {
            $feeAmount = $this->calculateAdminFee($donation->payment_channel, (int) $donation->amount);
            $grossAmount = (int) $donation->amount + $feeAmount;
            $channelLabel = $channelLabels[$donation->payment_channel] ?? $donation->payment_channel;
        }

        try {
            $snapToken = $this->createSnapToken($donation, $program);
        } catch (\Throwable $e) {
            Log::warning('Midtrans snap token error', [
                'donation_id' => $donation->id,
                'message' => $e->getMessage(),
            ]);
            $snapError = 'Pembayaran otomatis tidak tersedia. ' . Str::limit($e->getMessage(), 120);
            $snapToken = null;
        }

        if (! $snapToken && ! $snapError) {
            $snapError = 'Pembayaran otomatis tidak tersedia saat ini. Silakan gunakan transfer manual.';
        }

        return view('donations.payment', compact('donation', 'program', 'bankAccounts', 'snapToken', 'snapError', 'feeAmount', 'grossAmount', 'channelLabel'));
    }

    public function uploadProof(Request $request, Donation $donation): RedirectResponse
    {
        if ($donation->user_id && auth()->id() !== $donation->user_id) {
            abort(403);
        }
        if (! $donation->user_id && $request->input('token') !== $donation->access_token) {
            abort(403);
        }

        $data = $request->validate([
            'proof' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
        ]);

        $proofPath = $request->file('proof')->store('donations/proof', 'public');
        $donation->update([
            'proof_path' => $proofPath,
            'payment_method' => 'manual_transfer',
            'status' => 'pending',
            'payment_channel' => null,
            'admin_fee_amount' => null,
            'midtrans_gross_amount' => null,
        ]);

        return redirect()->route('donations.thankyou', ['donation' => $donation, 'token' => $donation->access_token])
            ->with('status', 'Bukti transfer diterima. Admin akan memverifikasi.');
    }

    public function midtransCallback(Request $request)
    {
        $payload = $request->all();

        if (! $this->isValidMidtransSignature($payload)) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $orderId = $payload['order_id'] ?? null;
        if (! $orderId) {
            return response()->json(['message' => 'Missing order_id'], 422);
        }

        $donation = Donation::where('midtrans_order_id', $orderId)->first();
        if (! $donation) {
            return response()->json(['message' => 'Donation not found'], 404);
        }

        $transactionStatus = $payload['transaction_status'] ?? null;
        $paymentType = $payload['payment_type'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;
        $transactionId = $payload['transaction_id'] ?? null;
        $grossAmount = $payload['gross_amount'] ?? null;
        $grossAmountValue = is_numeric($grossAmount) ? (int) round((float) $grossAmount) : null;
        $expectedGross = (int) $donation->amount + $this->calculateAdminFee($donation->payment_channel, (int) $donation->amount);

        $status = match ($transactionStatus) {
            'capture', 'settlement' => 'confirmed',
            'pending' => 'pending',
            default => 'rejected',
        };

        if ($grossAmountValue && $expectedGross && $grossAmountValue !== $expectedGross) {
            Log::warning('Midtrans gross amount mismatch', [
                'donation_id' => $donation->id,
                'gross_amount' => $grossAmountValue,
                'expected_gross' => $expectedGross,
            ]);
            $status = 'pending';
            $donation->admin_note = trim(implode(' | ', array_filter([
                $donation->admin_note,
                "Mismatch gross amount: {$grossAmountValue} != {$expectedGross}",
            ])));
        }

        $donation->update([
            'payment_method' => 'midtrans',
            'midtrans_status' => $transactionStatus,
            'midtrans_payment_type' => $paymentType,
            'midtrans_fraud_status' => $fraudStatus,
            'midtrans_transaction_id' => $transactionId,
            'midtrans_gross_amount' => $grossAmountValue,
            'status' => $status,
            'confirmed_at' => $status === 'confirmed' ? now() : null,
        ]);

        return response()->json(['message' => 'OK']);
    }

    private function createSnapToken(Donation $donation, Program $program): ?string
    {
        if (! config('midtrans.server_key') || ! config('midtrans.client_key')) {
            throw new \RuntimeException('Konfigurasi Midtrans belum lengkap.');
        }

        if ($donation->snap_token) {
            return $donation->snap_token;
        }

        if (! $donation->midtrans_order_id) {
            $donation->midtrans_order_id = 'DON-' . $donation->id . '-' . Str::upper(Str::random(6));
            $donation->save();
        }

        $emailFallback = config('mail.from.address') ?: 'donor@example.com';
        $itemName = Str::limit($program->title, 50, '');
        $feeAmount = $this->calculateAdminFee($donation->payment_channel, (int) $donation->amount);
        $grossAmount = (int) $donation->amount + $feeAmount;
        if ($donation->admin_fee_amount !== $feeAmount || $donation->midtrans_gross_amount !== $grossAmount) {
            $donation->update([
                'admin_fee_amount' => $feeAmount,
                'midtrans_gross_amount' => $grossAmount,
            ]);
        }
        $payload = [
            'transaction_details' => [
                'order_id' => $donation->midtrans_order_id,
                'gross_amount' => $grossAmount,
            ],
            'customer_details' => [
                'first_name' => $donation->donor_name,
                'email' => $donation->donor_email ?: $emailFallback,
                'phone' => $donation->donor_phone,
            ],
            'item_details' => array_values(array_filter([
                [
                    'id' => 'program-' . $program->id,
                    'price' => (int) $donation->amount,
                    'quantity' => 1,
                    'name' => $itemName,
                ],
                $feeAmount > 0 ? [
                    'id' => 'admin-fee',
                    'price' => $feeAmount,
                    'quantity' => 1,
                    'name' => 'Biaya Admin',
                ] : null,
            ])),
        ];

        $enabledPayments = $this->getEnabledPayments($donation->payment_channel);
        if (! empty($enabledPayments)) {
            $payload['enabled_payments'] = $enabledPayments;
        }

        Log::info('Midtrans snap payload', [
            'donation_id' => $donation->id,
            'payload' => $payload,
        ]);

        $response = Http::withBasicAuth(config('midtrans.server_key'), '')
            ->acceptJson()
            ->post(config('midtrans.snap_url'), $payload);

        if (! $response->successful()) {
            Log::warning('Midtrans snap token request failed', [
                'donation_id' => $donation->id,
                'status' => $response->status(),
                'response' => $response->body(),
            ]);
            $message = $response->json('error_messages.0')
                ?: $response->json('status_message')
                ?: 'Midtrans request gagal.';
            throw new \RuntimeException($message);
        }

        $token = $response->json('token');
        $redirectUrl = $response->json('redirect_url');

        if (! $token) {
            throw new \RuntimeException('Token Midtrans tidak tersedia.');
        }

        $donation->update([
            'snap_token' => $token,
            'snap_redirect_url' => $redirectUrl,
            'payment_method' => 'midtrans',
        ]);

        return $token;
    }

    private function calculateAdminFee(?string $channel, int $amount): int
    {
        if (! $channel || $amount <= 0) {
            return 0;
        }

        return match ($channel) {
            'bank_transfer' => 4000,
            'qris' => (int) ceil($amount * 0.007),
            'gopay' => (int) ceil($amount * 0.02),
            'shopeepay' => (int) ceil($amount * 0.02),
            'dana' => (int) ceil($amount * 0.015),
            'credit_card' => (int) ceil($amount * 0.029) + 2000,
            'minimarket' => 5000,
            'akulaku' => (int) ceil($amount * 0.017),
            'kredivo' => (int) ceil($amount * 0.02),
            default => 0,
        };
    }

    private function getEnabledPayments(?string $channel): array
    {
        return match ($channel) {
            'bank_transfer' => ['bank_transfer'],
            'qris' => ['qris'],
            'gopay' => ['gopay'],
            'shopeepay' => ['shopeepay'],
            'dana' => ['dana'],
            'credit_card' => ['credit_card'],
            'minimarket' => ['alfamart', 'indomaret'],
            'akulaku' => ['akulaku'],
            'kredivo' => ['kredivo'],
            default => [],
        };
    }

    private function getEnabledChannels(): array
    {
        $settings = SiteSetting::first();
        $channels = $settings?->payment_channels;
        if (empty($channels) || ! is_array($channels)) {
            return [
                'bank_transfer',
                'qris',
                'gopay',
                'shopeepay',
                'dana',
                'credit_card',
                'minimarket',
                'akulaku',
                'kredivo',
            ];
        }

        return array_values(array_unique($channels));
    }

    private function isValidMidtransSignature(array $payload): bool
    {
        $serverKey = config('midtrans.server_key');
        $orderId = $payload['order_id'] ?? '';
        $statusCode = $payload['status_code'] ?? '';
        $grossAmount = $payload['gross_amount'] ?? '';
        $signatureKey = $payload['signature_key'] ?? '';

        if (! $serverKey || ! $orderId || ! $statusCode || ! $grossAmount || ! $signatureKey) {
            return false;
        }

        $expected = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        return hash_equals($expected, $signatureKey);
    }
}
