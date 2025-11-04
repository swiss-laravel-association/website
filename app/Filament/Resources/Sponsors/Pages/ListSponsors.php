<?php

namespace App\Filament\Resources\Sponsors\Pages;

use App\Filament\Resources\Sponsors\SponsorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSponsors extends ListRecords
{
    protected static string $resource = SponsorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
