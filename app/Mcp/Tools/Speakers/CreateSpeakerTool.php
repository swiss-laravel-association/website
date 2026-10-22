<?php

namespace App\Mcp\Tools\Speakers;

use App\Actions\Speakers\CreateSpeaker;
use App\Mcp\Tools\Concerns\AuthorizesToolCalls;
use App\Models\Speaker;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tool;

#[Name('create-speaker')]
#[Description('Create a speaker. name is required. Profile fields are full URLs.')]
class CreateSpeakerTool extends Tool
{
    use AuthorizesToolCalls;

    public function handle(Request $request, CreateSpeaker $createSpeaker): Response
    {
        return $this->respond(function () use ($request, $createSpeaker): Response {
            $this->authorize($request, 'create', Speaker::class);

            /** @var array{name: string, bio?: string|null, website?: string|null, github_profile?: string|null, x_profile?: string|null, linkedin_profile?: string|null, bluesky_profile?: string|null, youtube_profile?: string|null} $attributes */
            $attributes = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'bio' => ['nullable', 'string'],
                'website' => ['nullable', 'url', 'max:255'],
                'github_profile' => ['nullable', 'url', 'max:255'],
                'x_profile' => ['nullable', 'url', 'max:255'],
                'linkedin_profile' => ['nullable', 'url', 'max:255'],
                'bluesky_profile' => ['nullable', 'url', 'max:255'],
                'youtube_profile' => ['nullable', 'url', 'max:255'],
            ]);

            return $this->modelResponse($createSpeaker->handle($attributes));
        });
    }

    /**
     * @return array<string, Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'name' => $schema->string()->description('Full name of the speaker.')->required(),
            'bio' => $schema->string()->description('Short biography.'),
            'website' => $schema->string()->description('Personal website, full URL.'),
            'github_profile' => $schema->string()->description('GitHub profile, full URL.'),
            'x_profile' => $schema->string()->description('X profile, full URL.'),
            'linkedin_profile' => $schema->string()->description('LinkedIn profile, full URL.'),
            'bluesky_profile' => $schema->string()->description('Bluesky profile, full URL.'),
            'youtube_profile' => $schema->string()->description('YouTube channel, full URL.'),
        ];
    }
}
