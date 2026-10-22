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

#[Name('get-sponsor')]
#[Description('Get a single sponsor by its ulid.')]
#[IsReadOnly]
class GetSponsorTool extends Tool
{
    use AuthorizesToolCalls;

    public function handle(Request $request): Response
    {
        return $this->respond(function () use ($request): Response {
            $validated = $request->validate(['ulid' => ['required', 'string']]);

            $sponsor = $this->findByUlid(Sponsor::class, $validated['ulid']);

            $this->authorize($request, 'view', $sponsor);

            return $this->modelResponse($sponsor);
        });
    }

    /**
     * @return array<string, Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'ulid' => $schema->string()->description('The ulid of the sponsor.')->required(),
        ];
    }
}
