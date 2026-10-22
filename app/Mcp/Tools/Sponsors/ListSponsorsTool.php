<?php

namespace App\Mcp\Tools\Sponsors;

use App\Mcp\Tools\Concerns\AuthorizesToolCalls;
use App\Models\Sponsor;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Name('list-sponsors')]
#[Description('List all sponsors. Each sponsor includes its numeric id and ulid.')]
#[IsReadOnly]
class ListSponsorsTool extends Tool
{
    use AuthorizesToolCalls;

    public function handle(Request $request): Response
    {
        return $this->respond(function () use ($request): Response {
            $this->authorize($request, 'viewAny', Sponsor::class);

            return $this->modelsResponse(Sponsor::query()->orderBy('order')->orderBy('name')->get());
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
