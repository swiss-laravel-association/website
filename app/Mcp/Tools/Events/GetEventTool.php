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

#[Name('get-event')]
#[Description('Get a single meetup event by its ulid, with its location, its talks and each talk\'s speakers. start_date and end_date are ISO 8601 with the Europe/Zurich offset.')]
#[IsReadOnly]
class GetEventTool extends Tool
{
    use AuthorizesToolCalls;
    use SerializesMeetupContent;

    public function handle(Request $request): Response
    {
        return $this->respond(function () use ($request): Response {
            $validated = $request->validate(['ulid' => ['required', 'string']]);

            $event = $this->findByUlid(Event::class, $validated['ulid']);

            $this->authorize($request, 'view', $event);

            return $this->jsonResponse($this->eventPayload($event->load(['location', 'talks.speakers'])));
        });
    }

    /**
     * @return array<string, Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'ulid' => $schema->string()->description('The ulid of the event.')->required(),
        ];
    }
}
