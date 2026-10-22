<?php

namespace App\Http\Resources;

use App\Models\Event;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Event attributes plus the public `url` and `location_ulid`. Event dates are stored as
 * Zurich wall-clock time in a column without a time zone (the iCal feed reads them the
 * same way), so the Zurich offset is attached on output. `location` and `talks` appear
 * only when those relations are loaded.
 *
 * @mixin Event
 */
class EventResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            ...$this->resource->attributesToArray(),
            'start_date' => $this->start_date->shiftTimezone('Europe/Zurich')->toIso8601String(),
            'end_date' => $this->end_date->shiftTimezone('Europe/Zurich')->toIso8601String(),
            'url' => $this->show_url,
            'location_ulid' => $this->relationLoaded('location')
                ? $this->location?->ulid
                : Location::query()->whereKey($this->location_id)->value('ulid'),
            'location' => $this->whenLoaded('location', fn (): ?array => $this->location?->attributesToArray()),
            'talks' => TalkResource::collection($this->whenLoaded('talks')),
        ];
    }
}
