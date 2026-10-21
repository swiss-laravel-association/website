<?php

namespace App\Mcp\Servers;

use App\Mcp\Tools\Locations\CreateLocationTool;
use App\Mcp\Tools\Locations\DeleteLocationTool;
use App\Mcp\Tools\Locations\FindOrCreateLocationTool;
use App\Mcp\Tools\Locations\GetLocationTool;
use App\Mcp\Tools\Locations\ListLocationsTool;
use App\Mcp\Tools\Locations\UpdateLocationTool;
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
use Laravel\Mcp\Server;
use Laravel\Mcp\Server\Attributes\Instructions;
use Laravel\Mcp\Server\Attributes\Name;
use Laravel\Mcp\Server\Attributes\Version;

#[Name('Swiss Laravel Association Website')]
#[Version('1.0.0')]
#[Instructions('Manage the content of the Swiss Laravel Association website: meetup events, talks, speakers, locations, sponsors and blog posts. Records are addressed by their ulid. Every tool checks the abilities of your API token and returns an error when the token lacks the required ability.')]
class WebsiteServer extends Server
{
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
    ];

    protected array $resources = [
        //
    ];

    protected array $prompts = [
        //
    ];
}
