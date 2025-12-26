<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Program;

class Donation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'program_id',
        'user_id',
        'donor_name',
        'donor_email',
        'donor_phone',
        'amount',
        'payment_method',
        'status',
        'proof_path',
        'midtrans_order_id',
        'midtrans_transaction_id',
        'midtrans_payment_type',
        'midtrans_status',
        'midtrans_fraud_status',
        'midtrans_gross_amount',
        'payment_channel',
        'admin_fee_amount',
        'snap_token',
        'snap_redirect_url',
        'note',
        'admin_note',
        'confirmed_at',
    ];

    protected $casts = [
        'confirmed_at' => 'datetime',
        'midtrans_gross_amount' => 'integer',
        'admin_fee_amount' => 'integer',
    ];

    protected static function booted(): void
    {
        static::saved(function (Donation $donation) {
            static::syncProgramCollected($donation->program_id);
        });

        static::deleted(function (Donation $donation) {
            static::syncProgramCollected($donation->program_id);
        });
    }

    protected static function syncProgramCollected(?int $programId): void
    {
        if (! $programId) {
            return;
        }

        $total = static::where('program_id', $programId)
            ->where('status', 'confirmed')
            ->sum('amount');

        Program::where('id', $programId)->update(['collected_amount' => $total]);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
