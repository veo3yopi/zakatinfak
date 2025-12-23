<?php

namespace App\Filament\Widgets;

use App\Models\Donation;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentDonations extends BaseWidget
{
    protected static ?string $heading = 'Donasi Terbaru';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Donation::query()
                    ->with('program')
                    ->latest()
            )
            ->defaultPaginationPageOption(5)
            ->columns([
                Tables\Columns\TextColumn::make('donor_name')
                    ->label('Donatur')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('program.title')
                    ->label('Program')
                    ->wrap()
                    ->limit(30),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Nominal')
                    ->alignEnd()
                    ->formatStateUsing(fn ($state) => 'Rp' . number_format($state, 0, ',', '.')),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'confirmed',
                        'danger' => 'rejected',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ]);
    }
}
