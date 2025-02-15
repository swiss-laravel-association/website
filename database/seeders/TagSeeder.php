<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Tags\Tag;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Tag::firstOrCreate([
            'name' => 'Laravel',
        ]);

        Tag::firstOrCreate([
            'name' => 'Vue.js',
        ]);

        Tag::firstOrCreate([
            'name' => 'Tailwind CSS',
        ]);

        Tag::firstOrCreate([
            'name' => 'Livewire',
        ]);

        Tag::firstOrCreate([
            'name' => 'Alpine.js',
        ]);

        Tag::firstOrCreate([
            'name' => 'JavaScript',
        ]);

        Tag::firstOrCreate([
            'name' => 'OctoberCMS',
        ]);

        Tag::firstOrCreate([
            'name' => 'Tests',
        ]);

        Tag::firstOrCreate([
            'name' => 'Laravel Cloud',
        ]);

    }
}
