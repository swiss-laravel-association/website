<?php

namespace App\Filament\Resources\Talks\Schemas;

use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TalkForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                MarkdownEditor::make('description')
                    ->columnSpanFull(),
                TextInput::make('recording_url')
                    ->url(),
            ]);
    }
}
