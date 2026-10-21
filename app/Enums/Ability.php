<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Ability: string implements HasLabel
{
    case EventsView = 'events:view';
    case EventsCreate = 'events:create';
    case EventsUpdate = 'events:update';
    case EventsDelete = 'events:delete';
    case EventsPublish = 'events:publish';
    case TalksView = 'talks:view';
    case TalksCreate = 'talks:create';
    case TalksUpdate = 'talks:update';
    case TalksDelete = 'talks:delete';
    case SpeakersView = 'speakers:view';
    case SpeakersCreate = 'speakers:create';
    case SpeakersUpdate = 'speakers:update';
    case SpeakersDelete = 'speakers:delete';
    case LocationsView = 'locations:view';
    case LocationsCreate = 'locations:create';
    case LocationsUpdate = 'locations:update';
    case LocationsDelete = 'locations:delete';
    case SponsorsView = 'sponsors:view';
    case SponsorsCreate = 'sponsors:create';
    case SponsorsUpdate = 'sponsors:update';
    case SponsorsDelete = 'sponsors:delete';
    case PostsView = 'posts:view';
    case PostsCreate = 'posts:create';
    case PostsUpdate = 'posts:update';
    case PostsDelete = 'posts:delete';
    case PostsPublish = 'posts:publish';

    public function getLabel(): string
    {
        return match ($this) {
            self::EventsView => 'View events',
            self::EventsCreate => 'Create events',
            self::EventsUpdate => 'Update events',
            self::EventsDelete => 'Delete events',
            self::EventsPublish => 'Publish events',
            self::TalksView => 'View talks',
            self::TalksCreate => 'Create talks',
            self::TalksUpdate => 'Update talks',
            self::TalksDelete => 'Delete talks',
            self::SpeakersView => 'View speakers',
            self::SpeakersCreate => 'Create speakers',
            self::SpeakersUpdate => 'Update speakers',
            self::SpeakersDelete => 'Delete speakers',
            self::LocationsView => 'View locations',
            self::LocationsCreate => 'Create locations',
            self::LocationsUpdate => 'Update locations',
            self::LocationsDelete => 'Delete locations',
            self::SponsorsView => 'View sponsors',
            self::SponsorsCreate => 'Create sponsors',
            self::SponsorsUpdate => 'Update sponsors',
            self::SponsorsDelete => 'Delete sponsors',
            self::PostsView => 'View posts',
            self::PostsCreate => 'Create posts',
            self::PostsUpdate => 'Update posts',
            self::PostsDelete => 'Delete posts',
            self::PostsPublish => 'Publish posts',
        };
    }

    /**
     * @return array<string, array<string, string>>
     */
    public static function groupedByResource(): array
    {
        $grouped = [];

        foreach (self::cases() as $ability) {
            $resource = ucfirst(explode(':', $ability->value)[0]);
            $grouped[$resource][$ability->value] = $ability->getLabel();
        }

        return $grouped;
    }
}
