<?php

namespace App\Mcp\Tools\Talks;

use App\Actions\Talks\DeleteTalk;
use App\Mcp\Tools\Concerns\AuthorizesToolCalls;
use App\Models\Talk;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;

#[Name('delete-talk')]
#[Description('Delete a talk by its ulid. It is removed from its events; its speakers keep existing.')]
#[IsDestructive]
class DeleteTalkTool extends Tool
{
    use AuthorizesToolCalls;

    public function handle(Request $request, DeleteTalk $deleteTalk): Response
    {
        return $this->respond(function () use ($request, $deleteTalk): Response {
            $validated = $request->validate(['ulid' => ['required', 'string']]);

            $talk = $this->findByUlid(Talk::class, $validated['ulid']);

            $this->authorize($request, 'delete', $talk);

            $deleteTalk->handle($talk);

            return $this->jsonResponse(['deleted' => true, 'id' => $talk->id, 'ulid' => $talk->ulid]);
        });
    }

    /**
     * @return array<string, Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'ulid' => $schema->string()->description('The ulid of the talk to delete.')->required(),
        ];
    }
}
