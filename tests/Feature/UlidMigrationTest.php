<?php

use Illuminate\Support\Facades\Schema;

it('adds a ulid column to the domain tables', function (string $table): void {
    expect(Schema::hasColumn($table, 'ulid'))->toBeTrue();
})->with(['events', 'locations', 'posts', 'speakers', 'sponsors', 'talks']);

it('ulid column is nullable on domain tables', function (string $table): void {
    $columns = Schema::getColumns($table);
    $ulidColumn = collect($columns)->firstWhere('name', 'ulid');

    expect($ulidColumn)->not->toBeNull();
    expect($ulidColumn['nullable'])->toBeTrue();
})->with(['events', 'locations', 'posts', 'speakers', 'sponsors', 'talks']);

it('ulid column has unique index on domain tables', function (string $table): void {
    $indexes = Schema::getIndexes($table);
    $ulidIndex = collect($indexes)->first(fn ($index): bool => $index['columns'] === ['ulid']);

    expect($ulidIndex)->not->toBeNull();
    expect($ulidIndex['unique'])->toBeTrue();
})->with(['events', 'locations', 'posts', 'speakers', 'sponsors', 'talks']);
