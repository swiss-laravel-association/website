<?php

namespace App\Mcp\Tools\Events;

use App\Mcp\Tools\Concerns\AuthorizesToolCalls;
use App\Mcp\Tools\Concerns\SerializesMeetupContent;
use App\Models\Event;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Name('list-events')]
#[Description('List all meetup events, newest first, including unpublished ones. Each event includes its numeric id, ulid, is_published, public url, location_id and location_ulid, and the full location. start_date and end_date are ISO 8601 with the Europe/Zurich offset.')]
#[IsReadOnly]
class ListEventsTool extends Tool
{
    use AuthorizesToolCalls;
    use SerializesMeetupContent;

    public function handle(Request $request): Response
    {
        return $this->respond(function () use ($request): Response {
            $this->authorize($request, 'viewAny', Event::class);

            $events = Event::query()->with('location')->orderByDesc('start_date')->get();

            return $this->jsonResponse($events->map(fn (Event $event): array => $this->eventPayload($event))->all());
        });
    }

    /**
     * @return array<string, Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return [];
    }
}
