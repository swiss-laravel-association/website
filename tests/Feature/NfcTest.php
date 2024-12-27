<?php

use App\Models\Event as Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('accepts only calls from clients with valid signatures', function(){
    $this->getJson(route('nfc.client-connect'))->assertStatus(403);
    $this->withHeaders(['x-client-signature' => 'some rubbish'])->getJson(route('nfc.client-connect'))->assertStatus(403);
    Event::factory()->current()->create(['api_code' => 'somethingStrange']);
    Event::factory()->current()->create(['api_code' => 'somethingStranger']);
    Event::factory()->current()->create(['api_code' => 'theStrangest!']);
    $this->withHeaders(['x-client-signature' => 'wrong password'])->getJson(route('nfc.client-connect'))->assertStatus(403);
    $this->withHeaders(['x-client-signature' => 'somethingStrange'])->getJson(route('nfc.client-connect'))->assertStatus(403);
    $this->withHeaders(['x-client-signature' => md5('somethingStrange')])->getJson(route('nfc.client-connect'))->assertStatus(200);
    $this->withHeaders(['x-client-signature' => md5('somethingStranger')])->getJson(route('nfc.client-connect'))->assertStatus(200);
    $this->withHeaders(['x-client-signature' => md5('theStrangest!')])->getJson(route('nfc.client-connect'))->assertStatus(200);
});

it('declines valid codes for past and future events', function() {
    Event::factory()->past()->create(['api_code' => 'past']);
    Event::factory()->future()->create(['api_code' => 'future']);
    $this->withHeaders(['x-client-signature' => md5('past')])->getJson(route('nfc.client-connect'))->assertStatus(403);
    $this->withHeaders(['x-client-signature' => md5('future')])->getJson(route('nfc.client-connect'))->assertStatus(403);
});

it('signs in a user with correct credentials', function () {
    $event = Event::factory()->current()->create(['api_code' => 'somethingStrange']);
    $member = User::factory()->create();
    $signature = $member->getSignatureLiteral();
    $uuid = $member->uuid;

        // missing stuff
    $this->withHeaders(['x-client-signature' => md5($event->api_code)])
        ->postJson(route('nfc.member-sign-in'), [])
        ->assertStatus(403)
        ->assertJsonFragment(['error' => trans('api.nfc.errors.user_credentials')]);

        // wrong uuid
    $this->withHeaders(['x-client-signature' => md5($event->api_code)])
        ->postJson(route('nfc.member-sign-in'), ['uuid' => Str::uuid(), 'signature' => $signature])
        ->assertStatus(403)
        ->assertJsonFragment(['error' => trans('api.nfc.errors.user_credentials')]);

        // wrong signature
    $this->withHeaders(['x-client-signature' => md5($event->api_code)])
        ->postJson(route('nfc.member-sign-in'), ['uuid' => $uuid, 'signature' => 'something random'])
        ->assertStatus(403)
        ->assertJsonFragment(['error' => trans('api.nfc.errors.user_credentials')]);

        // all shiny
    $this->withHeaders(['x-client-signature' => md5($event->api_code)])
        ->postJson(route('nfc.member-sign-in'), ['uuid' => $uuid, 'signature' => $signature])
        ->assertStatus(201);
});

it('allows only one sign in per event', function () {
    $eventOne = Event::factory()->current()->create(['api_code' => 'somethingStrange']);
    $eventTwo = Event::factory()->current()->create(['api_code' => 'someOtherCode']);
    $eventThree = Event::factory()->current()->create(['api_code' => 'AndAnotherSomething']);
    $member = User::factory()->create();
    $signature = $member->getSignatureLiteral();
    $uuid = $member->uuid;

    $this->withHeaders(['x-client-signature' => md5($eventOne->api_code)])
        ->postJson(route('nfc.member-sign-in'), ['uuid' => $uuid, 'signature' => $signature])
        ->assertStatus(201);

    $this->withHeaders(['x-client-signature' => md5($eventTwo->api_code)])
        ->postJson(route('nfc.member-sign-in'), ['uuid' => $uuid, 'signature' => $signature])
        ->assertStatus(201);

    $this->withHeaders(['x-client-signature' => md5($eventTwo->api_code)])
        ->postJson(route('nfc.member-sign-in'), ['uuid' => $uuid, 'signature' => $signature])
        ->assertStatus(403)
        ->assertJsonFragment(['error' => trans('api.nfc.errors.multi_sign_in')]);

    $this->withHeaders(['x-client-signature' => md5($eventTwo->api_code)])
        ->postJson(route('nfc.member-sign-in'), ['uuid' => $uuid, 'signature' => $signature])
        ->assertStatus(403)
        ->assertJsonFragment(['error' => trans('api.nfc.errors.multi_sign_in')]);

    $this->withHeaders(['x-client-signature' => md5($eventThree->api_code)])
        ->postJson(route('nfc.member-sign-in'), ['uuid' => $uuid, 'signature' => $signature])
        ->assertStatus(201);

    $this->withHeaders(['x-client-signature' => md5($eventThree->api_code)])
        ->postJson(route('nfc.member-sign-in'), ['uuid' => $uuid, 'signature' => $signature])
        ->assertStatus(403)
        ->assertJsonFragment(['error' => trans('api.nfc.errors.multi_sign_in')]);
});

it('returns the correct number of signed in members', function() {
    $event = Event::factory()->current()->create(['api_code' => 'somethingStrange']);
    $members = User::factory()->count(5)->create();

    $this->withHeaders(['x-client-signature' => md5($event->api_code)])
        ->getJson(route('nfc.client-connect'))
        ->assertStatus(200)
        ->assertJsonFragment([
            'sign_in_count' => 0
        ]);

    $this->withHeaders(['x-client-signature' => md5($event->api_code)])
        ->postJson(route('nfc.member-sign-in'), ['uuid' => $members[0]->uuid, 'signature' => $members[0]->getSignatureLiteral()])
        ->assertStatus(201);

    $this->withHeaders(['x-client-signature' => md5($event->api_code)])
        ->getJson(route('nfc.client-connect'))
        ->assertStatus(200)
        ->assertJsonFragment([
            'sign_in_count' => 1
        ]);

    $this->withHeaders(['x-client-signature' => md5($event->api_code)])
        ->postJson(route('nfc.member-sign-in'), ['uuid' => $members[1]->uuid, 'signature' => $members[1]->getSignatureLiteral()])
        ->assertStatus(201);

    $this->withHeaders(['x-client-signature' => md5($event->api_code)])
        ->postJson(route('nfc.member-sign-in'), ['uuid' => $members[2]->uuid, 'signature' => $members[2]->getSignatureLiteral()])
        ->assertStatus(201);

    $this->withHeaders(['x-client-signature' => md5($event->api_code)])
        ->getJson(route('nfc.client-connect'))
        ->assertStatus(200)
        ->assertJsonFragment([
            'sign_in_count' => 3
        ]);

});
