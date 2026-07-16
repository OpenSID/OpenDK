<?php

use Tests\BrowserTestCase;
use App\Models\Pembangunan;

uses(BrowserTestCase::class);

beforeEach(function () {
    Pembangunan::query()->delete();

    $this->user = \Tests\Browser\SessionState::loginAdminUser();
});

// =============================================================================
// MENU: PEMBANGUNAN
// =============================================================================
it('smoke test menu Pembangunan', function () {
    if (Pembangunan::count() === 0) {
        Pembangunan::factory()->create();
    }

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/data/pembangunan');
    $this->page->assertPathIs('/data/pembangunan');

    $this->page->assertSee('Pembangunan');
    $this->page->assertSee('Ekspor');
    $this->page->assertPresent('select#list_desa');
    $this->page->assertVisible('#pembangunan-table');

    // Tunggu render
    sleep(2);

})->group('smoke', 'smoke-pembangunan', 'browser');
