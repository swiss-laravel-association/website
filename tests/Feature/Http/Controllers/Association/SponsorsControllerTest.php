<?php

it('returns a successful response', function (): void {
    $response = $this->get(route('association.sponsors'));

    $response->assertStatus(200);
});

it('renders breadcrumbs with matching JSON-LD', function (): void {
    $response = $this->get(route('association.sponsors'));

    $response->assertStatus(200);
    $response->assertSee('data-flux-breadcrumbs', false);
    $response->assertSeeText('Sponsors');
    $response->assertSee('"@type":"BreadcrumbList"', false);
    $response->assertSee('"name":"Sponsors"', false);
});
