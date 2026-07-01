<?php

namespace App\Filament\Resources\Events\Tables;

use App\Models\Event;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('location.name')
                    ->searchable(),
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
                TernaryFilter::make('is_published')
                    ->label('Published'),
                Filter::make('start_date')
                    ->schema([
                        DatePicker::make('start_from')
                            ->label('Start from'),
                        DatePicker::make('start_until')
                            ->label('Start until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['start_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('start_date', '>=', $date),
                            )
                            ->when(
                                $data['start_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('start_date', '<=', $date),
                            );
                    }),
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
