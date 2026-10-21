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

#[Name('list-speakers')]
#[Description('List all speakers. Each speaker includes its numeric id and ulid.')]
#[IsReadOnly]
class ListSpeakersTool extends Tool
{
    use AuthorizesToolCalls;

    public function handle(Request $request): Response
    {
        return $this->respond(function () use ($request): Response {
            $this->authorize($request, 'viewAny', Speaker::class);

            return $this->modelsResponse(Speaker::query()->orderBy('name')->get());
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
