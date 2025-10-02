<?php

namespace Tests\Feature;

use App\Models\DataDesa;
use App\Models\DataSarana;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImportDataSarana;

class DataSaranaControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_index()
    {
        $response = $this->withoutMiddleware()
            ->withSession(['error' => 'Index'])
            ->get(route('data.data-sarana.index'));

        $response->assertStatus(200);
        $response->assertSee('Index');
    }

    public function test_getData()
    {
        $desa = DataDesa::factory()->create();
        DataSarana::factory()->create([
            'desa_id' => $desa->id,
            'nama' => 'Sarana GetData',
            'jumlah' => 2,
            'kategori' => 'paud',
        ]);

        $response = $this->withoutMiddleware()
            ->get(route('data.data-sarana.getdata'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data',
            'recordsTotal',
            'recordsFiltered'
        ]);

        $this->assertStringContainsString('Sarana GetData', $response->getContent());
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

    public function test_import_page()
    {
        $response = $this->withoutMiddleware()
            ->get(route('data.data-sarana.import'));

        $response->assertStatus(200);
        $response->assertSee('Upload');
    }

    public function test_import_excel_real_file()
    {
        Storage::fake('local');

        // bikin file CSV beneran
        $csvContent = "desa_id,nama,jumlah,kategori,keterangan\n1,Posyandu,5,puskesmas,Test Import";
        $path = storage_path('framework/testing/sarana.csv');
        file_put_contents($path, $csvContent);

        $file = new UploadedFile(
            $path,
            'sarana.csv',
            'text/csv',
            null,
            true
        );

        $response = $this->withoutMiddleware()
            ->post(route('data.data-sarana.import-excel'), [
                'file' => $file,
            ]);

        $response->assertRedirect();
    }



}
