<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DonationResource\Pages;
use App\Models\Donation;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DonationResource extends Resource
{
    protected static ?string $model = Donation::class;

    protected static ?string $navigationIcon = 'heroicon-o-receipt-refund';

    protected static ?string $navigationGroup = 'Transaksi';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Detail Donasi')
                ->columns(2)
                ->schema([
                    Forms\Components\Grid::make(1)->schema([
                        Placeholder::make('donor_info')
                            ->label('Donatur')
                            ->content(fn ($record) => $record?->donor_name . ($record?->donor_email ? ' • ' . $record->donor_email : ''))
                            ->visible(fn ($record) => filled($record)),
                        Placeholder::make('donor_phone')
                            ->label('Telepon')
                            ->content(fn ($record) => $record?->donor_phone ?? '-')
                            ->visible(fn ($record) => filled($record)),
                        Placeholder::make('amount_info')
                            ->label('Nominal')
                            ->content(fn ($record) => $record ? 'Rp' . number_format($record->amount, 0, ',', '.') : '-')
                            ->visible(fn ($record) => filled($record)),
                    ]),
                    Forms\Components\Grid::make(1)->schema([
                        Placeholder::make('program_info')
                            ->label('Program')
                            ->content(fn ($record) => $record?->program?->title ?? '-')
                            ->visible(fn ($record) => filled($record)),
                    ]),
                ]),
            Forms\Components\Section::make('Verifikasi & Bukti')
                ->columns(2)
                ->schema([
                    Select::make('status')
                        ->options([
                            'pending' => 'Pending',
                            'confirmed' => 'Terkonfirmasi',
                            'rejected' => 'Ditolak',
                        ])
                        ->required(),
                    FileUpload::make('proof_path')
                        ->label('Bukti Transfer')
                        ->disk('public')
                        ->directory('donations/proof')
                        ->imageEditor()
                        ->imagePreviewHeight('180')
                        ->openable()
                        ->downloadable(),
                    Textarea::make('admin_note')
                        ->label('Catatan Admin')
                        ->rows(4)
                        ->columnSpan(2),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('#')->sortable(),
                TextColumn::make('program.title')->label('Program')->searchable(),
                TextColumn::make('donor_name')->label('Nama Donatur')->searchable(),
                TextColumn::make('donor_email')->label('Email')->toggleable(),
                TextColumn::make('donor_phone')->label('Telepon')->toggleable(),
                TextColumn::make('amount')->label('Nominal')->money('idr', true)->sortable(),
                SpatieMediaLibraryImageColumn::make('program_cover')
                    ->label('Cover Program')
                    ->getStateUsing(fn ($record) => $record?->program?->getFirstMediaUrl('cover'))
                    ->visible(fn ($record) => filled($record?->program?->getFirstMediaUrl('cover')))
                    ->circular()
                    ->toggleable(),
                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'pending' => 'warning',
                        'confirmed' => 'success',
                        'rejected' => 'danger',
                    ])
                    ->formatStateUsing(fn ($state) => [
                        'pending' => 'Pending',
                        'confirmed' => 'Terkonfirmasi',
                        'rejected' => 'Ditolak',
                    ][$state] ?? $state),
                ImageColumn::make('proof_path')
                    ->label('Bukti')
                    ->disk('public')
                    ->square()
                    ->openUrlInNewTab()
                    ->toggleable(),
                TextColumn::make('created_at')->label('Dibuat')->since()->sortable(),
                TextColumn::make('confirmed_at')->label('Dikonfirmasi')->since()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    'pending' => 'Pending',
                    'confirmed' => 'Terkonfirmasi',
                    'rejected' => 'Ditolak',
                ]),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()->label('Setujui/Tolak'),
                    Tables\Actions\Action::make('confirm')
                        ->label('Konfirmasi')
                        ->action(fn (Donation $record) => $record->update(['status' => 'confirmed', 'confirmed_at' => now()]))
                        ->color('success')
                        ->requiresConfirmation(),
                    Tables\Actions\Action::make('reject')
                        ->label('Tolak')
                        ->action(fn (Donation $record) => $record->update(['status' => 'rejected', 'confirmed_at' => null]))
                        ->color('danger')
                        ->requiresConfirmation(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDonations::route('/'),
            'edit' => Pages\EditDonation::route('/{record}/edit'),
        ];
    }
}
