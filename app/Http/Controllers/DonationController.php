<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\BankAccount;
use App\Models\Program;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DonationController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'program_id' => ['required', 'exists:programs,id'],
            'donor_name' => ['required', 'string', 'max:255'],
            'donor_email' => ['nullable', 'email', 'max:255'],
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

        return view('donations.payment', compact('donation', 'program', 'bankAccounts'));
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
        $donation->update(['proof_path' => $proofPath]);

        return redirect()->route('donations.thankyou', $donation)
            ->with('status', 'Bukti transfer diterima. Admin akan memverifikasi.');
    }
}
