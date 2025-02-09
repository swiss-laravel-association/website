<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call(CountrySeeder::class);
        $this->call(EventTypeSeeder::class);
        $this->call(LocationSeeder::class);
        $this->call(TagSeeder::class);

        // User::factory(10)->create();
        // The environment is local :
        if (! App::environment('production')) {
            User::firstOrCreate(
                ['email' => 'test@laravel.swiss'],
                ['name' => 'Test User'],
            );
        }

    }
}
