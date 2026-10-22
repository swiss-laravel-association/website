<?php

namespace App\Mcp\Tools\Talks;

use App\Http\Resources\TalkResource;
use App\Mcp\Tools\Concerns\AuthorizesToolCalls;
use App\Models\Talk;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Name('list-talks')]
#[Description('List all talks, ordered by title. Each talk includes its numeric id, ulid and public url.')]
#[IsReadOnly]
class ListTalksTool extends Tool
{
    use AuthorizesToolCalls;

    public function handle(Request $request): Response
    {
        return $this->respond(function () use ($request): Response {
            $this->authorize($request, 'viewAny', Talk::class);

            $talks = Talk::query()->orderBy('title')->get();

            return $this->jsonResponse(TalkResource::collection($talks)->resolve());
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
