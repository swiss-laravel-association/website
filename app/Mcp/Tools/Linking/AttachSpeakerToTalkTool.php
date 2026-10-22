<?php

namespace App\Mcp\Tools\Linking;

use App\Actions\Talks\AttachSpeakerToTalk;
use App\Mcp\Tools\Concerns\AuthorizesToolCalls;
use App\Models\Speaker;
use App\Models\Talk;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\JsonSchema\Types\Type;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Attributes\Description;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsIdempotent;

#[Name('attach-speaker-to-talk')]
#[Description('Add a speaker to a talk. Both are addressed by their ulid. Adding a speaker who is already linked changes nothing. Needs the talks:update ability.')]
#[IsIdempotent]
class AttachSpeakerToTalkTool extends Tool
{
    use AuthorizesToolCalls;

    public function handle(Request $request, AttachSpeakerToTalk $attachSpeakerToTalk): Response
    {
        return $this->respond(function () use ($request, $attachSpeakerToTalk): Response {
            $validated = $request->validate([
                'speaker_ulid' => ['required', 'string'],
                'talk_ulid' => ['required', 'string'],
            ]);

            $speaker = $this->findByUlid(Speaker::class, $validated['speaker_ulid']);
            $talk = $this->findByUlid(Talk::class, $validated['talk_ulid']);

            $this->authorize($request, 'update', $talk);

            $attachSpeakerToTalk->handle($speaker, $talk);

            return $this->jsonResponse(['attached' => true, 'speaker_ulid' => $speaker->ulid, 'talk_ulid' => $talk->ulid]);
        });
    }

    /**
     * @return array<string, Type>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'speaker_ulid' => $schema->string()->description('The ulid of the speaker.')->required(),
            'talk_ulid' => $schema->string()->description('The ulid of the talk.')->required(),
        ];
    }
}
