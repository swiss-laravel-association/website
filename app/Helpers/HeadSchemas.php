<?php

namespace App\Helpers;

use App\Models\Event;
use App\Models\Speaker;
use App\Models\Talk;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Str;
use Laravel\Head\Facades\Schema;
use Laravel\Head\Schema\SchemaObject;

/**
 * Builds the JSON-LD objects for events, talks and speakers.
 *
 * Laravel Head rejects null and empty string values, so optional properties
 * are only set when a value exists.
 */
class HeadSchemas
{
    /**
     * The postal address and phone number are private, so they are left out of the JSON-LD.
     */
    public static function organization(): SchemaObject
    {
        return Schema::organization()
            ->name(config('sla.name'))
            ->url(config('sla.url'))
            ->logo(Vite::asset(config('sla.logo')))
            ->set('legalName', config('sla.legal_name'))
            ->date('foundingDate', Carbon::parse(config('sla.founding_date'))->startOfDay())
            ->set('description', config('sla.description'))
            ->set('email', config('sla.contact.email'))
            ->set('contactPoint', Schema::make('ContactPoint')
                ->set('email', config('sla.contact.email'))
                ->set('availableLanguage', Schema::make('Language')->set('name', 'English')))
            ->set('sameAs', array_values(config('sla.socials')));
    }

    /**
     * Pass $withTalks = false in listings to avoid loading talks and speakers for every event.
     */
    public static function event(Event $event, bool $withTalks = true): SchemaObject
    {
        $schema = Schema::make('Event')
            ->set('name', $event->name)
            ->set('url', $event->show_url)
            ->date('startDate', $event->start_date)
            ->date('endDate', $event->end_date)
            ->set('eventStatus', 'https://schema.org/EventScheduled')
            ->set('eventAttendanceMode', 'https://schema.org/OfflineEventAttendanceMode')
            ->set('image', asset('images/default-open-graph-image.webp'))
            ->set('organizer',
                Schema::organization()
                    ->name(config('sla.name'))
                    ->url(config('sla.url'))
            );

        if (filled($event->description)) {
            $schema->set('description', Str::limit($event->description, 300));
        }

        if ($event->location) {
            $address = Schema::make('PostalAddress')
                ->set('postalCode', $event->location->zip_code)
                ->set('addressLocality', $event->location->city)
                ->set('addressCountry', config('sla.address.country_code'));

            if (filled($event->location->address)) {
                $address->set('streetAddress', $event->location->address);
            }

            $schema->set('location',
                Schema::make('Place')
                    ->set('name', $event->location->name)
                    ->set('address', $address)
            );
        }

        if ($withTalks && $event->talks->isNotEmpty()) {
            $schema->set('subEvent',
                $event->talks
                    ->map(fn (Talk $talk): array => static::talkAsSubEvent($talk, $event)->toArray())
                    ->all()
            );

            $performers = static::speakersOf($event->talks);

            if ($performers->isNotEmpty()) {
                $schema->set('performer', $performers->map(fn (Speaker $speaker): array => static::person($speaker)->toArray())->all());
            }
        }

        return $schema;
    }

    public static function talk(Talk $talk): SchemaObject
    {
        $schema = Schema::make('CreativeWork')
            ->set('name', $talk->title)
            ->set('url', $talk->show_url);

        if (filled($talk->description)) {
            $schema->set('description', Str::limit($talk->description, 300));
        }

        if ($talk->speakers->isNotEmpty()) {
            $schema->set('author', $talk->speakers->map(fn (Speaker $speaker): array => static::person($speaker)->toArray())->all());
        }

        if ($talk->events->isNotEmpty()) {
            $schema->set('isPartOf',
                $talk->events
                    ->map(fn (Event $event): array => static::event($event, withTalks: false)->toArray())
                    ->all()
            );
        }

        return $schema;
    }

    public static function speaker(Speaker $speaker): SchemaObject
    {
        $schema = Schema::make('ProfilePage')
            ->set('url', $speaker->show_url)
            ->set('name', $speaker->name)
            ->set('mainEntity', static::person($speaker));

        if ($speaker->updated_at) {
            $schema->set('dateModified', $speaker->updated_at->toAtomString());
        }

        return $schema;
    }

    public static function person(Speaker $speaker): SchemaObject
    {
        $schema = Schema::person()
            ->set('name', $speaker->name)
            ->set('url', $speaker->show_url);

        if (filled($speaker->bio)) {
            $schema->set('description', Str::limit($speaker->bio, 300));
        }

        $sameAs = array_values(array_filter([
            $speaker->website,
            $speaker->github_profile,
            $speaker->x_profile,
            $speaker->linkedin_profile,
            $speaker->bluesky_profile,
            $speaker->youtube_profile,
        ]));

        if ($sameAs !== []) {
            $schema->set('sameAs', $sameAs);
        }

        return $schema;
    }

    /**
     * @param  iterable<int, Event>  $events
     */
    public static function eventList(iterable $events, string $name): SchemaObject
    {
        return static::itemList(
            $name,
            collect($events)->map(fn (Event $event): SchemaObject => static::event($event, withTalks: false)),
        );
    }

    /**
     * @param  iterable<int, Talk>  $talks
     */
    public static function talkList(iterable $talks, string $name): SchemaObject
    {
        return static::itemList(
            $name,
            collect($talks)->map(fn (Talk $talk): SchemaObject => Schema::make('CreativeWork')
                ->set('name', $talk->title)
                ->set('url', $talk->show_url)),
        );
    }

    /**
     * @param  Collection<int, SchemaObject>  $items
     */
    protected static function itemList(string $name, Collection $items): SchemaObject
    {
        return Schema::make('ItemList')
            ->set('name', $name)
            ->set('numberOfItems', $items->count())
            ->set('itemListElement',
                $items->values()
                    ->map(fn (SchemaObject $item, int $index): array => Schema::make('ListItem')
                        ->set('position', $index + 1)
                        ->set('item', $item)
                        ->toArray())
                    ->all()
            );
    }

    protected static function talkAsSubEvent(Talk $talk, Event $event): SchemaObject
    {
        $schema = Schema::make('Event')
            ->set('name', $talk->title)
            ->set('url', $talk->show_url)
            ->date('startDate', $event->start_date)
            ->date('endDate', $event->end_date);

        if (filled($talk->description)) {
            $schema->set('description', Str::limit($talk->description, 300));
        }

        if ($talk->speakers->isNotEmpty()) {
            $schema->set('performer', $talk->speakers->map(fn (Speaker $speaker): array => static::person($speaker)->toArray())->all());
        }

        return $schema;
    }

    /**
     * @param  Collection<int, Talk>  $talks
     * @return Collection<int, Speaker>
     */
    protected static function speakersOf(Collection $talks): Collection
    {
        return $talks->flatMap(fn (Talk $talk) => $talk->speakers)->unique('id')->values();
    }
}
