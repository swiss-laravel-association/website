<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PostForm
{
    public static function configure(Schema $schema): Schema
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
}
