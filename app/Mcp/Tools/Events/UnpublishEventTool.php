<?php

namespace App\Mcp\Tools\Events;

use App\Actions\Events\UnpublishEvent;
use App\Http\Resources\EventResource;
use App\Mcp\Tools\Concerns\AuthorizesToolCalls;
use App\Models\Event;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;

#[Name('unpublish-event')]
#[Description('Unpublish a meetup event by its ulid so it no longer appears on the website and in the iCal feed. Needs the events:publish ability.')]
#[IsIdempotent]
class UnpublishEventTool extends Tool
{
    use AuthorizesToolCalls;

    public function handle(Request $request, UnpublishEvent $unpublishEvent): Response
    {
        return $this->respond(function () use ($request, $unpublishEvent): Response {
            $validated = $request->validate(['ulid' => ['required', 'string']]);

            $event = $this->findByUlid(Event::class, $validated['ulid']);

            $this->authorize($request, 'publish', $event);

            return $this->jsonResponse(EventResource::make($unpublishEvent->handle($event))->resolve());
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
