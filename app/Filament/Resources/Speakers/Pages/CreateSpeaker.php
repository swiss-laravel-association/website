<?php

declare(strict_types=1);

namespace App\Filament\Resources\Speakers\Pages;

use App\Filament\Resources\Speakers\SpeakerResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSpeaker extends CreateRecord
{
    protected static string $resource = SpeakerResource::class;
}
