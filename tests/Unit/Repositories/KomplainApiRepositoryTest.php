<?php

use App\Models\Komplain;
use App\Repositories\KomplainApiRepository;

test('filter nik tidak tersedia pada API komplain publik', function () {
    $repository = new KomplainApiRepository(new Komplain());

    $property = new ReflectionProperty($repository, 'allowedFilters');
    $filters = collect($property->getValue($repository))
        ->map(fn ($filter) => is_string($filter) ? $filter : $filter->getName());

    expect($filters)->not->toContain('nik');
});

test('include penduduk tidak tersedia pada API komplain publik', function () {
    $repository = new KomplainApiRepository(new Komplain());

    $property = new ReflectionProperty($repository, 'allowedIncludes');

    expect($property->getValue($repository))->not->toContain('penduduk');
});
