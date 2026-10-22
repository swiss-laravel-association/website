<?php

namespace App\Mcp\Tools\Locations;

use App\Actions\Locations\UpdateLocation;
use App\Mcp\Tools\Concerns\AuthorizesToolCalls;
use App\Models\Location;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;

#[Name('update-location')]
#[Description('Update a meetup location by its ulid. Only the fields you pass are changed.')]
#[IsIdempotent]
class UpdateLocationTool extends Tool
{
    use AuthorizesToolCalls;

    public function handle(Request $request, UpdateLocation $updateLocation): Response
    {
        return $this->respond(function () use ($request, $updateLocation): Response {
            $validated = $request->validate(['ulid' => ['required', 'string']]);

            $location = $this->findByUlid(Location::class, $validated['ulid']);

            $this->authorize($request, 'update', $location);

            /** @var array{name?: string, address?: string|null, zip_code?: string, city?: string, description?: string|null, notes?: string|null, capacity?: int|null} $attributes */
            $attributes = $request->validate([
                'name' => ['sometimes', 'required', 'string', 'max:255'],
                'address' => ['sometimes', 'nullable', 'string', 'max:255'],
                'zip_code' => ['sometimes', 'required', 'string', 'max:255'],
                'city' => ['sometimes', 'required', 'string', 'max:255'],
                'description' => ['sometimes', 'nullable', 'string'],
                'notes' => ['sometimes', 'nullable', 'string'],
                'capacity' => ['sometimes', 'nullable', 'integer', 'min:0'],
            ]);

            return $this->modelResponse($updateLocation->handle($location, $attributes));
        });
    }

    /**
     * @return array<string, Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'ulid' => $schema->string()->description('The ulid of the location to update.')->required(),
            'name' => $schema->string()->description('Name of the venue.'),
            'address' => $schema->string()->description('Street and house number.'),
            'zip_code' => $schema->string()->description('Swiss postal code, e.g. "8004".'),
            'city' => $schema->string()->description('City, e.g. "Zürich".'),
            'description' => $schema->string()->description('Public description of the venue.'),
            'notes' => $schema->string()->description('Internal notes, not shown publicly.'),
            'capacity' => $schema->integer()->min(0)->description('Number of attendees the venue can hold.'),
        ];
    }
}
