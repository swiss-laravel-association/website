<?php

namespace App\Mcp\Tools\Concerns;

use App\Models\Event;
use App\Models\Speaker;
use App\Models\Talk;
use Carbon\CarbonImmutable;

/**
 * Turns events, talks and speakers into arrays for tool responses. Loaded relations are
 * included (without pivot data), and events and talks get their public `url`.
 */
trait SerializesMeetupContent
{
    /**
     * @return array<string, mixed>
     */
    protected function eventPayload(Event $event): array
    {
        $payload = [
            ...$event->attributesToArray(),
            'start_date' => $this->zurichDateString($event->start_date),
            'end_date' => $this->zurichDateString($event->end_date),
            'url' => $event->show_url,
        ];

        if ($event->relationLoaded('location')) {
            $payload['location'] = $event->location?->attributesToArray();
        }

        if ($event->relationLoaded('talks')) {
            $payload['talks'] = $event->talks->map(fn (Talk $talk): array => $this->talkPayload($talk))->all();
        }

        return $payload;
    }

    /**
     * @return array<string, mixed>
     */
    protected function talkPayload(Talk $talk): array
    {
        $payload = [...$talk->attributesToArray(), 'url' => $talk->show_url];

        if ($talk->relationLoaded('speakers')) {
            $payload['speakers'] = $talk->speakers->map(fn (Speaker $speaker): array => $speaker->attributesToArray())->all();
        }

        if ($talk->relationLoaded('events')) {
            $payload['events'] = $talk->events->map(fn (Event $event): array => $this->eventPayload($event))->all();
        }

        return $payload;
    }

    /**
     * Event dates are stored as Zurich wall-clock time in a column without a time zone
     * (the iCal feed reads them the same way), so attach the Zurich offset on output.
     */
    private function zurichDateString(CarbonImmutable $date): string
    {
        return $date->shiftTimezone('Europe/Zurich')->toIso8601String();
    }
}
