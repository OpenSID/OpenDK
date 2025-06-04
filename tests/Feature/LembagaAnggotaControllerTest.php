<?php

namespace Tests\Feature\Http\Controllers\Data;

use App\Models\Lembaga;
use App\Models\LembagaAnggota;
use App\Models\Penduduk;
use Faker\Generator;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\CrudTestCase;

class LembagaAnggotaControllerTest extends CrudTestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_can_create_lembaga_anggota()
    {
        $lembaga = Lembaga::factory()->create();
        $penduduk = Penduduk::inRandomOrder()->first();

        $response = $this->post(route('data.lembaga_anggota.store', $lembaga->slug), [
            'penduduk_id' => $penduduk->id,
            'no_anggota' => '001',
            'jabatan_id' => 1,
            'no_sk_jabatan' => 'SK001',
            'no_sk_pengangkatan' => 'SKP001',
            'tgl_sk_pengangkatan' => now()->toDateString(),
            'no_sk_pemberhentian' => null,
            'tgl_sk_pemberhentian' => null,
            'periode' => '2024-2025',
            'keterangan' => 'Keterangan test',
        ]);

        $response->assertRedirect(route('data.lembaga_anggota.index', $lembaga->slug));
        $this->assertDatabaseHas('das_lembaga_anggota', [
            'lembaga_id' => $lembaga->id,
            'penduduk_id' => $penduduk->id,
            'no_anggota' => '001',
        ]);
    }

    /** @test */
    public function it_can_update_lembaga_anggota()
    {
        $lembaga = Lembaga::factory()->create();
        $penduduk = Penduduk::inRandomOrder()->first();
        $anggota = LembagaAnggota::factory()->create([
            'lembaga_id' => $lembaga->id,
            'penduduk_id' => $penduduk->id,            
        ]);
        
        $jabatanBaru = (new Generator())->numberBetween(1, 5); // gunakan faker; // Assuming Jabatan factory exists
        $response = $this->put(route('data.lembaga_anggota.update', [$lembaga->slug, $anggota->id]), [
            'no_anggota' => '002',
            'jabatan_id' => $jabatanBaru, // Assuming Jabatan factory exists            
        ]);

        $response->assertRedirect(route('data.lembaga_anggota.index', $lembaga->slug));
        $this->assertDatabaseHas('das_lembaga_anggota', [
            'id' => $anggota->id,
            'no_anggota' => '002',
            'jabatan' => $jabatanBaru,
        ]);
    }

    /** @test */
    public function it_can_delete_lembaga_anggota()
    {
        $lembaga = Lembaga::factory()->create();
        $penduduk = Penduduk::inRandomOrder()->first();
        $anggota = LembagaAnggota::factory()->create([
            'lembaga_id' => $lembaga->id,
            'penduduk_id' => $penduduk->id,
        ]);

        $response = $this->delete(route('data.lembaga_anggota.destroy', [$lembaga->slug, $anggota->id]));

        $response->assertRedirect(route('data.lembaga_anggota.index', $lembaga->slug));
        $this->assertDatabaseMissing('das_lembaga_anggota', [
            'id' => $anggota->id,
        ]);
    }
}
