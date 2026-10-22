<?php

namespace App\Mcp\Tools\Speakers;

use App\Mcp\Tools\Concerns\AuthorizesToolCalls;
use App\Models\Speaker;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Name('get-speaker')]
#[Description('Get a single speaker by its ulid.')]
#[IsReadOnly]
class GetSpeakerTool extends Tool
{
    use AuthorizesToolCalls;

    public function handle(Request $request): Response
    {
        return $this->respond(function () use ($request): Response {
            $validated = $request->validate(['ulid' => ['required', 'string']]);

            $speaker = $this->findByUlid(Speaker::class, $validated['ulid']);

            $this->authorize($request, 'view', $speaker);

            return $this->modelResponse($speaker);
        });
    }

    /**
     * @return array<string, Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'ulid' => $schema->string()->description('The ulid of the speaker.')->required(),
        ];
    }
}
