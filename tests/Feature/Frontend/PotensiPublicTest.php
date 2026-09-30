<?php

use App\Models\NavMenu;
use App\Models\Potensi;
use App\Models\TipePotensi;
use Illuminate\Support\Facades\Cache;

beforeEach(function () {
    Cache::flush();
    $this->tipePotensi = TipePotensi::firstOrCreate(
        ['slug' => 'pariwisata'],
        ['nama_kategori' => 'Pariwisata']
    );

    $this->potensi = Potensi::firstOrCreate(
        ['nama_potensi' => 'Air Terjun Indah'],
        [
            'kategori_id' => $this->tipePotensi->id,
            'deskripsi'   => 'Deskripsi wisata alam air terjun indah',
            'lokasi'      => 'Kecamatan Contoh',
            'file_gambar' => 'storage/potensi_kecamatan/sample.jpg',
        ]
    );
});

it('displays all potensi page at /potensi successfully', function () {
    $response = $this->get('/potensi');

    $response->assertOk();
    $response->assertViewIs('pages.potensi.index');
    $response->assertViewHas('page_title', 'Potensi');
    $response->assertViewHas('kategori_potensi');
});

it('displays potensi by category page at /potensi/{slug} successfully', function () {
    $response = $this->get('/potensi/' . $this->tipePotensi->slug);

    $response->assertOk();
    $response->assertViewIs('pages.potensi.index');
    $response->assertViewHas('slug', $this->tipePotensi->slug);
    $response->assertViewHas('kategori_potensi');
});

it('displays potensi detail page at /potensi/{kategori}/{id} successfully', function () {
    $response = $this->get('/potensi/' . $this->tipePotensi->slug . '/' . $this->potensi->id);

    $response->assertOk();
    $response->assertViewIs('pages.potensi.show');
    $response->assertViewHas('id', (string) $this->potensi->id);
});

it('ensures nav_menus table contains /potensi for Potensi menu', function () {
    $nav = NavMenu::where('name', 'Potensi')->first();

    expect($nav)->not->toBeNull();
    expect($nav->url)->toBe('/potensi');
});

it('returns /potensi in the website api navmenus data', function () {
    $response = $this->getJson('/api/frontend/v1/website');

    $response->assertOk();
    $data = $response->json('data');

    $navmenusItem = collect($data)->firstWhere('id', 'navmenus');
    expect($navmenusItem)->not->toBeNull();

    $menus = $navmenusItem['attributes'][0] ?? [];
    $potensiMenu = collect($menus)->firstWhere('name', 'Potensi');

    expect($potensiMenu)->not->toBeNull();
    expect($potensiMenu['url'])->toBe('/potensi');
});
