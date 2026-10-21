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

#[Name('get-location')]
#[Description('Get a single meetup location by its ulid.')]
#[IsReadOnly]
class GetLocationTool extends Tool
{
    use AuthorizesToolCalls;

    public function handle(Request $request): Response
    {
        return $this->respond(function () use ($request): Response {
            $validated = $request->validate(['ulid' => ['required', 'string']]);

            $location = $this->findByUlid(Location::class, $validated['ulid']);

            $this->authorize($request, 'view', $location);

            return $this->modelResponse($location);
        });
    }

    /**
     * @return array<string, Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'ulid' => $schema->string()->description('The ulid of the location.')->required(),
        ];
    }
}
