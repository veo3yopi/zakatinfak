<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\BankAccount;
use App\Models\Program;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DonationController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'program_id' => ['required', 'exists:programs,id'],
            'donor_name' => ['required', 'string', 'max:255'],
            'donor_email' => ['required', 'email', 'max:255'],
            'donor_phone' => ['nullable', 'string', 'max:50'],
            'amount' => ['required', 'numeric', 'min:10000'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $program = Program::findOrFail($data['program_id']);

        $donation = Donation::create([
            'program_id' => $program->id,
            'user_id' => auth()->id(),
            'donor_name' => $data['donor_name'],
            'donor_email' => $data['donor_email'] ?? null,
            'donor_phone' => $data['donor_phone'] ?? null,
            'amount' => $data['amount'],
            'payment_method' => 'manual_transfer',
            'status' => 'pending',
            'note' => $data['note'] ?? null,
        ]);

        return redirect()
            ->route('donations.payment', $donation)
            ->with('status', 'Donasi tercatat. Silakan lanjutkan ke instruksi pembayaran.');
    }

    public function thankyou(Donation $donation)
    {
        // Pastikan user terkait atau guest yang baru submit bisa melihat halaman ini
        if ($donation->user_id && auth()->id() !== $donation->user_id) {
            abort(403);
        }

        $manualProofSubmitted = $donation->payment_method === 'manual_transfer' && filled($donation->proof_path);
        if ($donation->status !== 'confirmed' && ! $manualProofSubmitted) {
            return redirect()
                ->route('donations.payment', $donation)
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

        $program = $donation->program;
        $bankAccounts = BankAccount::where('is_active', true)->orderBy('sort_order')->get();
        $snapToken = null;
        $snapError = null;

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

        return view('donations.payment', compact('donation', 'program', 'bankAccounts', 'snapToken', 'snapError'));
    }

    public function uploadProof(Request $request, Donation $donation): RedirectResponse
    {
        if ($donation->user_id && auth()->id() !== $donation->user_id) {
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
        ]);

        return redirect()->route('donations.thankyou', $donation)
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

        $status = match ($transactionStatus) {
            'capture', 'settlement' => 'confirmed',
            'pending' => 'pending',
            default => 'rejected',
        };

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
        $payload = [
            'transaction_details' => [
                'order_id' => $donation->midtrans_order_id,
                'gross_amount' => (int) $donation->amount,
            ],
            'customer_details' => [
                'first_name' => $donation->donor_name,
                'email' => $donation->donor_email ?: $emailFallback,
                'phone' => $donation->donor_phone,
            ],
            'item_details' => [
                [
                    'id' => 'program-' . $program->id,
                    'price' => (int) $donation->amount,
                    'quantity' => 1,
                    'name' => $itemName,
                ],
            ],
        ];

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
