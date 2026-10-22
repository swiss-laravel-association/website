<?php

namespace App\Mcp\Tools\Locations;

use App\Actions\Locations\FindOrCreateLocation;
use App\Mcp\Tools\Concerns\AuthorizesToolCalls;
use App\Models\Location;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tool;

#[Name('find-or-create-location')]
#[Description('Return the location whose name matches (case-insensitive), or create it. zip_code and city are required when no location with that name exists. Other fields are ignored when a match is found. Needs both the locations:view and locations:create abilities.')]
class FindOrCreateLocationTool extends Tool
{
    use AuthorizesToolCalls;

    public function handle(Request $request, FindOrCreateLocation $findOrCreateLocation): Response
    {
        return $this->respond(function () use ($request, $findOrCreateLocation): Response {
            $this->authorize($request, 'viewAny', Location::class);
            $this->authorize($request, 'create', Location::class);

            $exists = Location::query()
                ->whereRaw('LOWER(name) = ?', [mb_strtolower((string) $request->string('name'))])
                ->exists();

            /** @var array{name: string, address?: string|null, zip_code?: string, city?: string, description?: string|null, notes?: string|null, capacity?: int|null} $validated */
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'address' => ['nullable', 'string', 'max:255'],
                'zip_code' => [$exists ? 'nullable' : 'required', 'string', 'max:255'],
                'city' => [$exists ? 'nullable' : 'required', 'string', 'max:255'],
                'description' => ['nullable', 'string'],
                'notes' => ['nullable', 'string'],
                'capacity' => ['nullable', 'integer', 'min:0'],
            ]);

            $name = $validated['name'];
            unset($validated['name']);

            return $this->modelResponse($findOrCreateLocation->handle($name, $validated));
        });
    }

    /**
     * @return array<string, Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'name' => $schema->string()->description('Name of the venue, e.g. "Kosmos". Matched case-insensitively.')->required(),
            'address' => $schema->string()->description('Street and house number. Used only when creating.'),
            'zip_code' => $schema->string()->description('Swiss postal code. Required when creating.'),
            'city' => $schema->string()->description('City. Required when creating.'),
            'description' => $schema->string()->description('Public description. Used only when creating.'),
            'notes' => $schema->string()->description('Internal notes. Used only when creating.'),
            'capacity' => $schema->integer()->min(0)->description('Number of attendees. Used only when creating.'),
        ];
    }
}
