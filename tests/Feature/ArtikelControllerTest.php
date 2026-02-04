<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package    OpenDK
 * @author     Tim Pengembang OpenDesa
 * @copyright  Hak Cipta 2017 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

use App\Models\Artikel;
use App\Models\ArtikelKategori;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

beforeEach(function () {
    // Create test data
    createTestArtikel();
});

/**
 * Create test artikel data.
 */
function createTestArtikel(): void
{
    // Create category first
    $kategori = ArtikelKategori::factory()->create([]);

    // Create articles
    Artikel::create([
        'judul' => 'Test Artikel 1',
        'slug' => 'test-artikel-1',
        'isi' => 'Ini adalah konten test artikel 1',
        'id_kategori' => $kategori->id_kategori,
        'gambar' => '/storage/test/image1.jpg',
        'status' => 1,
    ]);

    Artikel::create([
        'judul' => 'Test Artikel 2',
        'slug' => 'test-artikel-2',
        'isi' => 'Ini adalah konten test artikel 2',
        'id_kategori' => $kategori->id_kategori,
        'gambar' => '/storage/test/image2.jpg',
        'status' => 1,
    ]);

    Artikel::create([
        'judul' => 'Test Artikel 3',
        'slug' => 'test-artikel-3',
        'isi' => 'Ini adalah konten test artikel 3',
        'id_kategori' => null,
        'gambar' => '/storage/test/image3.jpg',
        'status' => 0, // draft
    ]);
}

test('can get all articles', function () {
    $response = $this->getJson('/api/frontend/v1/artikel');

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'type',
                    'id',
                    'attributes' => [
                        'id_kategori',
                        'slug',
                        'judul',
                        'kategori_id',
                        'gambar',
                        'isi',
                        'status',
                        'created_at',
                        'updated_at'
                    ]
                ]
            ],
            'meta' => [
                'pagination' => [
                    'total',
                    'count',
                    'per_page',
                    'current_page',
                    'total_pages'
                ]
            ],
            'links'
        ]);
});

test('can get articles with pagination', function () {
    $response = $this->getJson('/api/frontend/v1/artikel?page[number]=1&page[size]=5');

    $response->assertStatus(200)
        ->assertJsonPath('meta.pagination.per_page', 5)
        ->assertJsonPath('meta.pagination.current_page', 1);
});

test('can get articles with sorting', function () {
    $response = $this->getJson('/api/frontend/v1/artikel?sort=judul&order=asc');

    $response->assertStatus(200);
});

test('can get articles with filter by category', function () {
    $response = $this->getJson('/api/frontend/v1/artikel?filter[kategori]=berita');

    $response->assertStatus(200);
});

test('can get articles with filter by status', function () {
    $response = $this->getJson('/api/frontend/v1/artikel?filter[status]=1');

    $response->assertStatus(200);
});

test('can get articles with search', function () {
    $response = $this->getJson('/api/frontend/v1/artikel?search=test');

    $response->assertStatus(200);
});

test('can get articles with include kategori', function () {
    $response = $this->getJson('/api/frontend/v1/artikel?include=kategori');

    $response->assertStatus(200);
});

test('can get articles with include comments', function () {
    $response = $this->getJson('/api/frontend/v1/artikel?include=comments');

    $response->assertStatus(200);
});

test('can get articles with fields', function () {
    $response = $this->getJson('/api/frontend/v1/artikel?fields=id,judul,slug,created_at');

    $response->assertStatus(200);
});

test('can store comment for article', function () {
    $artikel = Artikel::first();

    $response = $this->postJson("/api/frontend/v1/artikel/{$artikel->id}/comments", [
        'nama' => 'Test User',
        'email' => 'test@example.com',
        'body' => 'Ini adalah komentar test',
    ]);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'data' => [
                'id',
                'das_artikel_id',
                'status',
                'nama',
                'email',
                'body',
                'comment_id',
                'ip_address',
                'device',
                'created_at',
                'updated_at'
            ]
        ]);
});

test('store comment with invalid data returns validation error', function () {
    $artikel = Artikel::first();

    $response = $this->postJson("/api/frontend/v1/artikel/{$artikel->id}/comments", [
        'nama' => '',
        'email' => 'invalid-email',
        'body' => '',
    ]);

    $response->assertStatus(422)
        ->assertJsonStructure([
            'errors'
        ]);
});

test('store comment for non existent article returns 404', function () {
    $response = $this->postJson("/api/frontend/v1/artikel/999999/comments", [
        'nama' => 'Test User',
        'email' => 'test@example.com',
        'body' => 'Ini adalah komentar test',
    ]);

    $response->assertStatus(404)
        ->assertJson([
            'errors' => [
                'message' => 'Artikel not found'
            ]
        ]);
});

test('validation error for invalid sort field', function () {
    $response = $this->getJson('/api/frontend/v1/artikel?sort=invalid_field');

    $response->assertStatus(400);
});
