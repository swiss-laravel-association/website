<?php

declare(strict_types=1);

namespace App\Filament\Resources\Sponsors\Pages;

use App\Filament\Resources\Sponsors\SponsorResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSponsor extends EditRecord
{
    protected static string $resource = SponsorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
