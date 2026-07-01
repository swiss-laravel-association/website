<?php

namespace App\Filament\Resources\Locations\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LocationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Name')
                    ->required(),
                TextInput::make('address'),
                TextInput::make('zip_code')
                    ->required(),
                TextInput::make('city')
                    ->required(),
                Textarea::make('description'),
                Textarea::make('notes')
                    ->label('Internal notes'),
                TextInput::make('capacity')
                    ->label('Max capacity')
                    ->numeric(),
            ]);
    }
}
