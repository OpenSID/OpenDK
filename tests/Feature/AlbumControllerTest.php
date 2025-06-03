<?php

namespace Tests\Feature;

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CompleteProfile;
use App\Models\Album;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Spatie\Permission\Middlewares\PermissionMiddleware;
use Spatie\Permission\Middlewares\RoleMiddleware;
use Tests\TestCase;

class AlbumControllerTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * Set up the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->withViewErrors([]);        
        $this->withoutMiddleware([Authenticate::class, RoleMiddleware::class, PermissionMiddleware::class, CompleteProfile::class]); // Disable middleware for this test        
    }


    public function test_display_the_album_index_page()
    {
        $response = $this->get(route('publikasi.album.index'));

        $response->assertStatus(200);
        $response->assertViewIs('publikasi.album.index');
    }

    /** @test */
    public function test_create_an_album()
    {
        $data = [
            'judul' => 'Test Album',
            'status' => true,
            'gambar' => null, // Assuming no image upload for this test
        ];

        $response = $this->post(route('publikasi.album.store'), $data);

        $this->assertDatabaseHas('albums', [
            'judul' => 'Test Album',
        ]);

        $response->assertRedirect(route('publikasi.album.index'));
        $response->assertSessionHas('success', 'Album berhasil disimpan!');
    }

    /** @test */
    public function test_update_an_album()
    {        
        $album = Album::factory()->create();

        $data = [
            'judul' => 'Updated Album',           
        ];        
        
        $response = $this->put(route('publikasi.album.update', $album->id), $data);
        $this->assertDatabaseHas('albums', [
            'judul' => 'Updated Album',
        ]);

        $response->assertRedirect(route('publikasi.album.index'));
        $response->assertSessionHas('success', 'Album berhasil diubah!');
    }

    /** @test */
    public function test_delete_an_album()
    {
        $album = Album::factory()->create();

        $response = $this->delete(route('publikasi.album.destroy', $album->id));

        $this->assertDatabaseMissing('albums', [
            'id' => $album->id,
        ]);

        $response->assertRedirect(route('publikasi.album.index'));
        $response->assertSessionHas('success', 'Album sukses dihapus!');
    }   
}
