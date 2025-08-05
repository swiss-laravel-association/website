<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        $date = Carbon::now()->addDays(random_int(1, 30));

        return [
            'name' => sprintf('%s Meetup', $date->format('F Y')),
            'description' => $this->faker->text(),
            'start_date' => $date->setTime(18, 30, 0),
            'end_date' => $date->setTime(22, 0, 0),

            'meetup_link' => 'https://www.meetup.com/laravel-switzerland-meetup/events/308234097/',
            'is_published' => $this->faker->boolean(),
            // 'location_id' => Location::factory(),
        ];
    }
}
