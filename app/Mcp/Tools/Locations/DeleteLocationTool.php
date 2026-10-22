<?php

namespace App\Mcp\Tools\Locations;

use App\Actions\Locations\DeleteLocation;
use App\Mcp\Tools\Concerns\AuthorizesToolCalls;
use App\Models\Location;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;

#[Name('delete-location')]
#[Description('Delete a meetup location by its ulid. Events held at this location keep existing but lose their location.')]
#[IsDestructive]
class DeleteLocationTool extends Tool
{
    use AuthorizesToolCalls;

    public function handle(Request $request, DeleteLocation $deleteLocation): Response
    {
        return $this->respond(function () use ($request, $deleteLocation): Response {
            $validated = $request->validate(['ulid' => ['required', 'string']]);

            $location = $this->findByUlid(Location::class, $validated['ulid']);

            $this->authorize($request, 'delete', $location);

            $deleteLocation->handle($location);

            return $this->jsonResponse(['deleted' => true, 'id' => $location->id, 'ulid' => $location->ulid]);
        });
    }

    /**
     * @return array<string, Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'ulid' => $schema->string()->description('The ulid of the location to delete.')->required(),
        ];
    }
}
