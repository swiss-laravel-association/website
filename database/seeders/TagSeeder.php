<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Prevents the seed from running multiple times :

        if (Tag::count() === 0) {

            Tag::create([
                'name' => 'Laravel',
                'description' => 'The best PHP framework.',
            ]);

            Tag::create([
                'name' => 'Vue.js',
                'description' => 'The best JavaScript framework.',
            ]);

            Tag::create([
                'name' => 'Tailwind CSS',
                'description' => 'The best CSS framework.',
            ]);

            Tag::create([
                'name' => 'Livewire',
                'description' => 'Building dynamic interfaces without JavaScript.',
            ]);

            Tag::create([
                'name' => 'Alpine.js',
                'description' => 'The best JavaScript framework.',
            ]);

            Tag::create([
                'name' => 'JavaScript',
                'description' => 'Back to the roots.',
            ]);

            Tag::create([
                'name' => 'OctoberCMS',
                'description' => 'Flat file CMS based on Laravel.',
            ]);

            Tag::create([
                'name' => 'Tests',
                'description' => 'Test your apps.',
            ]);

            Tag::create([
                'name' => 'Laravel Cloud',
                'description' => 'From local to production. Easy-peasy',
            ]);
        }
    }
}
