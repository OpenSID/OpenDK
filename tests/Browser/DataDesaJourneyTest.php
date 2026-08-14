<?php

use Tests\BrowserTestCase;
use Tests\Browser\Pages\LoginPage;
use Tests\Browser\Pages\DataDesaPage;
use App\Models\User;
use App\Models\Profil;

uses(BrowserTestCase::class);

test('admin can perform CRUD on data desa', function () {
    $user = User::factory()->create([
        'status' => 1,
    ]);
    $user->assignRole('super-admin');

    // Clear cache to ensure profil/settings are fresh
    \Illuminate\Support\Facades\Cache::flush();
    config(['app.server_pantau' => 'http://127.0.0.1:65535']); // Unreachable to force status_pantau = 0

    $loginPage = new LoginPage();
    $dataDesaPage = new DataDesaPage();

    // Get kecamatan_id to generate a valid desa_id
    $profil = Profil::first();
    if (!$profil->kecamatan_id) {
        $profil->update(['kecamatan_id' => '32.01.01']);
        Cache::forget('profil');
    }
    $kecamatanId = $profil->kecamatan_id;
    $testDesaId = $kecamatanId . '.2001'; // 8 chars + . + 4 chars = 13 chars

    $testData = [
        'desa_id' => $testDesaId,
        'nama' => 'Desa Test E2E',
        'sebutan_desa' => 'Desa',
        'luas_wilayah' => '15.5',
        'website' => 'https://desa-test-e2e.id',
    ];

    $updatedData = [
        'nama' => 'Desa Baru Berkembang',
        'luas_wilayah' => '20.0',
    ];

    // 1. Login
    $this->actingAs($user);
    $browser = visit($dataDesaPage->url());
    $browser->page()->waitForSelector('#datadesa-table');

    // 2. Create
    $dataDesaPage->createDesa($browser, $testData);
    $browser->waitForText('Data Desa berhasil disimpan!')
        ->assertSee('Data Desa berhasil disimpan!');

    // Fetch the actual record ID from database
    $desa = \App\Models\DataDesa::where('desa_id', $testData['desa_id'])->first();
    $recordId = $desa->id;

    // 3. Verify in list
    $browser->waitForText($testData['nama'])
        ->assertSee($testData['nama'])
        ->assertSee($testData['desa_id']);

    // 4. Update
    $dataDesaPage->editDesa($browser, $recordId, $updatedData);
    $browser->waitForText('Data Desa berhasil disimpan!')
        ->assertSee('Data Desa berhasil disimpan!');

    // 5. Verify updated data
    $browser->waitForText($updatedData['nama'])
        ->assertSee($updatedData['nama'])
        ->assertDontSee($testData['nama']);

    // 6. Delete
    $dataDesaPage->deleteDesa($browser, $recordId);
    $browser->waitForText('Data Desa sukses dihapus!')
        ->assertSee('Data Desa sukses dihapus!');

    // 7. Final verification
    $browser->assertDontSee($updatedData['nama']);
});

test('admin can sync data desa from external source', function () {
    $user = User::factory()->create([
        'status' => 1,
    ]);
    $user->assignRole('super-admin');

    // Clear cache to ensure profil/settings are fresh
    \Illuminate\Support\Facades\Cache::flush();

    // Set host_pantau to 'mock' to trigger the internal sync bypass in DataDesaController
    config(['app.host_pantau' => 'mock']);

    $dataDesaPage = new DataDesaPage();

    // Ensure we have a kecamatan_id set
    $profil = Profil::first();
    $profil->update(['kecamatan_id' => '32.01.01']);

    // Login and navigate to Data Desa
    $this->actingAs($user);
    $browser = visit($dataDesaPage->url());
    $browser->page()->waitForSelector('#datadesa-table');

    // Trigger Sync
    $browser->click('button[title="Ambil Data Desa Dari TrackSID"]')
        ->waitForText('Data Desa berhasil ditambahkan')
        ->assertSee('Data Desa berhasil ditambahkan');

    // Verify synced data appears in the table
    $browser->waitForText('Desa Sync Test 1')
        ->assertSee('Desa Sync Test 1')
        ->assertSee('32.01.01.2005')
        ->assertSee('Desa Sync Test 2')
        ->assertSee('32.01.01.2006');
})->group('browser', 'datadesa');
