<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Filament\Resources\EventResource\RelationManagers;
use App\Models\Event;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Name')
                    ->maxLength(255)
                    ->required(),
                Select::make('event_type_id')
                    ->relationship('eventType', 'name', fn (Builder $query) => $query->orderBy('sort_order')),
                DateTimePicker::make('start_date')
                    ->label('Start Date')
                    ->live()
                    ->firstDayOfWeek(1)
                    ->afterStateUpdated(function (?string $state, ?string $old, Set $set, Get $get) {
                        return $set('end_date', $get('end_date') ?: Carbon::parse($state)->addHours(2)->toDateTimeString());
                    })
                    ->required(),
                DateTimePicker::make('end_date')
                    ->label('End Date')
                    ->firstDayOfWeek(1)
                    ->required(),
                Select::make('location_id')
                    ->relationship(name: 'location', titleAttribute: 'name')
                    ->label('Location'),
                TextInput::make('location')
                    ->label('Location (add-on)'),
                Textarea::make('description')
                    ->label('Description')
                    ->autosize()
                    ->required(),
                TextInput::make('meetup_link')
                    ->label('Meetup Link')
                    ->url(),
                SpatieMediaLibraryFileUpload::make('cover')
                    ->label('Cover image')
                    ->collection('event-cover-image'),
                SpatieMediaLibraryFileUpload::make('photos')
                    ->multiple()
                    ->collection('events-cover-images')
                    ->reorderable(),
                Select::make('user_id')
                    ->relationship('user', 'name', fn (Builder $query) => $query->where('is_admin', true))
                    ->searchable()
                    ->preload()
                    ->label('User in charge'),
                Toggle::make('is_published')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->limit(20)
                    ->searchable(),
                TextColumn::make('location.city')
                    ->searchable(),
                TextColumn::make('start_date')
                    ->formatStateUsing(fn (CarbonImmutable $state) => $state->format('Y-m-d H:i'))
                    ->sortable(),
                TextColumn::make('eventType.name'),
                TextColumn::make('talks_count')
                    ->label('Talks')
                    ->counts('talks'),
                TextColumn::make('user.name')
                    ->label('In charge'),
                Tables\Columns\IconColumn::make('is_published')
                    ->label('Published')
                    ->boolean(),
            ])
            ->defaultSort('start_date')
            ->filters([
                SelectFilter::make('eventType')
                    ->relationship('eventType', 'name'),
                SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->label('In charge'),
                Filter::make('is_published')
                    ->query(fn (Builder $query): Builder => $query->where('is_published', true))
                    ->toggle(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\TalksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
