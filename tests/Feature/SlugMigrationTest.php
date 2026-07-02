<?php

use Illuminate\Support\Facades\Schema;

it('adds a slug column to the sluggable domain tables', function (string $table): void {
    expect(Schema::hasColumn($table, 'slug'))->toBeTrue();
})->with(['events', 'talks', 'speakers']);

it('slug column is nullable on sluggable domain tables', function (string $table): void {
    $columns = Schema::getColumns($table);
    $slugColumn = collect($columns)->firstWhere('name', 'slug');

    expect($slugColumn)->not->toBeNull();
    expect($slugColumn['nullable'])->toBeTrue();
})->with(['events', 'talks', 'speakers']);
