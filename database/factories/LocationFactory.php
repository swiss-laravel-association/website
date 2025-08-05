<?php

namespace Database\Factories;

use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location>
 */
class LocationFactory extends Factory
{
    protected $model = Location::class;

    public function definition(): array
    {
        $this->faker->addProvider(new \Wnx\FakerSwissLocations\Provider\Location($this->faker));

        /** @var \Wnx\FakerSwissLocations\Location $location */
        $location = $this->faker->location();

        return [
            'name' => $this->faker->company(),
            'address' => $this->faker->address(),
            'zip_code' => $location->postcode,
            'city' => $location->city,
            'description' => null,
            'notes' => null,
            'capacity' => $this->faker->randomElement([20, 30, 40, 50]),
        ];
    }
}
