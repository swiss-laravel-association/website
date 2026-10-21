<?php

namespace App\Mcp\Tools\Events;

use App\Actions\Events\UpdateEvent;
use App\Mcp\Tools\Concerns\AuthorizesToolCalls;
use App\Mcp\Tools\Concerns\SerializesMeetupContent;
use App\Mcp\Tools\Concerns\ValidatesEventInput;
use App\Models\Event;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;

#[Name('update-event')]
#[Description('Update a meetup event by its ulid. Only the fields you pass are changed; use publish-event and unpublish-event to change visibility. Dates are ISO 8601 date-times. Without an offset they are read as Europe/Zurich time; with an offset they are converted to Europe/Zurich. end_date must be after start_date, also when you pass only one of them. Pass location_ulid as null to remove the location.')]
#[IsIdempotent]
class UpdateEventTool extends Tool
{
    use AuthorizesToolCalls;
    use SerializesMeetupContent;
    use ValidatesEventInput;

    public function handle(Request $request, UpdateEvent $updateEvent): Response
    {
        return $this->respond(function () use ($request, $updateEvent): Response {
            $validated = $request->validate(['ulid' => ['required', 'string']]);

            $event = $this->findByUlid(Event::class, $validated['ulid']);

            $this->authorize($request, 'update', $event);

            /** @var array{name?: string, description?: string, start_date?: string, end_date?: string, location_ulid?: string|null, meetup_link?: string|null} $input */
            $input = $request->validate($this->eventRules(partial: true), $this->eventMessages());

            return $this->jsonResponse($this->eventPayload($updateEvent->handle($event, $this->eventAttributes($input, $event))));
        });
    }

    /**
     * @return array<string, Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'ulid' => $schema->string()->description('The ulid of the event to update.')->required(),
            'name' => $schema->string()->description('Name of the event.'),
            'description' => $schema->string()->description('Public description of the event.'),
            'start_date' => $schema->string()->description('Start as ISO 8601 date-time. Without an offset it is read as Europe/Zurich time.'),
            'end_date' => $schema->string()->description('End as ISO 8601 date-time, after start_date. Without an offset it is read as Europe/Zurich time.'),
            'location_ulid' => $schema->string()->nullable()->description('The ulid of an existing location, or null to remove the location.'),
            'meetup_link' => $schema->string()->description('Link to the event on meetup.com, full URL.'),
        ];
    }
}
