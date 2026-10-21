<?php

namespace App\Mcp\Servers;

use App\Mcp\Tools\Locations\CreateLocationTool;
use App\Mcp\Tools\Locations\DeleteLocationTool;
use App\Mcp\Tools\Locations\FindOrCreateLocationTool;
use App\Mcp\Tools\Locations\GetLocationTool;
use App\Mcp\Tools\Locations\ListLocationsTool;
use App\Mcp\Tools\Locations\UpdateLocationTool;
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
    ];

    protected array $resources = [
        //
    ];

    protected array $prompts = [
        //
    ];
}
