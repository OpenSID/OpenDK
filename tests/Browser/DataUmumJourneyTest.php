<?php

use Tests\BrowserTestCase;
use Tests\Browser\Pages\DashboardPage;
use Tests\Browser\Pages\DataUmumPage;

uses(BrowserTestCase::class);

test('admin can update data umum info wilayah', function () {
    // 0. Setup: Ensure Admin credentials
    $admin = \App\Models\User::where('email', 'admin@mail.com')->first();
    expect($admin)->not->toBeNull();
    $admin->update(['password' => 'Password123!']);

    $dashboardPage = new DashboardPage();
    $dataUmumPage = new DataUmumPage();

    $testData = [
        'tipologi' => 'Tipologi Test ' . time(),
        'sejarah' => 'Sejarah Test ' . time(),
        'ketinggian' => '100',
        'sumber_luas_wilayah' => '1',
        'luas_wilayah' => '500',
        'bts_wil_utara' => 'North Border',
        'bts_wil_timur' => 'East Border',
        'bts_wil_selatan' => 'South Border',
        'bts_wil_barat' => 'West Border',
        'jml_puskesmas' => '1',
        'jml_puskesmas_pembantu' => '2',
        'jml_posyandu' => '3',
        'jml_pondok_bersalin' => '4',
        'jml_paud' => '5',
        'jml_sd' => '6',
        'jml_smp' => '7',
        'jml_sma' => '8',
        'jml_masjid_besar' => '9',
        'jml_mushola' => '10',
        'jml_gereja' => '11',
        'jml_pasar' => '12',
        'jml_balai_pertemuan' => '13',
        'lat' => '1.234',
        'lng' => '5.678',
    ];

    try {
        // 1. Initial State: Login as Admin

        $this->actingAs($admin);
        $browser = visit($dashboardPage->url());
        $browser->waitForText('Dashboard')
            ->assertSee('Dashboard');

        // 2. Journey: Navigate to Data Umum

        $url = rtrim(config('app.url'), '/') . $dataUmumPage->url();
        $browser->page()->goto($url);

        // 3. Journey: Update Info Wilayah

        $dataUmumPage->updateInfoWilayah($browser, $testData);

        // 4. Verification: Success Message

        $browser->waitForText('sukses')
            ->assertSee('Update Data Umum sukses!');

        // 5. Verification: Persistence

        $url = rtrim(config('app.url'), '/') . $dataUmumPage->url();
        $browser->page()->goto($url);
        $browser->waitForText('Info Wilayah')
            ->assertValue('ketinggian', $testData['ketinggian'])
            ->assertValue('luas_wilayah', $testData['luas_wilayah'])
            ->assertSeeIn('#wilayah', $testData['bts_wil_utara'])
            ->assertSeeIn('#wilayah', $testData['bts_wil_timur'])
            ->assertSeeIn('#wilayah', $testData['bts_wil_selatan'])
            ->assertSeeIn('#wilayah', $testData['bts_wil_barat'])
            ->assertValue('jml_puskesmas', $testData['jml_puskesmas'])
            ->assertValue('jml_sd', $testData['jml_sd']);

        // Check lat/lng on the other tab
        $browser->click('a[href="#lokasi-kantor"]')
            ->wait(0.5)
            ->assertValue('lat', $testData['lat'])
            ->assertValue('lng', $testData['lng']);



    } catch (\Throwable $e) {
        $msg = $e->getMessage();
        $currentUrl = (isset($browser)) ? $browser->url() : 'unknown';

        $errorDetails = "FAILED AT URL: $currentUrl\n";
        $errorDetails .= "ERROR: $msg\n";
        $errorDetails .= "TRACE:\n" . $e->getTraceAsString() . "\n";

        file_put_contents(base_path('storage/logs/failure_data_umum.txt'), $errorDetails);

        if (isset($browser)) {
            try {
                file_put_contents(base_path('storage/logs/failure_data_umum.html'), $browser->content());
            } catch (\Exception $ex) {
                // Ignore error during HTML capture
            }
        }
        throw $e;
    }
})->group('browser', 'journey', 'data-umum');
