<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Tag;
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
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationGroup = 'Konten';

    protected static ?string $navigationLabel = 'Artikel & Berita';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Hidden::make('user_id')->default(fn () => auth()->id())->dehydrated(),
            Forms\Components\Section::make('Artikel')
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
                        ->unique(ignoreRecord: true)
                        ->maxLength(255)
                        ->readonly(),
                    Select::make('post_category_id')
                        ->relationship('category', 'name')
                        ->label('Kategori')
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
                                ->unique(table: PostCategory::class, column: 'slug')
                                ->maxLength(255)
                                ->readonly(),
                            Textarea::make('description')->label('Deskripsi')->rows(2),
                            TextInput::make('sort_order')->label('Urutan')->numeric()->default(0),
                        ])
                        ->createOptionUsing(function (array $data): int {
                            return PostCategory::create([
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
                        ->label('Unggulan')
                        ->default(false),
                    Textarea::make('excerpt')
                        ->label('Ringkasan')
                        ->rows(3)
                        ->columnSpanFull(),
                    RichEditor::make('content')
                        ->label('Konten')
                        ->columnSpanFull(),
                    Select::make('tags')
                        ->label('Tag')
                        ->multiple()
                        ->relationship('tags', 'name')
                        ->preload()
                        ->searchable()
                        ->createOptionForm([
                            TextInput::make('name')
                                ->label('Nama Tag')
                                ->required()
                                ->maxLength(255)
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                            TextInput::make('slug')
                                ->label('Slug')
                                ->required()
                                ->unique(table: Tag::class, column: 'slug')
                                ->maxLength(255)
                                ->readonly(),
                        ]),
                    DateTimePicker::make('published_at')
                        ->label('Tanggal Publikasi')
                        ->native(true)
                        ->seconds(false),
                ]),
            Forms\Components\Section::make('Media')
                ->schema([
                    SpatieMediaLibraryFileUpload::make('cover')
                        ->label('Cover')
                        ->collection('cover')
                        ->image()
                        ->directory('posts/covers')
                        ->imageEditor()
                        ->maxSize(5 * 1024),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('cover')->collection('cover')->label('Cover')->square()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('title')->label('Judul')->searchable()->sortable()->limit(40),
                TextColumn::make('category.name')->label('Kategori')->sortable()->badge(),
                TextColumn::make('status')->badge()->colors([
                    'secondary' => 'draft',
                    'success' => 'published',
                ])->sortable(),
                IconColumn::make('is_featured')->label('Unggulan')->boolean(),
                TextColumn::make('published_at')->label('Publikasi')->dateTime('d M Y H:i')->sortable(),
                TextColumn::make('author.name')->label('Penulis')->searchable()->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')->label('Dibuat')->dateTime('d M Y H:i')->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('post_category_id')->relationship('category', 'name')->label('Kategori'),
                SelectFilter::make('status')->options([
                    'draft' => 'Draft',
                    'published' => 'Published',
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
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }
}
