<?php

declare(strict_types=1);

namespace App\Actions\Sponsors;

use App\Models\Sponsor;

class DeleteSponsor
{
    public function handle(Sponsor $sponsor): void
    {
        $sponsor->delete();
    }
}
