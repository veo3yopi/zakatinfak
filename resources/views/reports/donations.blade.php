<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Donasi</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, sans-serif; color: #1f2937; font-size: 12px; }
        h1 { font-size: 18px; margin: 0 0 6px; }
        .muted { color: #6b7280; }
        .summary { display: table; width: 100%; margin: 12px 0 16px; }
        .summary .card { display: table-cell; padding: 10px 12px; border: 1px solid #e5e7eb; border-radius: 10px; }
        .summary .card + .card { margin-left: 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #e5e7eb; padding: 6px 8px; text-align: left; vertical-align: top; }
        th { background: #f3f4f6; font-weight: 700; }
        .right { text-align: right; }
    </style>
</head>
<body>
    @php
        $maskEmail = function (?string $email) {
            if (! $email || ! str_contains($email, '@')) {
                return $email ?? '-';
            }
            [$name, $domain] = explode('@', $email, 2);
            $prefix = mb_substr($name, 0, 2);
            return $prefix . '***@' . $domain;
        };
        $maskPhone = function (?string $phone) {
            if (! $phone) {
                return '-';
            }
            return mb_substr($phone, 0, 4) . '****';
        };
        $maskName = function (?string $name) {
            if (! $name) {
                return '-';
            }
            return mb_substr($name, 0, 2) . '***';
        };
        $fmtStatus = function (?string $status) {
            return match ($status) {
                'confirmed' => 'Terkonfirmasi',
                'pending' => 'Pending',
                'rejected' => 'Ditolak',
                default => $status ?? '-',
            };
        };
    @endphp

    <h1>Laporan Donasi</h1>
    <div class="muted">Periode: {{ $period ?? '-' }}</div>
    <div class="summary">
        <div class="card">
            <div class="muted">Total Donasi</div>
            <div><strong>Rp{{ number_format($summary['total_amount'] ?? 0, 0, ',', '.') }}</strong></div>
        </div>
        <div class="card">
            <div class="muted">Total Transaksi</div>
            <div><strong>{{ number_format($summary['total_count'] ?? 0) }}</strong></div>
        </div>
        <div class="card">
            <div class="muted">Confirmed</div>
            <div><strong>{{ number_format($summary['confirmed'] ?? 0) }}</strong></div>
        </div>
        <div class="card">
            <div class="muted">Pending + Rejected</div>
            <div><strong>{{ number_format(($summary['pending'] ?? 0) + ($summary['rejected'] ?? 0)) }}</strong></div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Program</th>
                <th>Donatur</th>
                <th>Email</th>
                <th>Telepon</th>
                <th class="right">Nominal</th>
                <th>Metode</th>
                <th>Channel</th>
                <th>Order ID</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $row)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ optional($row->created_at)->format('d M Y') }}</td>
                    <td>{{ $row->program?->title ?? '-' }}</td>
                    <td>{{ $mask ? $maskName($row->donor_name) : ($row->donor_name ?? '-') }}</td>
                    <td>{{ $mask ? $maskEmail($row->donor_email) : ($row->donor_email ?? '-') }}</td>
                    <td>{{ $mask ? $maskPhone($row->donor_phone) : ($row->donor_phone ?? '-') }}</td>
                    <td class="right">Rp{{ number_format($row->amount ?? 0, 0, ',', '.') }}</td>
                    <td>{{ $row->payment_method === 'midtrans' ? 'Midtrans' : 'Manual' }}</td>
                    <td>{{ $row->midtrans_payment_type ?? '-' }}</td>
                    <td>{{ $row->midtrans_order_id ?? '-' }}</td>
                    <td>{{ $fmtStatus($row->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
