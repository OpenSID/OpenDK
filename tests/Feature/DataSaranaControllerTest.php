<?php

namespace Tests\Feature;

use App\Models\DataDesa;
use App\Models\DataSarana;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class DataSaranaControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_index()
    {
        $response = $this->withoutMiddleware()
            ->get(route('data.data-sarana.index'));

        $response->assertStatus(200);
    }

    public function test_create()
    {
        $response = $this->withoutMiddleware()
            ->get(route('data.data-sarana.create'));

        $response->assertStatus(200);
    }

    public function test_store()
    {
        $desa = DataDesa::factory()->create();

        $postData = [
            'desa_id' => $desa->id,
            'nama' => 'Sarana Test',
            'jumlah' => 5,
            'kategori' => 'puskesmas',
            'keterangan' => 'Keterangan test',
        ];

        $response = $this->withoutMiddleware()
            ->post(route('data.data-sarana.store'), $postData);

        $response->assertRedirect(route('data.data-sarana.index'));
        $this->assertDatabaseHas('das_data_sarana', ['nama' => 'Sarana Test']);
    }

    public function test_edit()
    {
        $sarana = DataSarana::factory()->create();

        $response = $this->withoutMiddleware()
            ->get(route('data.data-sarana.edit', $sarana->id));

        $response->assertStatus(200);
    }

    public function test_update()
    {
        $sarana = DataSarana::factory()->create();

        $updateData = [
            'desa_id' => $sarana->desa_id,
            'nama' => 'Sarana Updated',
            'jumlah' => 10,
            'kategori' => $sarana->kategori,
            'keterangan' => 'Keterangan Updated',
        ];

        $response = $this->withoutMiddleware()
            ->put(route('data.data-sarana.update', $sarana->id), $updateData);

        $response->assertRedirect(route('data.data-sarana.index'));
        $this->assertDatabaseHas('das_data_sarana', ['nama' => 'Sarana Updated']);
    }

    public function test_destroy()
    {
        $sarana = DataSarana::factory()->create();

        $response = $this->withoutMiddleware()
            ->delete(route('data.data-sarana.destroy', $sarana->id));

        $response->assertRedirect(route('data.data-sarana.index'));

        // ✅ pastikan sudah terhapus dari database
        $this->assertDatabaseMissing('das_data_sarana', [
            'id' => $sarana->id,
        ]);
    }


    public function test_export()
    {
        $response = $this->withoutMiddleware()
            ->get(route('data.data-sarana.export'));

        $response->assertStatus(200);
        $response->assertHeader('content-disposition');
    }
}
