<?php

namespace App\Filament\Resources\Posts\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostInfolist
{
    public static function configure(Schema $schema): Schema
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
}
