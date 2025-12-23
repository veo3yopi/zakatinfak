<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProgramResource\Pages;
use App\Models\Program;
use App\Models\ProgramCategory;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ProgramResource extends Resource
{
    protected static ?string $model = Program::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';

    protected static ?string $navigationGroup = 'Program';

    protected static ?string $navigationLabel = 'Program';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Hidden::make('user_id')->default(fn () => auth()->id())->dehydrated(),

                Forms\Components\Section::make('Informasi Program')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Slug unik untuk URL')
                            ->unique(ignoreRecord: true)
                            ->readonly(),
                        Select::make('program_category_id')
                            ->relationship('category', 'name')
                            ->label('Kategori')
                            ->required()
                            ->preload()
                            ->searchable()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label('Nama Kategori')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                                TextInput::make('slug')
                                    ->label('Slug')
                                    ->required()
                                    ->unique(table: ProgramCategory::class, column: 'slug')
                                    ->maxLength(255)
                                    ->readonly(),
                                Textarea::make('description')
                                    ->label('Deskripsi')
                                    ->rows(2),
                                TextInput::make('sort_order')
                                    ->label('Urutan')
                                    ->numeric()
                                    ->default(0),
                            ])
                            ->createOptionUsing(function (array $data): int {
                                return ProgramCategory::create([
                                    'name' => $data['name'],
                                    'slug' => $data['slug'] ?? Str::slug($data['name']),
                                    'description' => $data['description'] ?? null,
                                    'sort_order' => $data['sort_order'] ?? 0,
                                ])->getKey();
                            }),
                        Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Published',
                                'archived' => 'Archived',
                            ])
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                if ($state === 'published' && ! $get('published_at')) {
                                    $set('published_at', now());
                                }
                            })
                            ->default('draft')
                            ->required(),
                        Forms\Components\Toggle::make('is_featured')
                            ->label('Tandai sebagai unggulan')
                            ->default(false),
                        TextInput::make('location')
                            ->label('Lokasi')
                            ->maxLength(255),
                        TextInput::make('target_amount')
                            ->label('Target Dana')
                            ->prefix('Rp')
                            ->numeric()
                            ->step('0.01'),
                        TextInput::make('collected_amount')
                            ->label('Terkumpul')
                            ->prefix('Rp')
                            ->numeric()
                            ->step('0.01')
                            ->default(0),
                        DateTimePicker::make('starts_at')
                            ->label('Mulai')
                            ->native(true)
                            ->seconds(false),
                        DateTimePicker::make('ends_at')
                            ->label('Berakhir')
                            ->native(true)
                            ->seconds(false),
                        DateTimePicker::make('published_at')
                            ->label('Tanggal Publikasi')
                            ->native(true)
                            ->seconds(false)
                            ->helperText('Otomatis terisi saat status Published, bisa diubah manual.'),
                    ]),

                Forms\Components\Section::make('Konten')
                    ->schema([
                        Textarea::make('summary')
                            ->label('Ringkasan')
                            ->rows(3)
                            ->maxLength(500),
                        RichEditor::make('content')
                            ->label('Deskripsi Lengkap')
                            ->toolbarButtons([
                                'bold','italic','underline','strike','blockquote','link','bulletList','orderedList','h2','h3','codeBlock'
                            ])
                            ->columnSpanFull(),
                        SpatieMediaLibraryFileUpload::make('cover')
                            ->label('Cover/Banner')
                            ->collection('cover')
                            ->image()
                            ->directory('programs/covers')
                            ->imageEditor()
                            ->maxSize(5 * 1024),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('rowIndex')->rowIndex()->label('No')->sortable(false),
                SpatieMediaLibraryImageColumn::make('cover')->collection('cover')->label('Cover')->circular()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('title')->label('Judul')->searchable()->sortable()->limit(40),
                TextColumn::make('category.name')->label('Kategori')->sortable()->badge(),
                TextColumn::make('status')->badge()->colors([
                    'secondary' => 'draft',
                    'success' => 'published',
                    'danger' => 'archived',
                ])->sortable(),
                IconColumn::make('is_featured')->label('Unggulan')->boolean(),
                TextColumn::make('target_amount')->label('Target')->money('idr', true),
                TextColumn::make('collected_amount')->label('Terkumpul')->money('idr', true),
                TextColumn::make('starts_at')->label('Mulai')->date('d M Y')->sortable(),
                TextColumn::make('ends_at')->label('Berakhir')->date('d M Y')->sortable(),
                TextColumn::make('published_at')->label('Publikasi')->dateTime('d M Y H:i'),
                TextColumn::make('user.name')->label('Dibuat oleh')->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')->label('Dibuat')->dateTime('d M Y H:i')->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('program_category_id')->relationship('category', 'name')->label('Kategori'),
                SelectFilter::make('status')->options([
                    'draft' => 'Draft',
                    'published' => 'Published',
                    'archived' => 'Archived',
                ]),
                TernaryFilter::make('is_featured')->label('Unggulan'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPrograms::route('/'),
            'create' => Pages\CreateProgram::route('/create'),
            'edit' => Pages\EditProgram::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
