<?php

namespace App\Filament\Resources\Posts;

use App\Filament\Resources\Posts\Pages\CreatePost;
use App\Filament\Resources\Posts\Pages\EditPost;
use App\Filament\Resources\Posts\Pages\ListPosts;
use App\Filament\Resources\Posts\Pages\ViewPost;
use App\Models\Post;
use App\Models\User;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static string|BackedEnum|null $navigationIcon = 'phosphor-newspaper-duotone';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns([
                'sm' => 3,
                'xl' => 12,
            ])
            ->components([
                Section::make()
                    ->schema([
                        TextInput::make('title')
                            ->required(),
                        MarkdownEditor::make('content')
                            ->required()
                            ->columnSpanFull(),
                    ])->columnSpan(8),
                Section::make()
                    ->heading('Metadata')
                    ->schema([
                        MarkdownEditor::make('excerpt')
                            ->required()
                            ->columnSpanFull(),
                        DateTimePicker::make('published_at'),
                        Select::make('authors')
                            ->required()
                            ->multiple()
                            ->relationship('authors', 'name')
                            ->options(fn () => User::pluck('name', 'id')),
                    ])->columnSpan(4),
            ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->columns([
                'sm' => 3,
                'xl' => 12,
            ])
            ->components([
                Section::make()->schema([
                    TextEntry::make('title'),
                    TextEntry::make('content')
                        ->prose()
                        ->formatStateUsing(fn ($state) => Str::markdown($state)),
                ])->columnSpan(8),
                Section::make()->schema([
                    TextEntry::make('excerpt'),
                    TextEntry::make('published_at')
                        ->dateTime('d.m.Y H:i'),
                    TextEntry::make('authors.name')
                        ->label('Authors')
                        ->listWithLineBreaks()
                        ->bulleted(),
                ])->columnSpan(4),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable(),
                TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('authors.name')
                    ->label('Authors')
                    ->bulleted(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('published_at', 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPosts::route('/'),
            'create' => CreatePost::route('/create'),
            'view' => ViewPost::route('/{record}'),
            'edit' => EditPost::route('/{record}/edit'),
        ];
    }
}
