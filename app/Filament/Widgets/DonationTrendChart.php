<?php

namespace App\Filament\Widgets;

use App\Models\Donation;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class DonationTrendChart extends ChartWidget
{
    protected static ?string $heading = 'Tren Donasi 6 Bulan Terakhir';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $months = collect(range(0, 5))
            ->map(fn ($offset) => now()->subMonths(5 - $offset)->startOfMonth());

        $totals = $months->map(function (Carbon $month) {
            return Donation::where('status', 'confirmed')
                ->whereBetween('confirmed_at', [
                    $month->copy()->startOfMonth(),
                    $month->copy()->endOfMonth(),
                ])
                ->sum('amount');
        });

        return [
            'datasets' => [
                [
                    'label' => 'Donasi Terkonfirmasi',
                    'data' => $totals->values(),
                    'borderColor' => '#16a34a',
                    'backgroundColor' => 'rgba(22, 163, 74, 0.2)',
                    'fill' => true,
                    'tension' => 0.35,
                ],
            ],
            'labels' => $months->map(fn (Carbon $month) => $month->format('M Y'))->values(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
