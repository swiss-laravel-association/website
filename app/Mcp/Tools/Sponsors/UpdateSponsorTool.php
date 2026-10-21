<?php

namespace App\Mcp\Tools\Sponsors;

use App\Actions\Sponsors\UpdateSponsor;
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
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;

#[Name('update-sponsor')]
#[Description('Update a sponsor by its ulid. Only the fields you pass are changed. Logos cannot be set through this tool.')]
#[IsIdempotent]
class UpdateSponsorTool extends Tool
{
    use AuthorizesToolCalls;

    public function handle(Request $request, UpdateSponsor $updateSponsor): Response
    {
        return $this->respond(function () use ($request, $updateSponsor): Response {
            $ulid = $request->validate(['ulid' => ['required', 'string']])['ulid'];

            $sponsor = $this->findByUlid(Sponsor::class, $ulid);

            $this->authorize($request, 'update', $sponsor);

            /** @var array{name?: string, website?: string, type?: string, background_color?: string|null} $validated */
            $validated = $request->validate([
                'name' => ['sometimes', 'required', 'string', 'max:255'],
                'website' => ['sometimes', 'required', 'url', 'max:255'],
                'type' => ['sometimes', 'required', Rule::enum(SponsorType::class)],
                'background_color' => ['sometimes', 'nullable', 'string', 'max:255'],
            ]);

            if (isset($validated['type'])) {
                $validated['type'] = SponsorType::from($validated['type']);
            }

            /** @var array{name?: string, website?: string, type?: SponsorType, background_color?: string|null} $validated */
            return $this->modelResponse($updateSponsor->handle($sponsor, $validated));
        });
    }

    /**
     * @return array<string, Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'ulid' => $schema->string()->description('The ulid of the sponsor to update.')->required(),
            'name' => $schema->string()->description('Name of the sponsor.'),
            'website' => $schema->string()->description('Website, full URL.'),
            'type' => $schema->string()->enum(array_map(fn (SponsorType $type): string => $type->value, SponsorType::cases()))->description('Sponsor type: founding or location.'),
            'background_color' => $schema->string()->description('CSS background color behind the logo, e.g. "#ffffff".'),
        ];
    }
}
