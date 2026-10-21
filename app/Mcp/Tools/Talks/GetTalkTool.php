<?php

namespace App\Mcp\Tools\Talks;

use App\Mcp\Tools\Concerns\AuthorizesToolCalls;
use App\Mcp\Tools\Concerns\SerializesMeetupContent;
use App\Models\Talk;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[Name('get-talk')]
#[Description('Get a single talk by its ulid, with its speakers and the events it is part of.')]
#[IsReadOnly]
class GetTalkTool extends Tool
{
    use AuthorizesToolCalls;
    use SerializesMeetupContent;

    public function handle(Request $request): Response
    {
        return $this->respond(function () use ($request): Response {
            $validated = $request->validate(['ulid' => ['required', 'string']]);

            $talk = $this->findByUlid(Talk::class, $validated['ulid']);

            $this->authorize($request, 'view', $talk);

            return $this->jsonResponse($this->talkPayload($talk->load(['speakers', 'events'])));
        });
    }

    /**
     * @return array<string, Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'ulid' => $schema->string()->description('The ulid of the talk.')->required(),
        ];
    }
}
