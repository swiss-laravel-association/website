<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Events\EventResource;
use App\Models\Event;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class PastEventsWidget extends TableWidget
{
    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Past Events')
            ->query(
                Event::query()
                    ->past()
                    ->orderBy('start_date', 'desc')
                    ->limit(3)
            )
            ->paginated(false)
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('start_date')
                    ->dateTime('Y-m-d H:i'),
                TextColumn::make('location.name')
                    ->label('Location'),
            ])
            ->recordUrl(fn (Event $record): string => EventResource::getUrl('edit', ['record' => $record], configuration: 'all'))
            ->emptyStateHeading('No past events');
    }
}
