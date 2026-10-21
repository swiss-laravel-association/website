<?php

namespace App\Mcp\Tools\Locations;

use App\Actions\Locations\CreateLocation;
use App\Mcp\Tools\Concerns\AuthorizesToolCalls;
use App\Models\Location;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tool;

#[Name('create-location')]
#[Description('Create a meetup location. name, zip_code and city are required. Use find-or-create-location instead when the location may already exist.')]
class CreateLocationTool extends Tool
{
    use AuthorizesToolCalls;

    public function handle(Request $request, CreateLocation $createLocation): Response
    {
        return $this->respond(function () use ($request, $createLocation): Response {
            $this->authorize($request, 'create', Location::class);

            /** @var array{name: string, address?: string|null, zip_code: string, city: string, description?: string|null, notes?: string|null, capacity?: int|null} $attributes */
            $attributes = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'address' => ['nullable', 'string', 'max:255'],
                'zip_code' => ['required', 'string', 'max:255'],
                'city' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'notes' => ['nullable', 'string'],
                'capacity' => ['nullable', 'integer', 'min:0'],
            ]);

            return $this->modelResponse($createLocation->handle($attributes));
        });
    }

    /**
     * @return array<string, Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'name' => $schema->string()->description('Name of the venue, e.g. "Kosmos".')->required(),
            'address' => $schema->string()->description('Street and house number.'),
            'zip_code' => $schema->string()->description('Swiss postal code, e.g. "8004".')->required(),
            'city' => $schema->string()->description('City, e.g. "Zürich".')->required(),
            'description' => $schema->string()->description('Public description of the venue.'),
            'notes' => $schema->string()->description('Internal notes, not shown publicly.'),
            'capacity' => $schema->integer()->min(0)->description('Number of attendees the venue can hold.'),
        ];
    }
}
