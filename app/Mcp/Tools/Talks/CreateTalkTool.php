<?php

namespace App\Mcp\Tools\Talks;

use App\Actions\Talks\CreateTalk;
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

#[Name('create-talk')]
#[Description('Create a talk. title and description are required. Link speakers with attach-speaker-to-talk and add the talk to an event with attach-talk-to-event.')]
class CreateTalkTool extends Tool
{
    use AuthorizesToolCalls;

    public function handle(Request $request, CreateTalk $createTalk): Response
    {
        return $this->respond(function () use ($request, $createTalk): Response {
            $this->authorize($request, 'create', Talk::class);

            /** @var array{title: string, description: string, recording_url?: string|null} $attributes */
            $attributes = $request->validate([
                'title' => ['required', 'string', 'max:255'],
                'description' => ['required', 'string'],
                'recording_url' => ['nullable', 'url', 'max:255'],
            ]);

            return $this->jsonResponse(TalkResource::make($createTalk->handle($attributes)->refresh())->resolve());
        });
    }

    /**
     * @return array<string, Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'title' => $schema->string()->description('Title of the talk.')->required(),
            'description' => $schema->string()->description('Abstract of the talk.')->required(),
            'recording_url' => $schema->string()->description('Link to the recording, full URL.'),
        ];
    }
}
