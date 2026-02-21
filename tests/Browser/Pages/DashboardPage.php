<?php

namespace Tests\Browser\Pages;

class DashboardPage
{
    /**
     * Get the URL for the page.
     */
    public function url(): string
    {
        return '/dashboard';
    }

    /**
     * Assert that the browser is on the page.
     */
    public function assert($browser): void
    {
        $browser->assertPathIs($this->url())
            ->assertSee('Dashboard')
            ->assertPresent('.content-wrapper');
    }

    /**
     * Get the element shortcuts for the page.
     */
    public function elements(): array
    {
        return [
            '@desa_card' => '.small-box:contains("Desa")',
            '@penduduk_card' => '.small-box:contains("Penduduk")',
            '@keluarga_card' => '.small-box:contains("Keluarga")',
            '@bantuan_card' => '.small-box:contains("Bantuan")',
            '@sidebar' => '.main-sidebar',
            '@user-menu' => '.user-menu',
            '@logout-button' => '#logout-form button',
        ];
    }

    /**
     * Logout the user.
     */
    public function logout($browser): void
    {
        $browser->click('.user-menu')
            ->waitFor('#logout-form button', 5)
            ->click('#logout-form button');
    }
}
