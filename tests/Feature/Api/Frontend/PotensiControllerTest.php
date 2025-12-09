<?php

namespace Tests\Feature\Api\Frontend;

use App\Models\Potensi;
use App\Models\TipePotensi;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\CrudTestCase;

class PotensiControllerTest extends CrudTestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test data
        $tipePotensi = TipePotensi::create([
            'nama_kategori' => 'Test Kategori',
            'slug' => 'test-kategori',
        ]);

        Potensi::create([
            'kategori_id' => $tipePotensi->id,
            'nama_potensi' => 'Potensi 1',
            'deskripsi' => 'Deskripsi potensi 1',
            'lokasi' => 'Lokasi potensi 1',
            'file_gambar' => 'storage/potensi_kecamatan//cDEnWmVEkFlBvIIEDiJxRba4wH2tsRaurHLvIydW.png',
            'long' => null,
            'lat' => null,
        ]);

        Potensi::create([
            'kategori_id' => $tipePotensi->id,
            'nama_potensi' => 'Potensi 2',
            'deskripsi' => 'Deskripsi potensi 2',
            'lokasi' => 'Lokasi potensi 2',
            'file_gambar' => '/img/no-image.png',
            'long' => null,
            'lat' => null,
        ]);
    }

    /**
     * Test that the API returns the correct structure
     */
    public function test_potensi_api_returns_correct_structure()
    {
        $response = $this->getJson('/api/frontend/v1/potensi');

        $response->assertStatus(200);

        // Check main structure
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'type',
                    'id',
                    'attributes' => [
                        'kategori_id',
                        'nama_potensi',
                        'deskripsi',
                        'lokasi',
                        'file_gambar',
                        'long',
                        'lat',
                        'created_at',
                        'updated_at',
                        'file_gambar_path',
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

        // Check specific values
        $data = $response->json('data');
        $this->assertEquals('potensi', $data[0]['type']);
        $this->assertIsString($data[0]['id']);
        $this->assertEquals('Potensi 1', $data[0]['attributes']['nama_potensi']);
        $this->assertStringContainsString('storage/potensi_kecamatan//cDEnWmVEkFlBvIIEDiJxRba4wH2tsRaurHLvIydW.png', $data[0]['attributes']['file_gambar_path']);
    }

    /**
     * Test pagination
     */
    public function test_potensi_api_pagination()
    {
        $response = $this->getJson('/api/frontend/v1/potensi?page[number]=1&page[size]=1');

        $response->assertStatus(200);
        
        $pagination = $response->json('meta.pagination');
        $this->assertEquals(2, $pagination['total']);
        $this->assertEquals(1, $pagination['count']);
        $this->assertEquals(1, $pagination['per_page']);
        $this->assertEquals(1, $pagination['current_page']);
        $this->assertEquals(2, $pagination['total_pages']);
    }
}