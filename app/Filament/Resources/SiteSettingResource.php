<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteSettingResource\Pages;
use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\FileUpload;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\Resource;

class SiteSettingResource extends Resource
{
    protected static ?string $model = SiteSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Site Settings';

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Identitas')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('site_name')->label('Nama Lembaga')->required(),
                    Forms\Components\TextInput::make('site_title')->label('Title / Page Title')->helperText('Judul yang tampil di tab browser.')->columnSpan(1),
                    Forms\Components\TextInput::make('site_tagline')->label('Tagline'),
                    Forms\Components\Textarea::make('site_description')->label('Deskripsi')->rows(2)->columnSpanFull(),
                    FileUpload::make('logo_url')
                        ->label('Logo')
                        ->image()
                        ->directory('site/logo')
                        ->columnSpan(1),
                    FileUpload::make('favicon_url')
                        ->label('Favicon')
                        ->image()
                        ->directory('site/favicon')
                        ->columnSpan(1),
                    FileUpload::make('about_hero_url')
                        ->label('Banner Halaman Tentang')
                        ->image()
                        ->helperText('Gunakan banner landscape min. 1600x500px agar tetap tajam pada ukuran hero (hingga 1280px lebar & tinggi 256-384px). Pastikan area penting ada di tengah karena gambar akan dipotong (object-fit: cover).')
                        ->directory('site/about')
                        ->columnSpanFull(),
                ]),
            Forms\Components\Section::make('Kontak & Hero')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('contact_email')->label('Email'),
                    Forms\Components\TextInput::make('contact_phone')->label('Telepon'),
                    Forms\Components\TextInput::make('address')->label('Alamat')->columnSpanFull(),
                    Forms\Components\TextInput::make('hero_cta_label')->label('CTA Label'),
                    Forms\Components\TextInput::make('hero_cta_url')->label('CTA URL'),
                ]),
        Forms\Components\Section::make('Program')
            ->schema([
                FileUpload::make('program_banner_url')
                    ->label('Banner Halaman Program')
                    ->image()
                    ->helperText('Gunakan banner panorama 1600x260px (tablet 1024x220px, mobile 768x200px) agar pas dengan tinggi hero 260px. Simpan fokus utama di tengah karena gambar akan dipotong (object-fit: cover).')
                    ->directory('site/program')
                    ->columnSpanFull(),
            ]),
            Forms\Components\Section::make('Tentang')
                ->schema([
                    Forms\Components\TextInput::make('about_title')->label('Judul'),
                    Forms\Components\TextInput::make('about_subtitle')->label('Subjudul'),
                    Forms\Components\Textarea::make('about_summary')->label('Ringkasan')->rows(2),
                    Forms\Components\Textarea::make('about_mission')->label('Misi')->rows(2),
                    Forms\Components\Textarea::make('about_vision')->label('Visi')->rows(2),
                    Forms\Components\Textarea::make('about_values')->label('Nilai')->rows(2),
                    Forms\Components\Textarea::make('about_principles')->label('Prinsip')->rows(2),
                    Forms\Components\Textarea::make('about_goals')->label('Tujuan')->rows(2),
                ]),
            Forms\Components\Section::make('Dampak')
                ->columns(2)
                ->schema([
                    Forms\Components\TextInput::make('impact_beneficiaries')->label('Penerima Manfaat')->numeric(),
                    Forms\Components\TextInput::make('impact_programs')->label('Program')->numeric(),
                    Forms\Components\TextInput::make('impact_regions')->label('Wilayah')->numeric(),
                    Forms\Components\TextInput::make('impact_volunteers')->label('Relawan')->numeric(),
                ]),
            Forms\Components\Section::make('Footer')
                ->columns(2)
                ->schema([
                    Forms\Components\Textarea::make('footer_about_text')
                        ->label('Deskripsi Singkat Footer')
                        ->helperText('Teks yang muncul di kolom pertama footer.')
                        ->rows(3)
                        ->columnSpanFull(),
                    Forms\Components\Repeater::make('footer_links')
                        ->label('Link Tambahan')
                        ->schema([
                            Forms\Components\TextInput::make('label')
                                ->label('Nama Link')
                                ->required(),
                            Forms\Components\TextInput::make('url')
                                ->label('URL')
                                ->url()
                                ->required(),
                        ])
                        ->columns(2)
                        ->addActionLabel('Tambah Link')
                        ->columnSpanFull(),
                    Forms\Components\Repeater::make('footer_social_links')
                        ->label('Media Sosial')
                        ->columns(6)
                        ->collapsed()
                        ->schema([
                            Forms\Components\TextInput::make('label')
                                ->label('Nama Platform')
                                ->placeholder('Instagram')
                                ->required()
                                ->columnSpan(2),
                            Forms\Components\FileUpload::make('icon_path')
                                ->label('Icon Kustom')
                                ->image()
                                ->directory('site/footer/social')
                                ->helperText('Upload icon berbentuk lingkaran (PNG/SVG latar transparan).')
                                ->imageEditor()
                                ->required()
                                ->columnSpan(2),
                            Forms\Components\TextInput::make('url')
                                ->label('URL')
                                ->url()
                                ->placeholder('https://instagram.com/akun')
                                ->required()
                                ->columnSpan(2),
                        ])
                        ->addActionLabel('Tambah Sosial Media')
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('footer_address')
                        ->label('Alamat Footer')
                        ->placeholder('Jl. Amanah No. 5, Jakarta')
                        ->columnSpan(2),
                    Forms\Components\TextInput::make('footer_email')
                        ->label('Email Footer')
                        ->email(),
                    Forms\Components\TextInput::make('footer_phone')
                        ->label('Kontak Footer')
                        ->tel(),
                ]),
            Forms\Components\Section::make('Ucapan Terima Kasih')
                ->schema([
                    Forms\Components\TextInput::make('thankyou_title')
                        ->label('Judul Ucapan')
                        ->placeholder('Terima kasih atas donasimu!')
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('thankyou_message')
                        ->label('Pesan Ucapan')
                        ->rows(3)
                        ->placeholder('Semoga menjadi amal jariyah dan keberkahan untukmu.')
                        ->columnSpanFull(),
                ]),
            Forms\Components\Section::make('Pembayaran')
                ->schema([
                    Forms\Components\CheckboxList::make('payment_channels')
                        ->label('Channel Pembayaran Aktif')
                        ->options([
                            'bank_transfer' => 'Virtual Account (Transfer Bank)',
                            'qris' => 'QRIS',
                            'gopay' => 'GoPay',
                            'shopeepay' => 'ShopeePay',
                            'dana' => 'DANA',
                            'credit_card' => 'Kartu Kredit',
                            'minimarket' => 'Minimarket (Alfamart/Indomaret)',
                            'akulaku' => 'Akulaku Paylater',
                            'kredivo' => 'Kredivo',
                        ])
                        ->columns(2)
                        ->helperText('Hanya channel yang dipilih akan muncul di form donasi.')
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSiteSettings::route('/'),
            'edit' => Pages\EditSiteSetting::route('/{record}/edit'),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('site_name')->label('Nama')->searchable(),
                TextColumn::make('site_tagline')->label('Tagline')->limit(40),
                TextColumn::make('updated_at')->label('Diperbarui')->since(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([]);
    }
}
