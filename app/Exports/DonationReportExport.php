<?php

namespace App\Exports;

use App\Models\Donation;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DonationReportExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(
        protected Collection $rows,
        protected bool $mask = true,
    ) {}

    public function collection(): Collection
    {
        return $this->rows;
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Program',
            'Donatur',
            'Email',
            'Telepon',
            'Nominal',
            'Metode',
            'Channel',
            'Order ID',
            'Status',
        ];
    }

    public function map($row): array
    {
        $donation = $row instanceof Donation ? $row : null;

        return [
            optional($donation?->created_at)->format('d M Y'),
            $donation?->program?->title ?? '-',
            $this->mask ? $this->maskName($donation?->donor_name) : ($donation?->donor_name ?? '-'),
            $this->mask ? $this->maskEmail($donation?->donor_email) : ($donation?->donor_email ?? '-'),
            $this->mask ? $this->maskPhone($donation?->donor_phone) : ($donation?->donor_phone ?? '-'),
            $donation ? 'Rp' . number_format($donation->amount, 0, ',', '.') : '-',
            $donation?->payment_method === 'midtrans' ? 'Midtrans' : 'Manual',
            $donation?->midtrans_payment_type ?? '-',
            $donation?->midtrans_order_id ?? '-',
            match ($donation?->status) {
                'confirmed' => 'Terkonfirmasi',
                'pending' => 'Pending',
                'rejected' => 'Ditolak',
                default => $donation?->status ?? '-',
            },
        ];
    }

    private function maskEmail(?string $email): string
    {
        if (! $email || ! str_contains($email, '@')) {
            return $email ?? '-';
        }

        [$name, $domain] = explode('@', $email, 2);
        $prefix = mb_substr($name, 0, 2);

        return $prefix . '***@' . $domain;
    }

    private function maskPhone(?string $phone): string
    {
        if (! $phone) {
            return '-';
        }

        $prefix = mb_substr($phone, 0, 4);

        return $prefix . '****';
    }

    private function maskName(?string $name): string
    {
        if (! $name) {
            return '-';
        }

        $prefix = mb_substr($name, 0, 2);

        return $prefix . '***';
    }
}
