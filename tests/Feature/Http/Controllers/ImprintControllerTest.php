<?php

it('returns a successful response', function (): void {
    $response = $this->get(route('imprint'));

    $response->assertStatus(200);
});

it('renders breadcrumbs with matching JSON-LD', function (): void {
    $response = $this->get(route('imprint'));

    $response->assertStatus(200);
    $response->assertSee('data-flux-breadcrumbs', false);
    $response->assertSee('"@type":"BreadcrumbList"', false);
    $response->assertSee('"name":"Imprint"', false);
});

it('renders the address from config and an SEO title', function (): void {
    $response = $this->get(route('imprint'));

    $response->assertSeeText(config('sla.address.street'));
    $response->assertSeeText(config('sla.address.city'));
    $response->assertSeeText(config('sla.contact.email'));
    $response->assertSee('<title>Imprint | Swiss Laravel Association</title>', false);
});
