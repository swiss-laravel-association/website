<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum SponsorType: string implements HasLabel
{
    case Founding = 'founding';
    case Location = 'location';

    public function getLabel(): string
    {
        return match ($this) {
            self::Founding => 'Founding Sponsor',
            self::Location => 'Location Sponsor',
        };
    }
}
