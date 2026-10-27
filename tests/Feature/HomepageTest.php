<?php

it('returns a successful response', function (): void {
    $response = $this->get('/');

    $response->assertStatus(200);
});

it('renders the global Organization JSON-LD without the private address and phone number', function (): void {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('"@type":"Organization"', false);
    $response->assertSee('"email":"sandro@laravel.swiss"', false);
    $response->assertDontSee('Boulevard Lilienthal', false);
    $response->assertDontSee('Glattpark', false);
    $response->assertDontSee('+41 79 298 36 35', false);
    $response->assertDontSee('"telephone"', false);
});

it('renders a title and meta description on the homepage', function (): void {
    $response = $this->get('/');

    $response->assertSee('<title>Swiss Laravel Association | Laravel Meetups in Switzerland</title>', false);
    $response->assertSee('<meta name="description"', false);
});
