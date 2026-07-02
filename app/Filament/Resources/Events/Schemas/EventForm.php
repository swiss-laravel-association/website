<?php

namespace App\Filament\Resources\Events\Schemas;

use App\Filament\Resources\Locations\Schemas\LocationForm;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Name')
                    ->required(),
                Select::make('location_id')
                    ->label('Location')
                    ->relationship('location', 'name')
                    ->searchable()
                    ->preload()
                    ->createOptionForm(fn (Schema $schema): Schema => LocationForm::configure($schema)),
                DateTimePicker::make('start_date')
                    ->label('Start Date')
                    ->required(),
                DateTimePicker::make('end_date')
                    ->label('End Date')
                    ->required(),
                Textarea::make('description')
                    ->label('Description')
                    ->autosize()
                    ->required(),
                TextInput::make('meetup_link')
                    ->label('Meetup Link'),
                Checkbox::make('is_published')
                    ->label('Is Published')
                    ->default(false),
            ]);
    }
}
