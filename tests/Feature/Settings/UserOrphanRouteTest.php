<?php

use Illuminate\Support\Facades\Route;

test('route user yang tidak memiliki method controller sudah dihapus', function (string $name) {
    expect(Route::has($name))->toBeFalse();
})->with([
    'setting.user.password',
    'setting.user.photo',
    'setting.user.uphoto',
]);
