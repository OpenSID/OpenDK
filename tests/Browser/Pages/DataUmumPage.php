<?php

namespace Tests\Browser\Pages;

class DataUmumPage
{
    /**
     * Get the URL for the page.
     */
    public function url(): string
    {
        return '/data/data-umum';
    }

    /**
     * Update the info wilayah.
     */
    public function updateInfoWilayah($browser, array $data): void
    {
        $browser->waitForText('Info Wilayah');

        // Set TinyMCE content via JS
        $this->setTinyMCEContent($browser, 'tipologi', $data['tipologi']);
        $this->setTinyMCEContent($browser, 'sejarah', $data['sejarah']);

        $browser->type('ketinggian', $data['ketinggian'])
            ->select('sumber_luas_wilayah', $data['sumber_luas_wilayah'])
            ->type('luas_wilayah', $data['luas_wilayah'])
            ->type('bts_wil_utara', $data['bts_wil_utara'])
            ->type('bts_wil_timur', $data['bts_wil_timur'])
            ->type('bts_wil_selatan', $data['bts_wil_selatan'])
            ->type('bts_wil_barat', $data['bts_wil_barat'])
            ->type('jml_puskesmas', $data['jml_puskesmas'])
            ->type('jml_puskesmas_pembantu', $data['jml_puskesmas_pembantu'])
            ->type('jml_posyandu', $data['jml_posyandu'])
            ->type('jml_pondok_bersalin', $data['jml_pondok_bersalin'])
            ->type('jml_paud', $data['jml_paud'])
            ->type('jml_sd', $data['jml_sd'])
            ->type('jml_smp', $data['jml_smp'])
            ->type('jml_sma', $data['jml_sma'])
            ->type('jml_masjid_besar', $data['jml_masjid_besar'])
            ->type('jml_mushola', $data['jml_mushola'])
            ->type('jml_gereja', $data['jml_gereja'])
            ->type('jml_pasar', $data['jml_pasar'])
            ->type('jml_balai_pertemuan', $data['jml_balai_pertemuan']);

        // Switch tab for lat/lng
        $browser->click('a[href="#lokasi-kantor"]')
            ->wait(0.5)
            ->type('lat', $data['lat'])
            ->type('lng', $data['lng'])
            ->press('Simpan');
    }

    /**
     * Helper to set TinyMCE content.
     */
    public function setTinyMCEContent($browser, $id, $content): void
    {
        // Wait a bit for editor to potentially initialize
        $browser->wait(0.5);
        $browser->page()->evaluate("async ({id, content}) => {
            const trySetTinyMCE = () => {
                try {
                    if (typeof tinymce !== 'undefined') {
                        const editor = tinymce.get(id);
                        if (editor && editor.initialized) {
                            // Using execCommand is sometimes safer than setContent
                            editor.execCommand('mceSetContent', false, content);
                            editor.save();
                            return true;
                        }
                    }
                } catch (e) { console.warn('TinyMCE error:', e); }
                return false;
            };

            if (!trySetTinyMCE()) {
                // Wait a bit more
                await new Promise(r => setTimeout(r, 600));
                if (!trySetTinyMCE()) {
                    const el = document.getElementById(id);
                    if (el) {
                        el.value = content;
                        el.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                }
            }
        }", ['id' => $id, 'content' => $content]);
    }
}
