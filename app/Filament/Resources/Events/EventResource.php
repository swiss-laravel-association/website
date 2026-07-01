<?php

namespace App\Filament\Resources\Events;

use App\Filament\Resources\Events\Pages\CreateEvent;
use App\Filament\Resources\Events\Pages\EditEvent;
use App\Filament\Resources\Events\Pages\ListEvents;
use App\Filament\Resources\Events\RelationManagers\TalksRelationManager;
use App\Filament\Resources\Events\Schemas\EventForm;
use App\Filament\Resources\Events\Tables\EventsTable;
use App\Models\Event;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $configurationClass = EventResourceConfiguration::class;

    /**
     * Registered explicitly as "All", "Upcoming" and "Past" configurations in
     * the panel provider, so the plain registration must be excluded from
     * auto-discovery to avoid a duplicate (unscoped) navigation item.
     */
    protected static bool $isDiscovered = false;

    protected static string|BackedEnum|null $navigationIcon = 'phosphor-calendar-dots-duotone';

    protected static string|UnitEnum|null $navigationGroup = 'Events';

    protected static ?string $navigationLabel = 'All Events';

    protected static ?string $recordTitleAttribute = 'name';

    /**
     * @return array<int, string>
     */
    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'location.name'];
    }

    public static function form(Schema $schema): Schema
    {
        return EventForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EventsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $configuration = static::getConfiguration();

        if ($configuration instanceof EventResourceConfiguration) {
            if ($configuration->getTimeframe() === 'upcoming') {
                $query->where('start_date', '>', now());
            } elseif ($configuration->getTimeframe() === 'past') {
                $query->where('start_date', '<', now());
            }
        }

        return $query;
    }

    public static function getNavigationLabel(): string
    {
        $configuration = static::getConfiguration();

        if ($configuration instanceof EventResourceConfiguration && $configuration->getNavigationLabel()) {
            return $configuration->getNavigationLabel();
        }

        return parent::getNavigationLabel();
    }

    public static function getNavigationSort(): ?int
    {
        $configuration = static::getConfiguration();

        if ($configuration instanceof EventResourceConfiguration && $configuration->getNavigationSort() !== null) {
            return $configuration->getNavigationSort();
        }

        return parent::getNavigationSort();
    }

    public static function getRelations(): array
    {
        return [
            TalksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListEvents::route('/'),
            'create' => CreateEvent::route('/create'),
            'edit' => EditEvent::route('/{record}/edit'),
        ];
    }
}
