<?php

namespace App\Filament\Pages;

use App\Exports\DonationReportExport;
use App\Models\Donation;
use Barryvdh\DomPDF\Facade\Pdf;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class DonationReport extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static ?string $navigationGroup = 'Transaksi';

    protected static ?string $navigationLabel = 'Laporan Donasi';

    protected static ?string $title = 'Laporan Donasi';

    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.pages.donation-report';

    public ?string $startDate = null;
    public ?string $endDate = null;
    public array $statuses = [];
    public bool $mask = true;

    public function mount(): void
    {
        $now = now();

        $this->startDate = $now->copy()->startOfMonth()->toDateString();
        $this->endDate = $now->copy()->endOfMonth()->toDateString();
        $this->statuses = ['confirmed', 'pending', 'rejected'];
        $this->mask = true;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Periode Laporan')
                    ->columns(2)
                    ->schema([
                        Forms\Components\DatePicker::make('startDate')
                            ->label('Mulai')
                            ->required(),
                        Forms\Components\DatePicker::make('endDate')
                            ->label('Sampai')
                            ->required(),

                    ]),
                Forms\Components\Section::make('Opsi Laporan')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('statuses')
                            ->label('Status')
                            ->multiple()
                            ->options([
                                'confirmed' => 'Terkonfirmasi',
                                'pending' => 'Pending',
                                'rejected' => 'Ditolak',
                            ])
                            ->required(),
                        Forms\Components\Toggle::make('mask')
                            ->label('Masking Donatur')
                            ->helperText('Sembunyikan sebagian nama/email/telepon.'),
                    ]),
                    Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('apply')
                                ->label('Terapkan Filter')
                                ->color('primary')
                                ->action('applyFilters'),
                        ])->columnSpanFull(),
            ])

            ->statePath('');
    }

    public function applyFilters(): void
    {
        $this->resetTable();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getFilteredQuery())
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('rowNumber')
                    ->label('No')
                    ->rowIndex(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->date('d M Y'),
                Tables\Columns\TextColumn::make('program.title')
                    ->label('Program')
                    ->wrap()
                    ->limit(32),
                Tables\Columns\TextColumn::make('donor_name')
                    ->label('Donatur')
                    ->formatStateUsing(fn ($state) => $this->mask ? $this->maskName($state) : $state)
                    ->searchable(),
                Tables\Columns\TextColumn::make('donor_email')
                    ->label('Email')
                    ->formatStateUsing(fn ($state) => $this->mask ? $this->maskEmail($state) : $state)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('donor_phone')
                    ->label('Telepon')
                    ->formatStateUsing(fn ($state) => $this->mask ? $this->maskPhone($state) : $state)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Nominal')
                    ->money('idr', true)
                    ->alignEnd(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Metode')
                    ->formatStateUsing(fn ($state) => $state === 'midtrans' ? 'Midtrans' : 'Manual')
                    ->badge()
                    ->colors([
                        'success' => 'midtrans',
                        'warning' => 'manual_transfer',
                    ]),
                Tables\Columns\TextColumn::make('midtrans_payment_type')
                    ->label('Channel')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('midtrans_order_id')
                    ->label('Order ID')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'pending' => 'warning',
                        'confirmed' => 'green',
                        'rejected' => 'danger',
                    ])
                    ->formatStateUsing(fn ($state) => [
                        'pending' => 'Pending',
                        'confirmed' => 'Terkonfirmasi',
                        'rejected' => 'Ditolak',
                    ][$state] ?? $state),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export_pdf')
                ->label('Export PDF')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('primary')
                ->action(fn () => $this->exportPdf()),
            Action::make('export_excel')
                ->label('Export Excel')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('success')
                ->action(fn () => $this->exportExcel()),
        ];
    }

    protected function getViewData(): array
    {
        $query = $this->getFilteredQuery();

        $totalAmount = (clone $query)->sum('amount');
        $totalCount = (clone $query)->count();
        $confirmedCount = (clone $query)->where('status', 'confirmed')->count();
        $pendingCount = (clone $query)->where('status', 'pending')->count();
        $rejectedCount = (clone $query)->where('status', 'rejected')->count();

        return [
            'summary' => [
                'total_amount' => $totalAmount,
                'total_count' => $totalCount,
                'confirmed' => $confirmedCount,
                'pending' => $pendingCount,
                'rejected' => $rejectedCount,
            ],
        ];
    }

    protected function exportPdf()
    {
        $data = $this->getExportData();
        $pdf = Pdf::loadView('reports.donations', $data)->setPaper('a4', 'landscape');

        return response()->streamDownload(
            fn () => print($pdf->output()),
            $data['filename']
        );
    }

    protected function exportExcel()
    {
        $data = $this->getExportData();

        return Excel::download(
            new DonationReportExport($data['rows'], $data['mask']),
            $data['filename_excel']
        );
    }

    protected function getExportData(): array
    {
        $rows = $this->getFilteredQuery()
            ->with('program')
            ->orderBy('created_at', 'desc')
            ->get();

        $summary = $this->getViewData()['summary'];

        $period = $this->startDate . ' s/d ' . $this->endDate;
        $filename = 'laporan-donasi-' . now()->format('Ymd-His') . '.pdf';
        $filenameExcel = 'laporan-donasi-' . now()->format('Ymd-His') . '.xlsx';

        return [
            'rows' => $rows,
            'summary' => $summary,
            'period' => $period,
            'mask' => $this->mask,
            'filename' => $filename,
            'filename_excel' => $filenameExcel,
        ];
    }

    protected function getFilteredQuery(): Builder
    {
        $start = Carbon::parse($this->startDate)->startOfDay();
        $end = Carbon::parse($this->endDate)->endOfDay();

        return Donation::query()
            ->with('program')
            ->whereBetween('created_at', [$start, $end])
            ->when(count($this->statuses) > 0, fn (Builder $query) => $query->whereIn('status', $this->statuses));
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
