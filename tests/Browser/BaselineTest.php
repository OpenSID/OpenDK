<?php

use Tests\BrowserTestCase;

uses(BrowserTestCase::class);

test('can reach the testing server', function () {
    visit('/')
        ->assertSee('OpenDK');
})->group('browser', 'baseline');
