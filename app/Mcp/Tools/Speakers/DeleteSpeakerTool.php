<?php

namespace App\Mcp\Tools\Speakers;

use App\Actions\Speakers\DeleteSpeaker;
use App\Mcp\Tools\Concerns\AuthorizesToolCalls;
use App\Models\Speaker;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;

#[Name('delete-speaker')]
#[Description('Delete a speaker by its ulid. The speaker is detached from its talks; the talks keep existing.')]
#[IsDestructive]
class DeleteSpeakerTool extends Tool
{
    use AuthorizesToolCalls;

    public function handle(Request $request, DeleteSpeaker $deleteSpeaker): Response
    {
        return $this->respond(function () use ($request, $deleteSpeaker): Response {
            $validated = $request->validate(['ulid' => ['required', 'string']]);

            $speaker = $this->findByUlid(Speaker::class, $validated['ulid']);

            $this->authorize($request, 'delete', $speaker);

            $deleteSpeaker->handle($speaker);

            return $this->jsonResponse(['deleted' => true, 'id' => $speaker->id, 'ulid' => $speaker->ulid]);
        });
    }

    /**
     * @return array<string, Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'ulid' => $schema->string()->description('The ulid of the speaker to delete.')->required(),
        ];
    }
}
