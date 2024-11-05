<?php

return [
    /*
     *  You'll find both the API token and endpoint on Mailcoach'
     *  API tokens screen in the Mailcoach settings.
     */
    'api_token' => env('MAILCOACH_API_TOKEN'),

    'endpoint' => env('MAILCOACH_API_ENDPOINT'),

    'lists' => [
        'newsletter' => [
            'uuid' => env('MAILCOACH_NEWSLETTER_LIST_UUID'),
        ],
    ]
];
