<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ProgramHeroSettings extends Page implements HasForms
{
    use InteractsWithForms;
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Hero Program';

    protected static ?string $title = 'Pengaturan Hero Program';

    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.program-hero-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $settings = SiteSetting::firstOrCreate([]);

        $this->form->fill([
            'program_hero_show_title' => $settings->program_hero_show_title ?? true,
            'program_hero_show_summary' => $settings->program_hero_show_summary ?? true,
            'program_hero_show_cta' => $settings->program_hero_show_cta ?? true,
            'program_show_categories' => $settings->program_show_categories ?? true,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Kontrol Hero Halaman Program')
                    ->description('Atur elemen yang akan tampil di hero halaman Program agar sesuai kebutuhan campaign.')
                    ->schema([
                        Forms\Components\Toggle::make('program_hero_show_title')
                            ->label('Tampilkan Judul')
                            ->helperText('Matikan jika ingin menyembunyikan headline utama.')
                            ->default(true),
                        Forms\Components\Toggle::make('program_hero_show_summary')
                            ->label('Tampilkan Ringkasan')
                            ->helperText('Ringkasan singkat tepat di bawah judul.')
                            ->default(true),
                        Forms\Components\Toggle::make('program_hero_show_cta')
                            ->label('Tampilkan Tombol')
                            ->helperText('Kontrol tombol aksi “Jelajahi Program”.')
                            ->default(true),
                        Forms\Components\Toggle::make('program_show_categories')
                            ->label('Tampilkan Filter Kategori')
                            ->helperText('Matikan jika ingin menyembunyikan seluruh pill kategori pada halaman Program.')
                            ->default(true),
                    ])
                    ->columns(1),
            ])
            ->statePath('data')
            ->model(SiteSetting::class);
    }

    public function submit(): void
    {
        $state = $this->form->getState();

        $settings = SiteSetting::firstOrCreate([]);

        $settings->update([
            'program_hero_show_title' => (bool) ($state['program_hero_show_title'] ?? true),
            'program_hero_show_summary' => (bool) ($state['program_hero_show_summary'] ?? true),
            'program_hero_show_cta' => (bool) ($state['program_hero_show_cta'] ?? true),
            'program_show_categories' => (bool) ($state['program_show_categories'] ?? true),
        ]);

        Notification::make()
            ->title('Hero Program berhasil diperbarui')
            ->success()
            ->send();
    }
}
