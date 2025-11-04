<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TalkResource\Pages\CreateTalk;
use App\Filament\Resources\TalkResource\Pages\EditTalk;
use App\Filament\Resources\TalkResource\Pages\ListTalks;
use App\Models\Talk;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TalkResource extends Resource
{
    protected static ?string $model = Talk::class;

    protected static string|BackedEnum|null $navigationIcon = 'phosphor-lectern-duotone';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                MarkdownEditor::make('description')
                    ->columnSpanFull(),
                Select::make('speakers')
                    ->relationship('speakers', 'name')
                    ->multiple()
                    ->required(),
                TextInput::make('recording_url')
                    ->url(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title'),
                TextColumn::make('speakers.name'),
                TextColumn::make('events.name'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTalks::route('/'),
            'create' => CreateTalk::route('/create'),
            'edit' => EditTalk::route('/{record}/edit'),
        ];
    }
}
