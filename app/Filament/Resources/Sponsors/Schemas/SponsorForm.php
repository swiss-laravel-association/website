<?php

namespace App\Filament\Resources\Sponsors\Schemas;

use App\Enums\SponsorType;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SponsorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('website')
                    ->required(),
                Select::make('type')
                    ->options(SponsorType::class)
                    ->required(),
                SpatieMediaLibraryFileUpload::make('logo')
                    ->disk('public')
                    ->collection('logo'),
                ColorPicker::make('background_color'),
            ]);
    }
}
