<?php

namespace App\Mcp\Tools\Linking;

use App\Actions\Talks\AttachTalkToEvent;
use App\Mcp\Tools\Concerns\AuthorizesToolCalls;
use App\Models\Event;
use App\Models\Talk;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;

#[Name('attach-talk-to-event')]
#[Description('Add a talk to an event. Both are addressed by their ulid. Adding a talk that is already part of the event changes nothing. Needs the events:update ability.')]
#[IsIdempotent]
class AttachTalkToEventTool extends Tool
{
    use AuthorizesToolCalls;

    public function handle(Request $request, AttachTalkToEvent $attachTalkToEvent): Response
    {
        return $this->respond(function () use ($request, $attachTalkToEvent): Response {
            $validated = $request->validate([
                'talk_ulid' => ['required', 'string'],
                'event_ulid' => ['required', 'string'],
            ]);

            $talk = $this->findByUlid(Talk::class, $validated['talk_ulid']);
            $event = $this->findByUlid(Event::class, $validated['event_ulid']);

            $this->authorize($request, 'update', $event);

            $attachTalkToEvent->handle($talk, $event);

            return $this->jsonResponse(['attached' => true, 'talk_ulid' => $talk->ulid, 'event_ulid' => $event->ulid]);
        });
    }

    /**
     * @return array<string, Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'talk_ulid' => $schema->string()->description('The ulid of the talk.')->required(),
            'event_ulid' => $schema->string()->description('The ulid of the event.')->required(),
        ];
    }
}
