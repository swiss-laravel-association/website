<?php

namespace App\Mcp\Tools\Locations;

use App\Mcp\Tools\Concerns\AuthorizesToolCalls;
use App\Models\Location;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Name('list-locations')]
#[Description('List all meetup locations, ordered by name. Each location includes its numeric id and ulid.')]
#[IsReadOnly]
class ListLocationsTool extends Tool
{
    use AuthorizesToolCalls;

    public function handle(Request $request): Response
    {
        return $this->respond(function () use ($request): Response {
            $this->authorize($request, 'viewAny', Location::class);

            return $this->modelsResponse(Location::query()->orderBy('name')->get());
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
