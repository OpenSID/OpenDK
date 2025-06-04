<?php

namespace Tests\Feature\Http\Controllers\Data;

use App\Models\Komplain;
use App\Models\KategoriKomplain;
use App\Models\JawabKomplain;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\CrudTestCase;

class AdminKomplainControllerTest extends CrudTestCase
{
    use DatabaseTransactions;    

    public function test_index()
    {
        $response = $this->get(route('admin-komplain.index'));
        $response->assertStatus(200);
        $response->assertViewIs('sistem_komplain.index');
    }

    public function test_get_data_komplain()
    {
        KategoriKomplain::factory()->create();
        Komplain::factory()->create();
        $response = $this->getJson(route('admin-komplain.getdata'));
        $response->assertStatus(200);
        $response->assertJsonStructure(['data']);
    }

    public function test_disetujui()
    {
        $komplain = Komplain::factory()->create(['status' => 'REVIEW']);
        $response = $this->put(route('admin-komplain.setuju', $komplain->id), [
            'status' => 'SELESAI'
        ]);
        $response->assertRedirect(route('admin-komplain.index'));
        $this->assertDatabaseHas('das_komplain', [
            'id' => $komplain->id,
            'status' => 'SELESAI'
        ]);
    }

    public function test_anonim()
    {
        $komplain = Komplain::factory()->create(['anonim' => 0]);
        $response = $this->put(route('admin-komplain.anonim', $komplain->id), [
            'anonim' => 1
        ]);
        $response->assertRedirect(route('admin-komplain.index'));
        $this->assertDatabaseHas('das_komplain', [
            'id' => $komplain->id,
            'anonim' => 1
        ]);
    }

    public function test_show()
    {
        $komplain = Komplain::factory()->create();
        $response = $this->get(route('admin-komplain.show', $komplain->id));
        $response->assertStatus(200);
        $response->assertViewIs('sistem_komplain.show');
    }

    public function test_edit()
    {
        $komplain = Komplain::factory()->create();
        $response = $this->get(route('admin-komplain.edit', $komplain->id));
        $response->assertStatus(200);
        $response->assertViewIs('sistem_komplain.edit');
    }

    public function test_update()
    {
        $kategori = KategoriKomplain::factory()->create();
        $penduduk = $this->getPenduduk();
        $komplain = Komplain::factory()->create(['kategori' => $kategori->id, 'nik' => $penduduk->nik]);
        $response = $this->put(route('admin-komplain.update', $komplain->id), [
            'nik' => $komplain->nik,
            'judul' => 'Judul Baru',
            'kategori' => $kategori->id,
            'laporan' => 'Isi laporan baru',
        ]);
        $response->assertRedirect(route('admin-komplain.index'));
        $this->assertDatabaseHas('das_komplain', [
            'id' => $komplain->id,
            'judul' => 'Judul Baru'
        ]);
    }

    public function test_update_komentar()
    {
        $penduduk = $this->getPenduduk();
        $jawab = JawabKomplain::factory()->create(['penjawab' => $penduduk->nik]);
        $response = $this->put(route('admin-komplain.updatekomentar', $jawab->id), [
            'jawaban' => 'Jawaban baru'
        ]);
        $response->assertJson(['status' => 'success']);
        $this->assertDatabaseHas('das_jawab_komplain', [
            'id' => $jawab->id,
            'jawaban' => 'Jawaban baru'
        ]);
    }

    public function test_statistik()
    {        
        $response = $this->get(route('admin-komplain.statistik'));
        $response->assertStatus(200);
        $response->assertViewIs('sistem_komplain.statistik');
    }

    public function test_get_komentar()
    {
        $jawab = JawabKomplain::factory()->create();
        $response = $this->get(route('admin-komplain.getkomentar', $jawab->id));
        $response->assertJson(['status' => 'success']);
    }

    public function test_destroy()
    {
        $komplain = Komplain::factory()->create();
        $response = $this->delete(route('admin-komplain.destroy', $komplain->id));
        $response->assertRedirect(route('admin-komplain.index'));
        $this->assertDatabaseMissing('das_komplain', [
            'id' => $komplain->id
        ]);
    }

    public function test_delete_komentar()
    {
        $jawab = JawabKomplain::factory()->create();
        $response = $this->delete(route('admin-komplain.deletekomentar', $jawab->id));
        $this->assertDatabaseMissing('das_jawab_komplain', [
            'id' => $jawab->id
        ]);
    }

    private function getPenduduk()
    {        
        $penduduk = \App\Models\Penduduk::inRandomOrder()->first();
        return $penduduk ?: \App\Models\Penduduk::factory()->create();
    }
}