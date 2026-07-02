<?php

namespace App\Filament\Resources\Talks\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TalksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('speakers.name')
                    ->searchable(),
                TextColumn::make('events.name')
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('speakers')
                    ->label('Speakers')
                    ->multiple()
                    ->relationship('speakers', 'name')
                    ->preload(),
                SelectFilter::make('events')
                    ->label('Events')
                    ->multiple()
                    ->relationship('events', 'name')
                    ->preload(),
                TernaryFilter::make('recording_url')
                    ->label('Recording available')
                    ->placeholder('All talks')
                    ->trueLabel('With recording')
                    ->falseLabel('Without recording')
                    ->queries(
                        true: fn (Builder $query): Builder => $query->whereNotNull('recording_url'),
                        false: fn (Builder $query): Builder => $query->whereNull('recording_url'),
                        blank: fn (Builder $query): Builder => $query,
                    ),
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
