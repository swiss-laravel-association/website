<?php

namespace App\Http\Resources;

use App\Models\Speaker;
use App\Models\Talk;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Talk attributes plus the public `url`. `speakers` and `events` appear only when those
 * relations are loaded, without pivot data.
 *
 * @mixin Talk
 */
class TalkResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            ...$this->resource->attributesToArray(),
            'url' => $this->show_url,
            'speakers' => $this->whenLoaded('speakers', fn (): array => $this->speakers
                ->map(fn (Speaker $speaker): array => $speaker->attributesToArray())
                ->all()),
            'events' => EventResource::collection($this->whenLoaded('events')),
        ];
    }
}
