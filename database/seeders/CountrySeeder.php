<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Country::firstOrCreate(
            ['code' => 'CH'],
            ['name' => 'Switzerland', 'sort_order' => 1, 'description' => "Switzerland is a small country, but it's a creative one."]
        );

        Country::firstOrCreate(
            ['code' => 'DE'],
            ['name' => 'Germany', 'sort_order' => 2, 'description' => 'Germany makes the best Schnitzel.']
        );

        Country::firstOrCreate(
            ['code' => 'IT'],
            ['name' => 'Italy', 'sort_order' => 3, 'description' => 'Pasta e pizza. E Chianti. Ma!']
        );

        Country::firstOrCreate(
            ['code' => 'FR'],
            ['name' => 'France',  'sort_order' => 4, 'description' => 'The most romantic contry in the World.']
        );
    }
}
