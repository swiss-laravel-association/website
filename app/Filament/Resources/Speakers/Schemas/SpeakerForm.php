<?php

namespace App\Filament\Resources\Speakers\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SpeakerForm
{
    public static function configure(Schema $schema): Schema
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
}
