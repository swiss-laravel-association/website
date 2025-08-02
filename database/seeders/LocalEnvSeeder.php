<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Location;
use App\Models\Sponsor;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Seeder;

class LocalEnvSeeder extends Seeder
{
    public function run(): void
    {
        $this->createSponsors();
        $this->createLocations();
        $this->createEvents();
    }

    private function createLocations(): void
    {
        Location::factory()
            ->count(12)
            ->create();
    }

    private function createSponsors(): void
    {
        Sponsor::factory()
            ->isFoundingSponsor()
            ->count(5)
            ->create();

        Sponsor::factory()
            ->isLocationSponsor()
            ->count(5)
            ->create();
    }

    private function createEvents(): void
    {
        $period = CarbonPeriod::create(
            now()->subMonths(12),
            '1 month',
            now()->addMonths(12)
        );

        /** @var Carbon $date */
        foreach ($period as $date) {
            Event::factory()->create([
                'name' => sprintf('%s Meetup', $date->format('F Y')),
                'start_date' => $date->setTime(18, 30, 0),
                'end_date' => $date->setTime(22, 0, 0),
                'is_published' => true,
                'location_id' => Location::inRandomOrder()->first()?->id,
            ]);
        }
    }
}
