<?php

use App\Models\Event as Event;
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

});

it('allows only one sign in per event', function () {

});

it('returns the correct number of signed in members', function() {

});
