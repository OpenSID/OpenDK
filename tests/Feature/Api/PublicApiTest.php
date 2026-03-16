<?php

use App\Models\Artikel;
use App\Models\DataDesa;
use App\Models\ArtikelKategori;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;

uses(DatabaseTransactions::class);

test('guest can access articles list', function () {
    Artikel::factory()->count(5)->create(['status' => 1]);

    $response = getJson('/api/frontend/v1/artikel');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data',
            'meta' => [
                'pagination'
            ]
        ]);
});

test('guest can filter articles by category', function () {
    $kategori = ArtikelKategori::factory()->create(['slug' => 'berita-desa']);
    Artikel::factory()->count(3)->create(['status' => 1, 'id_kategori' => $kategori->id_kategori]);
    Artikel::factory()->count(2)->create(['status' => 1, 'id_kategori' => null]);

    $response = getJson('/api/frontend/v1/artikel?filter[kategori]=berita-desa');

    $response->assertStatus(200);
    $data = $response->json('data');

    foreach ($data as $item) {
        expect($item['attributes']['id_kategori'] == $kategori->id_kategori)->toBeTrue();
    }
});

test('guest can sort articles', function () {
    Artikel::factory()->create(['judul' => 'A Artikel', 'status' => 1]);
    Artikel::factory()->create(['judul' => 'Z Artikel', 'status' => 1]);

    $response = getJson('/api/frontend/v1/artikel?sort=judul&order=asc');

    $response->assertStatus(200);
    $data = $response->json('data');

    // Find the ones we created if there's other data
    $titles = array_map(fn($item) => $item['attributes']['judul'], $data);
    $aIndex = array_search('A Artikel', $titles);
    $zIndex = array_search('Z Artikel', $titles);

    if ($aIndex !== false && $zIndex !== false) {
        expect($aIndex)->toBeLessThan($zIndex);
    }
});

test('guest can see pagination metadata', function () {
    Artikel::factory()->count(20)->create(['status' => 1]);

    $response = getJson('/api/frontend/v1/artikel?page[size]=10');

    $response->assertStatus(200)
        ->assertJsonPath('meta.pagination.per_page', 10);
});

test('guest can access desa list', function () {
    DataDesa::factory()->count(3)->create();

    $response = getJson('/api/frontend/v1/desa');

    $response->assertStatus(200)
        ->assertJsonStructure(['data']);
});

test('guest can post a comment to an article', function () {
    $artikel = Artikel::factory()->create(['status' => 1]);

    $response = postJson("/api/frontend/v1/artikel/{$artikel->id}/comments", [
        'nama' => 'Test User',
        'email' => 'test@example.com',
        'body' => 'This is a test comment',
    ]);

    $response->assertStatus(201)
        ->assertJsonPath('data.nama', 'Test User');

    $this->assertDatabaseHas('das_artikel_comment', [
        'das_artikel_id' => $artikel->id,
        'nama' => 'Test User',
        'email' => 'test@example.com',
    ]);
});

test('guest gets 404 when posting comment to non-existent article', function () {
    $response = postJson("/api/frontend/v1/artikel/99999/comments", [
        'nama' => 'Test User',
        'email' => 'test@example.com',
        'body' => 'This is a test comment',
    ]);

    $response->assertStatus(404);
});
