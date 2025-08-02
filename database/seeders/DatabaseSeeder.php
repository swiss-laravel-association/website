<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->createSystemUser();

        if (app()->environment('local')) {
            $this->call(LocalEnvSeeder::class);
        }
    }

    protected function createSystemUser(): void
    {
        if (User::where('email', 'test@example.com')->exists() === false) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);
        }
    }
}
