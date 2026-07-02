<?php

use Illuminate\Support\Facades\Schema;

it('adds a ulid column to the domain tables', function (string $table): void {
    expect(Schema::hasColumn($table, 'ulid'))->toBeTrue();
})->with(['events', 'locations', 'posts', 'speakers', 'sponsors', 'talks']);
