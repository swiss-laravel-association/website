<?php

namespace App\Mcp\Servers;

use App\Mcp\Tools\Events\CreateEventTool;
use App\Mcp\Tools\Events\DeleteEventTool;
use App\Mcp\Tools\Events\GetEventTool;
use App\Mcp\Tools\Events\ListEventsTool;
use App\Mcp\Tools\Events\PublishEventTool;
use App\Mcp\Tools\Events\UnpublishEventTool;
use App\Mcp\Tools\Events\UpdateEventTool;
use App\Mcp\Tools\Linking\AttachSpeakerToTalkTool;
use App\Mcp\Tools\Linking\AttachTalkToEventTool;
use App\Mcp\Tools\Linking\DetachSpeakerFromTalkTool;
use App\Mcp\Tools\Linking\DetachTalkFromEventTool;
use App\Mcp\Tools\Locations\CreateLocationTool;
use App\Mcp\Tools\Locations\DeleteLocationTool;
use App\Mcp\Tools\Locations\FindOrCreateLocationTool;
use App\Mcp\Tools\Locations\GetLocationTool;
use App\Mcp\Tools\Locations\ListLocationsTool;
use App\Mcp\Tools\Locations\UpdateLocationTool;
use App\Mcp\Tools\Posts\CreatePostTool;
use App\Mcp\Tools\Posts\DeletePostTool;
use App\Mcp\Tools\Posts\GetPostTool;
use App\Mcp\Tools\Posts\ListPostsTool;
use App\Mcp\Tools\Posts\PublishPostTool;
use App\Mcp\Tools\Posts\UnpublishPostTool;
use App\Mcp\Tools\Posts\UpdatePostTool;
use App\Mcp\Tools\Speakers\CreateSpeakerTool;
use App\Mcp\Tools\Speakers\DeleteSpeakerTool;
use App\Mcp\Tools\Speakers\GetSpeakerTool;
use App\Mcp\Tools\Speakers\ListSpeakersTool;
use App\Mcp\Tools\Speakers\UpdateSpeakerTool;
use App\Mcp\Tools\Sponsors\CreateSponsorTool;
use App\Mcp\Tools\Sponsors\DeleteSponsorTool;
use App\Mcp\Tools\Sponsors\GetSponsorTool;
use App\Mcp\Tools\Sponsors\ListSponsorsTool;
use App\Mcp\Tools\Sponsors\UpdateSponsorTool;
use App\Mcp\Tools\Talks\CreateTalkTool;
use App\Mcp\Tools\Talks\DeleteTalkTool;
use App\Mcp\Tools\Talks\GetTalkTool;
use App\Mcp\Tools\Talks\ListTalksTool;
use App\Mcp\Tools\Talks\UpdateTalkTool;
use Laravel\Mcp\Server;
use Laravel\Mcp\Server\Attributes\Instructions;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Attributes\Version;

#[Name('Swiss Laravel Association Website')]
#[Version('1.0.0')]
#[Instructions('Manage the content of the Swiss Laravel Association website: meetup events, talks, speakers, locations, sponsors and blog posts. Records are addressed by their ulid. Every tool checks the abilities of your API token and returns an error when the token lacks the required ability.')]
class WebsiteServer extends Server
{
    public int $defaultPaginationLength = 50;

    protected array $tools = [
        ListLocationsTool::class,
        GetLocationTool::class,
        CreateLocationTool::class,
        UpdateLocationTool::class,
        DeleteLocationTool::class,
        FindOrCreateLocationTool::class,
        ListSpeakersTool::class,
        GetSpeakerTool::class,
        CreateSpeakerTool::class,
        UpdateSpeakerTool::class,
        DeleteSpeakerTool::class,
        ListSponsorsTool::class,
        GetSponsorTool::class,
        CreateSponsorTool::class,
        UpdateSponsorTool::class,
        DeleteSponsorTool::class,
        ListEventsTool::class,
        GetEventTool::class,
        CreateEventTool::class,
        UpdateEventTool::class,
        DeleteEventTool::class,
        PublishEventTool::class,
        UnpublishEventTool::class,
        ListTalksTool::class,
        GetTalkTool::class,
        CreateTalkTool::class,
        UpdateTalkTool::class,
        DeleteTalkTool::class,
        AttachTalkToEventTool::class,
        DetachTalkFromEventTool::class,
        AttachSpeakerToTalkTool::class,
        DetachSpeakerFromTalkTool::class,
        ListPostsTool::class,
        GetPostTool::class,
        CreatePostTool::class,
        UpdatePostTool::class,
        DeletePostTool::class,
        PublishPostTool::class,
        UnpublishPostTool::class,
    ];

    protected array $resources = [
        //
    ];

    protected array $prompts = [
        //
    ];
}
