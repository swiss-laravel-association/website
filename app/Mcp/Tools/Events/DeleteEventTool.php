<?php

namespace App\Mcp\Tools\Events;

use App\Actions\Events\DeleteEvent;
use App\Mcp\Tools\Concerns\AuthorizesToolCalls;
use App\Models\Event;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;

#[Name('delete-event')]
#[Description('Delete a meetup event by its ulid. Its talks keep existing but are no longer linked to the event.')]
#[IsDestructive]
class DeleteEventTool extends Tool
{
    use AuthorizesToolCalls;

    public function handle(Request $request, DeleteEvent $deleteEvent): Response
    {
        return $this->respond(function () use ($request, $deleteEvent): Response {
            $validated = $request->validate(['ulid' => ['required', 'string']]);

            $event = $this->findByUlid(Event::class, $validated['ulid']);

            $this->authorize($request, 'delete', $event);

            $deleteEvent->handle($event);

            return $this->jsonResponse(['deleted' => true, 'id' => $event->id, 'ulid' => $event->ulid]);
        });
    }

    /**
     * @return array<string, Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'ulid' => $schema->string()->description('The ulid of the event to delete.')->required(),
        ];
    }
}
