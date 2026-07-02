<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Events\EventResource;
use App\Models\Event;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class UpcomingEventsWidget extends TableWidget
{
    protected static ?int $sort = 1;

    public function table(Table $table): Table
    {
        return $table
            ->heading('Upcoming Events')
            ->query(
                Event::query()
                    ->upcoming()
                    ->orderBy('start_date')
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
            ->emptyStateHeading('No upcoming events');
    }
}
