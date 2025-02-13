<?php

namespace Database\Seeders;

use App\Models\EventType;
use Illuminate\Database\Seeder;

class EventTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EventType::firstOrCreate(
            ['name' => 'Meetup'],
            ['description' => "Let's meet at the meetups ! Our events are awesome, lots of people share their knowledge.",
                'sort_order' => 1],
        );

        EventType::firstOrCreate(
            ['name' => 'Meetup with diner'],
            ['sort_order' => 2],
        );

        EventType::firstOrCreate(
            ['name' => 'Fun activity'],
            ['sort_order' => 2],
        );

        EventType::firstOrCreate(
            ['name' => 'Other'],
            ['sort_order' => 3],
        );
    }
}
