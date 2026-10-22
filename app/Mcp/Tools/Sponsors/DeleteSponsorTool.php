<?php

namespace App\Mcp\Tools\Sponsors;

use App\Actions\Sponsors\DeleteSponsor;
use App\Mcp\Tools\Concerns\AuthorizesToolCalls;
use App\Models\Sponsor;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsDestructive;

#[Name('delete-sponsor')]
#[Description('Delete a sponsor by its ulid.')]
#[IsDestructive]
class DeleteSponsorTool extends Tool
{
    use AuthorizesToolCalls;

    public function handle(Request $request, DeleteSponsor $deleteSponsor): Response
    {
        return $this->respond(function () use ($request, $deleteSponsor): Response {
            $validated = $request->validate(['ulid' => ['required', 'string']]);

            $sponsor = $this->findByUlid(Sponsor::class, $validated['ulid']);

            $this->authorize($request, 'delete', $sponsor);

            $deleteSponsor->handle($sponsor);

            return $this->jsonResponse(['deleted' => true, 'id' => $sponsor->id, 'ulid' => $sponsor->ulid]);
        });
    }

    /**
     * @return array<string, Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'ulid' => $schema->string()->description('The ulid of the sponsor to delete.')->required(),
        ];
    }
}
