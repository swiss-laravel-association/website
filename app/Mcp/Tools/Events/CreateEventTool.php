<?php

namespace App\Mcp\Tools\Events;

use App\Actions\Events\CreateEvent;
use App\Http\Resources\EventResource;
use App\Mcp\Tools\Concerns\AuthorizesToolCalls;
use App\Mcp\Tools\Concerns\ValidatesEventInput;
use App\Models\Event;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tool;

#[Name('create-event')]
#[Description('Create a meetup event. The event is always created unpublished; use publish-event to publish it. Dates are ISO 8601 date-times. Without an offset (e.g. 2026-11-10T18:30:00) they are read as Europe/Zurich time; with an offset (e.g. 2026-11-10T17:30:00Z) they are converted to Europe/Zurich. end_date must be after start_date. Pass location_ulid to link a location (see list-locations or find-or-create-location).')]
class CreateEventTool extends Tool
{
    use AuthorizesToolCalls;
    use ValidatesEventInput;

    public function handle(Request $request, CreateEvent $createEvent): Response
    {
        return $this->respond(function () use ($request, $createEvent): Response {
            $this->authorize($request, 'create', Event::class);

            /** @var array{name: string, description: string, start_date: string, end_date: string, location_ulid?: string|null, meetup_link?: string|null} $validated */
            $validated = $request->validate($this->eventRules(partial: false), $this->eventMessages());

            /** @var array{name: string, description: string, start_date: string, end_date: string, location_id?: int|null, meetup_link?: string|null} $attributes */
            $attributes = $this->eventAttributes($validated);

            return $this->jsonResponse(EventResource::make($createEvent->handle($attributes)->refresh())->resolve());
        });
    }

    /**
     * @return array<string, Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'name' => $schema->string()->description('Name of the event, e.g. "Laravel Zürich Meetup November 2026".')->required(),
            'description' => $schema->string()->description('Public description of the event.')->required(),
            'start_date' => $schema->string()->description('Start as ISO 8601 date-time. Without an offset it is read as Europe/Zurich time.')->required(),
            'end_date' => $schema->string()->description('End as ISO 8601 date-time, after start_date. Without an offset it is read as Europe/Zurich time.')->required(),
            'location_ulid' => $schema->string()->description('The ulid of an existing location.'),
            'meetup_link' => $schema->string()->description('Link to the event on meetup.com, full URL.'),
        ];
    }
}
