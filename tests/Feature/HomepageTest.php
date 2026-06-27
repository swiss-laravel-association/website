<?php

it('returns a successful response', function (): void {
    $response = $this->get('/');

    $response->assertStatus(200);
});

it('renders the global Organization JSON-LD with the address from config', function (): void {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('"@type":"Organization"', false);
    $response->assertSee('"streetAddress":"Boulevard Lilienthal 54"', false);
    $response->assertSee('"postalCode":"8152"', false);
    $response->assertSee('"addressLocality":"Glattpark"', false);
    $response->assertSee('"email":"sandro@laravel.swiss"', false);
});

it('renders a title and meta description on the homepage', function (): void {
    $response = $this->get('/');

    $response->assertSee('<title>Swiss Laravel Association</title>', false);
    $response->assertSee('<meta name="description"', false);
});
