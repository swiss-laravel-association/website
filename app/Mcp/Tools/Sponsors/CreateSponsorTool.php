<?php

namespace App\Mcp\Tools\Sponsors;

use App\Actions\Sponsors\CreateSponsor;
use App\Enums\SponsorType;
use App\Mcp\Tools\Concerns\AuthorizesToolCalls;
use App\Models\Sponsor;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Illuminate\Validation\Rule;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tool;

#[Name('create-sponsor')]
#[Description('Create a sponsor. name, website and type (founding or location) are required. Logos cannot be set through this tool.')]
class CreateSponsorTool extends Tool
{
    use AuthorizesToolCalls;

    public function handle(Request $request, CreateSponsor $createSponsor): Response
    {
        return $this->respond(function () use ($request, $createSponsor): Response {
            $this->authorize($request, 'create', Sponsor::class);

            /** @var array{name: string, website: string, type: string, background_color?: string|null} $validated */
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'website' => ['required', 'url', 'max:255'],
                'type' => ['required', Rule::enum(SponsorType::class)],
                'background_color' => ['nullable', 'string', 'regex:/^#(?:[0-9a-fA-F]{3}|[0-9a-fA-F]{6}|[0-9a-fA-F]{8})$/'],
            ]);

            $validated['type'] = SponsorType::from($validated['type']);

            /** @var array{name: string, website: string, type: SponsorType, background_color?: string|null} $validated */
            return $this->modelResponse($createSponsor->handle($validated));
        });
    }

    /**
     * @return array<string, Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'name' => $schema->string()->description('Name of the sponsor.')->required(),
            'website' => $schema->string()->description('Website, full URL.')->required(),
            'type' => $schema->string()->enum(array_map(fn (SponsorType $type): string => $type->value, SponsorType::cases()))->description('Sponsor type: founding or location.')->required(),
            'background_color' => $schema->string()->description('Hex background color behind the logo: #RGB, #RRGGBB or #RRGGBBAA, e.g. "#ffffff".'),
        ];
    }
}
