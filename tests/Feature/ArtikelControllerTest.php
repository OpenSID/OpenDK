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

namespace Tests\Feature;

use App\Models\Artikel;
use App\Models\ArtikelKategori;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ArtikelControllerTest extends TestCase
{
    use DatabaseTransactions, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test data
        $this->createTestArtikel();
    }

    /**
     * Test getting all articles.
     *
     * @return void
     */
    public function test_can_get_all_articles()
    {
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
    }

    /**
     * Test getting articles with pagination.
     *
     * @return void
     */
    public function test_can_get_articles_with_pagination()
    {
        $response = $this->getJson('/api/frontend/v1/artikel?page[number]=1&page[size]=5');

        $response->assertStatus(200)
            ->assertJsonPath('meta.pagination.per_page', 5)
            ->assertJsonPath('meta.pagination.current_page', 1);
    }

    /**
     * Test getting articles with sorting.
     *
     * @return void
     */
    public function test_can_get_articles_with_sorting()
    {
        $response = $this->getJson('/api/frontend/v1/artikel?sort=judul&order=asc');

        $response->assertStatus(200);
    }

    /**
     * Test getting articles with filtering by category.
     *
     * @return void
     */
    public function test_can_get_articles_with_filter_by_category()
    {
        $response = $this->getJson('/api/frontend/v1/artikel?filter[kategori]=berita');

        $response->assertStatus(200);
    }

    /**
     * Test getting articles with filtering by status.
     *
     * @return void
     */
    public function test_can_get_articles_with_filter_by_status()
    {
        $response = $this->getJson('/api/frontend/v1/artikel?filter[status]=1');

        $response->assertStatus(200);
    }

    /**
     * Test getting articles with search.
     *
     * @return void
     */
    public function test_can_get_articles_with_search()
    {
        $response = $this->getJson('/api/frontend/v1/artikel?search=test');

        $response->assertStatus(200);
    }

    /**
     * Test getting articles with include kategori.
     *
     * @return void
     */
    public function test_can_get_articles_with_include_kategori()
    {
        $response = $this->getJson('/api/frontend/v1/artikel?include=kategori');

        $response->assertStatus(200);
    }

    /**
     * Test getting articles with include comments.
     *
     * @return void
     */
    public function test_can_get_articles_with_include_comments()
    {
        $response = $this->getJson('/api/frontend/v1/artikel?include=comments');

        $response->assertStatus(200);
    }

    /**
     * Test getting articles with field selection.
     *
     * @return void
     */
    public function test_can_get_articles_with_fields()
    {
        $response = $this->getJson('/api/frontend/v1/artikel?fields=id,judul,slug,created_at');

        $response->assertStatus(200);
    }

    /**
     * Test getting single article by ID.
     *
     * @return void
     */
    public function test_can_get_single_article_by_id()
    {
        $artikel = Artikel::first();

        $response = $this->getJson("/api/frontend/v1/artikel?filter[id]={$artikel->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    [
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
                ]
            ]);
    }

    /**
     * Test getting single article by ID with includes.
     *
     * @return void
     */
    public function test_can_get_single_article_by_id_with_includes()
    {
        $artikel = Artikel::first();

        $response = $this->getJson("/api/frontend/v1/artikel?filter[id]=$artikel->id}?include=kategori,comments");

        $response->assertStatus(200);
    }


    /**
     * Test validation error for invalid sort field.
     *
     * @return void
     */
    public function test_validation_error_for_invalid_sort_field()
    {
        $response = $this->getJson('/api/frontend/v1/artikel?sort=invalid_field');

        $response->assertStatus(400);
    }

    /**
     * Create test artikel data.
     *
     * @return void
     */
    private function createTestArtikel()
    {
        // Create category first
        $kategori = ArtikelKategori::factory()->create([]);

        // Create articles
        Artikel::create([
            'judul' => 'Test Artikel 1',
            'slug' => 'test-artikel-1',
            'isi' => 'Ini adalah konten test artikel 1',
            'id_kategori' => $kategori->id_kategori,
            'kategori_id' => $kategori->id_kategori,
            'gambar' => '/storage/test/image1.jpg',
            'status' => 1,
        ]);

        Artikel::create([
            'judul' => 'Test Artikel 2',
            'slug' => 'test-artikel-2',
            'isi' => 'Ini adalah konten test artikel 2',
            'id_kategori' => $kategori->id_kategori,
            'kategori_id' => $kategori->id_kategori,
            'gambar' => '/storage/test/image2.jpg',
            'status' => 1,
        ]);

        Artikel::create([
            'judul' => 'Test Artikel 3',
            'slug' => 'test-artikel-3',
            'isi' => 'Ini adalah konten test artikel 3',
            'id_kategori' => null,
            'kategori_id' => null,
            'gambar' => '/storage/test/image3.jpg',
            'status' => 0, // draft
        ]);
    }
}
