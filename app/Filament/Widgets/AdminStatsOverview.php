<?php

namespace App\Filament\Widgets;

use App\Models\Donation;
use App\Models\Post;
use App\Models\Program;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $confirmedTotal = Donation::where('status', 'confirmed')->sum('amount');
        $pendingCount = Donation::where('status', 'pending')->count();

        return [
            Stat::make('Total Donasi', 'Rp' . number_format($confirmedTotal, 0, ',', '.'))
                ->description('Terkonfirmasi')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
            Stat::make('Donasi Pending', number_format($pendingCount))
                ->description('Menunggu pembayaran')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),
            Stat::make('Program Aktif', number_format(Program::count()))
                ->description('Program tersedia')
                ->descriptionIcon('heroicon-m-rectangle-group')
                ->color('info'),
            Stat::make('Artikel', number_format(Post::count()))
                ->description('Konten dipublikasi')
                ->descriptionIcon('heroicon-m-document-text'),
        ];
    }
}
