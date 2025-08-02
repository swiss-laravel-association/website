<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages\CreatePost;
use App\Filament\Resources\PostResource\Pages\EditPost;
use App\Filament\Resources\PostResource\Pages\ListPosts;
use App\Filament\Resources\PostResource\Pages\ViewPost;
use App\Models\Post;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'phosphor-newspaper-duotone';

    public static function form(Form $form): Form
    {
        return $form
            ->columns([
                'sm' => 3,
                'xl' => 12,
            ])
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        TextInput::make('title')
                            ->required(),
                        MarkdownEditor::make('content')
                            ->required()
                            ->columnSpanFull(),
                    ])->columnSpan(8),
                Forms\Components\Section::make()
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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->columns([
                'sm' => 3,
                'xl' => 12,
            ])
            ->schema([
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
            ->actions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->bulkActions([
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
