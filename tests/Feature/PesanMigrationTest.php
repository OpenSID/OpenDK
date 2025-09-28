<?php

namespace Tests\Feature;

use App\Models\DataDesa;
use App\Models\Pesan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PesanMigrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function pesan_can_use_kode_desa_instead_of_id()
    {
        // Create a DataDesa with specific desa_id (kode_desa)
        $dataDesa = DataDesa::factory()->create([
            'desa_id' => '1234567890',
            'nama' => 'Desa Test'
        ]);

        // Create a Pesan using kode_desa
        $pesan = Pesan::factory()->create([
            'das_data_desa_id' => '1234567890',
            'judul' => 'Test Pesan'
        ]);

        // Test relationship works with kode_desa
        $this->assertNotNull($pesan->dataDesa);
        $this->assertEquals('Desa Test', $pesan->dataDesa->nama);
        $this->assertEquals('1234567890', $pesan->dataDesa->desa_id);
    }

    /** @test */
    public function das_data_desa_id_is_varchar_type()
    {
        $pesan = Pesan::factory()->create([
            'das_data_desa_id' => 'ABC123DEF456' // Test with alphanumeric
        ]);

        $this->assertIsString($pesan->das_data_desa_id);
        $this->assertEquals('ABC123DEF456', $pesan->das_data_desa_id);
    }

    /** @test */
    public function pesan_relationship_with_data_desa_works()
    {
        // Create multiple DataDesa
        $desa1 = DataDesa::factory()->create(['desa_id' => '1111111111', 'nama' => 'Desa Satu']);
        $desa2 = DataDesa::factory()->create(['desa_id' => '2222222222', 'nama' => 'Desa Dua']);

        // Create Pesan for each desa
        $pesan1 = Pesan::factory()->create(['das_data_desa_id' => '1111111111']);
        $pesan2 = Pesan::factory()->create(['das_data_desa_id' => '2222222222']);

        // Test relationships
        $this->assertEquals('Desa Satu', $pesan1->dataDesa->nama);
        $this->assertEquals('Desa Dua', $pesan2->dataDesa->nama);
    }

    /** @test */
    public function pesan_can_handle_null_desa_id()
    {
        $pesan = Pesan::factory()->create(['das_data_desa_id' => null]);

        $this->assertNull($pesan->dataDesa);
    }

    /** @test */
    public function pesan_handles_nonexistent_desa_id()
    {
        $pesan = Pesan::factory()->create(['das_data_desa_id' => 'NONEXISTENT']);

        $this->assertNull($pesan->dataDesa);
    }
}