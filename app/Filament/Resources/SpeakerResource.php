<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SpeakerResource\Pages\CreateSpeaker;
use App\Filament\Resources\SpeakerResource\Pages\EditSpeaker;
use App\Filament\Resources\SpeakerResource\Pages\ListSpeakers;
use App\Models\Speaker;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SpeakerResource extends Resource
{
    protected static ?string $model = Speaker::class;

    protected static string|BackedEnum|null $navigationIcon = 'phosphor-user-sound-duotone';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Textarea::make('bio')
                    ->columnSpanFull()
                    ->autosize(),
                TextInput::make('website'),
                TextInput::make('github_profile'),
                TextInput::make('x_profile'),
                TextInput::make('linkedin_profile'),
                TextInput::make('bluesky_profile'),
                TextInput::make('youtube_profile'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
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
            'index' => ListSpeakers::route('/'),
            'create' => CreateSpeaker::route('/create'),
            'edit' => EditSpeaker::route('/{record}/edit'),
        ];
    }
}
