<?php

namespace Tests\Browser\Pages;

class DataDesaPage
{
    /**
     * Get the URL for the page.
     */
    public function url(): string
    {
        return '/data/data-desa';
    }

    /**
     * Assert that the browser is on the page.
     */
    public function assert($browser): void
    {
        $browser->assertPathIs($this->url())
            ->assertSee('Data Desa');
    }

    /**
     * Create a new village.
     */
    public function createDesa($browser, $data): void
    {
        $browser->page()->goto(rtrim(config('app.url'), '/') . $this->url() . '/create');
        $browser->page()->waitForSelector('#form-datadesa');

        $browser->page()->locator('#desa_id')->type($data['desa_id'], ['delay' => 50]);
        $browser->fill('input[name="nama"]:not([type="hidden"])', $data['nama'])
            ->fill('#sebutan_desa', $data['sebutan_desa'] ?? 'Desa')
            ->fill('#luas_wilayah', $data['luas_wilayah'])
            ->fill('#website', $data['website'] ?? '')
            ->press('Simpan');
    }

    /**
     * Edit a village.
     */
    public function editDesa($browser, $desaId, $newData): void
    {
        $browser->page()->goto(rtrim(config('app.url'), '/') . $this->url() . "/edit/{$desaId}");
        $browser->page()->waitForSelector('#form-datadesa');

        $browser->fill('input[name="nama"]:not([type="hidden"])', $newData['nama'])
            ->fill('#luas_wilayah', $newData['luas_wilayah'])
            ->press('Simpan');
    }

    /**
     * Delete a village.
     */
    public function deleteDesa($browser, $recordId): void
    {
        $browser->page()->waitForSelector('#datadesa-table');
        $browser->click("a#deleteModal[data-href*='/{$recordId}']");
        $browser->page()->waitForSelector('#delete-modal', ['state' => 'visible']);
        $browser->click("#delete-modal button[type='submit']");
    }
}
