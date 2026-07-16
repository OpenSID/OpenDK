<?php

use Tests\BrowserTestCase;
use App\Models\Program;

uses(BrowserTestCase::class);

beforeEach(function () {
    Program::query()->delete();

    $this->user = \Tests\Browser\SessionState::loginAdminUser();
});

// =============================================================================
// MENU: PROGRAM BANTUAN
// =============================================================================
it('smoke test menu Program Bantuan', function () {
    if (Program::count() === 0) {
        Program::factory()->create();
    }

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/data/program-bantuan');
    $this->page->assertPathIs('/data/program-bantuan');

    $this->page->assertSee('Program Bantuan');
    $this->page->assertSee('Ekspor');
    $this->page->assertPresent('select#list_desa');
    $this->page->assertVisible('#program-table');

    // Tunggu render
    sleep(2);

})->group('smoke', 'smoke-program-bantuan', 'browser');
