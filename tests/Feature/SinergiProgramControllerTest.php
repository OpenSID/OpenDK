<?php

namespace Tests\Feature;

use App\Models\SinergiProgram;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\CrudTestCase;
use Tests\TestCase;

class SinergiProgramControllerTest extends CrudTestCase
{
    use DatabaseTransactions;    

    public function test_index()
    {
        
        $response = $this->get(route('informasi.sinergi-program.index'));
        $response->assertStatus(200);
        $response->assertViewIs('informasi.sinergi_program.index');
    }

    public function test_get_data_sinergi_program()
    {
        
        SinergiProgram::factory()->count(2)->create();
        $response = $this->getJson(route('informasi.sinergi-program.getdata'),[
            'X-Requested-With' => 'XMLHttpRequest',
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure(['data']);
    }

    public function test_create()
    {
        
        $response = $this->get(route('informasi.sinergi-program.create'));
        $response->assertStatus(200);
        $response->assertViewIs('informasi.sinergi_program.create');
    }

    public function test_store_success()
    {
        
        Storage::fake('public');
        $data = [
            'nama' => 'Program Baru',
            'deskripsi' => 'Deskripsi program',
            'status' => 1,
            'urutan' => 1,
            'gambar' => UploadedFile::fake()->image('gambar.jpg'),
            'url' => 'https://example.com',
        ];

        $response = $this->post(route('informasi.sinergi-program.store'), $data);

        $response->assertRedirect(route('informasi.sinergi-program.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas((new SinergiProgram())->getTable(), [
            'nama' => 'Program Baru',
            'status' => 1,
        ]);
    }

    public function test_store_failed()
    {
        
        $response = $this->post(route('informasi.sinergi-program.store'), []);
        $response->assertSessionHasErrors();
    }

    public function test_edit()
    {
        
        $sinergi = SinergiProgram::factory()->create();
        $response = $this->get(route('informasi.sinergi-program.edit', $sinergi->id));
        $response->assertStatus(200);
        $response->assertViewIs('informasi.sinergi_program.edit');
    }

    public function test_update_success()
    {
        
        Storage::fake('public');
        $sinergi = SinergiProgram::factory()->create(['nama' => 'Lama']);
        $data = [
            'nama' => 'Baru',
            'deskripsi' => 'Deskripsi update',
            'status' => 1,
            'urutan' => 2,
            'gambar' => UploadedFile::fake()->image('gambar2.jpg'),
            'url' => 'https://example.com',
        ];

        $response = $this->put(route('informasi.sinergi-program.update', $sinergi->id), $data);

        $response->assertRedirect(route('informasi.sinergi-program.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas($sinergi->getTable(), [
            'id' => $sinergi->id,
            'nama' => 'Baru',
        ]);
    }

    public function test_update_failed()
    {
        
        $sinergi = SinergiProgram::factory()->create();
        $response = $this->put(route('informasi.sinergi-program.update', $sinergi->id), []);
        $response->assertSessionHasErrors();
    }

    public function test_destroy_success()
    {
        
        $sinergi = SinergiProgram::factory()->create();
        $response = $this->delete(route('informasi.sinergi-program.destroy', $sinergi->id));
        $response->assertRedirect(route('informasi.sinergi-program.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing($sinergi->getTable(), [
            'id' => $sinergi->id,
        ]);
    } 
}    