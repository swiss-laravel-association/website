<?php

declare(strict_types=1);

namespace App\Filament\Resources\Talks\Pages;

use App\Filament\Resources\Talks\TalkResource;
use Filament\Resources\Pages\CreateRecord;

class CreateTalk extends CreateRecord
{
    protected static string $resource = TalkResource::class;
}
