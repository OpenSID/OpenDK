<?php

use App\Models\Album;
use App\Models\Galeri;
use App\Models\SettingAplikasi;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Cache;

/*
|--------------------------------------------------------------------------
| Halaman Galeri Publik
|--------------------------------------------------------------------------
|
| Tema web memuat media galeri lewat JavaScript, namun URL dasar media harus
| benar-benar valid agar gambar tidak gagal dimuat di browser.
|
*/

uses(DatabaseTransactions::class);

beforeEach(function () {
    SettingAplikasi::updateOrCreate(
        ['key' => 'sinkronisasi_database_gabungan'],
        ['value' => '0']
    );

    Cache::flush();

    $this->album = Album::factory()->create([
        'judul' => 'Album Publik',
        'slug' => 'album-publik',
    ]);
});

it('renders the public galeri list page', function () {
    $response = $this->get(route('publik.publikasi.galeri', 'album-publik'));

    $response->assertStatus(200);
    $response->assertSee('galeri-container', false);
});

it('renders the public galeri detail page', function () {
    $response = $this->get(route('publik.publikasi.galeri.detail', 'album-publik'));

    $response->assertStatus(200);
    $response->assertSee('galeri-images-container', false);
});

it('builds the storage base url without a double slash', function () {
    $response = $this->get(route('publik.publikasi.galeri.detail', 'album-publik'));

    // asset() membuang garis miring di akhir path, sehingga pemisah harus
    // ditambahkan eksplisit. Tanpa itu nama berkas akan menempel ke folder.
    $response->assertSee(json_encode(asset('storage/publikasi/galeri')), false)
        ->assertSee("storageBaseUrl + '/' + item", false);

    // Dua garis miring sebelum folder akan membuat gambar gagal dimuat.
    $response->assertDontSee('//storage/publikasi/galeri/');
});

it('loads the shared media rendering helper on the galeri pages', function () {
    $response = $this->get(route('publik.publikasi.galeri.detail', 'album-publik'));

    $response->assertSee('custom.js', false);
});
