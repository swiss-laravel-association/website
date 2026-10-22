<?php

namespace App\Mcp\Tools\Talks;

use App\Actions\Talks\UpdateTalk;
use App\Http\Resources\TalkResource;
use App\Mcp\Tools\Concerns\AuthorizesToolCalls;
use App\Models\Talk;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;

#[Name('update-talk')]
#[Description('Update a talk by its ulid. Only the fields you pass are changed.')]
#[IsIdempotent]
class UpdateTalkTool extends Tool
{
    use AuthorizesToolCalls;

    public function handle(Request $request, UpdateTalk $updateTalk): Response
    {
        return $this->respond(function () use ($request, $updateTalk): Response {
            $validated = $request->validate(['ulid' => ['required', 'string']]);

            $talk = $this->findByUlid(Talk::class, $validated['ulid']);

            $this->authorize($request, 'update', $talk);

            /** @var array{title?: string, description?: string, recording_url?: string|null} $attributes */
            $attributes = $request->validate([
                'title' => ['sometimes', 'required', 'string', 'max:255'],
                'description' => ['sometimes', 'required', 'string'],
                'recording_url' => ['sometimes', 'nullable', 'url', 'max:255'],
            ]);

            return $this->jsonResponse(TalkResource::make($updateTalk->handle($talk, $attributes))->resolve());
        });
    }

    /**
     * @return array<string, Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'ulid' => $schema->string()->description('The ulid of the talk to update.')->required(),
            'title' => $schema->string()->description('Title of the talk.'),
            'description' => $schema->string()->description('Abstract of the talk.'),
            'recording_url' => $schema->string()->nullable()->description('Link to the recording, full URL, or null to remove it.'),
        ];
    }
}
