<?php

namespace App\Mcp\Tools\Speakers;

use App\Actions\Speakers\UpdateSpeaker;
use App\Mcp\Tools\Concerns\AuthorizesToolCalls;
use App\Models\Speaker;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;

#[Name('update-speaker')]
#[Description('Update a speaker by its ulid. Only the fields you pass are changed.')]
#[IsIdempotent]
class UpdateSpeakerTool extends Tool
{
    use AuthorizesToolCalls;

    public function handle(Request $request, UpdateSpeaker $updateSpeaker): Response
    {
        return $this->respond(function () use ($request, $updateSpeaker): Response {
            $validated = $request->validate(['ulid' => ['required', 'string']]);

            $speaker = $this->findByUlid(Speaker::class, $validated['ulid']);

            $this->authorize($request, 'update', $speaker);

            /** @var array{name?: string, bio?: string|null, website?: string|null, github_profile?: string|null, x_profile?: string|null, linkedin_profile?: string|null, bluesky_profile?: string|null, youtube_profile?: string|null} $attributes */
            $attributes = $request->validate([
                'name' => ['sometimes', 'required', 'string', 'max:255'],
                'bio' => ['sometimes', 'nullable', 'string'],
                'website' => ['sometimes', 'nullable', 'url', 'max:255'],
                'github_profile' => ['sometimes', 'nullable', 'url', 'max:255'],
                'x_profile' => ['sometimes', 'nullable', 'url', 'max:255'],
                'linkedin_profile' => ['sometimes', 'nullable', 'url', 'max:255'],
                'bluesky_profile' => ['sometimes', 'nullable', 'url', 'max:255'],
                'youtube_profile' => ['sometimes', 'nullable', 'url', 'max:255'],
            ]);

            return $this->modelResponse($updateSpeaker->handle($speaker, $attributes));
        });
    }

    /**
     * @return array<string, Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'ulid' => $schema->string()->description('The ulid of the speaker to update.')->required(),
            'name' => $schema->string()->description('Full name of the speaker.'),
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
