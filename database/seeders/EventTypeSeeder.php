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
        // Prevents the seed from running multiple times :

        if (EventType::count() === 0) {

            EventType::create([
                'name' => 'Meetup',
                'description' => "Let's meet at the meetups ! Our events are awesome, lots of people share their knowledge.",
                'sort_order' => 1,
            ]);

            EventType::create([
                'name' => 'Meetup with diner',
                'sort_order' => 2,
            ]);

            EventType::create([
                'name' => 'Fun activity',
                'sort_order' => 2,
            ]);

            EventType::create([
                'name' => 'Other',
                'sort_order' => 3,
            ]);

        }
    }
}
