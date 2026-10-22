<?php

use App\Mcp\Servers\WebsiteServer;
use Laravel\Mcp\Facades\Mcp;

Mcp::web('/mcp', WebsiteServer::class)->middleware(['auth:sanctum', 'throttle:120,1']);
