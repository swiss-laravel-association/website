<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Identity
    |--------------------------------------------------------------------------
    |
    | These values describe the association as a single source of truth. Both
    | the Organization JSON-LD (rendered globally via SchemaHelper) and the
    | imprint page read from here. Update them in one place.
    */

    'name' => 'Swiss Laravel Association',
    'legal_name' => 'Swiss Laravel Association',
    'url' => env('APP_URL', 'https://laravel.swiss'),
    'logo' => 'resources/images/logos/swiss-laravel-association.webp',
    'founding_date' => '2024-09-26',
    'description' => 'Bringing Laravel developers together across Switzerland.',

    'address' => [
        'street' => 'Boulevard Lilienthal 54',
        'postal_code' => '8152',
        'city' => 'Glattpark',
        'country' => 'Switzerland',
        'country_code' => 'CH',
    ],

    'contact' => [
        'email' => 'sandro@laravel.swiss',
        'phone' => '+41 79 298 36 35',
    ],

    'socials' => [
        'youtube' => 'https://www.youtube.com/@swiss-laravel-association',
        'bluesky' => 'https://bsky.app/profile/laravel.swiss',
        'linkedin' => 'https://www.linkedin.com/company/swiss-laravel-association/',
        'mastodon' => 'https://phpc.social/@swiss_laravel_association',
        'twitter' => 'https://twitter.com/swisslaravel',
        'x' => 'https://x.com/swisslaravel',
        'github' => 'https://github.com/swiss-laravel-association',
    ],
];
