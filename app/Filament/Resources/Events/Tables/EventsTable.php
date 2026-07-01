<?php

namespace App\Filament\Resources\Events\Tables;

use App\Models\Event;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('location.name'),
                TextColumn::make('start_date')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
                TextColumn::make('talks_count')
                    ->label('Talks')
                    ->state(fn (Event $event): int => $event->talks->count()),
                IconColumn::make('is_published')
                    ->boolean(),
            ])
            ->defaultSort('start_date', 'desc')
            ->filters([
                SelectFilter::make('location.name')
                    ->label('Location')
                    ->multiple()
                    ->relationship('location', 'name')
                    ->preload(),
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
}
