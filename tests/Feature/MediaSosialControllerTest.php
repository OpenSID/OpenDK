<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\MediaSosial;
use Illuminate\Http\UploadedFile;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CompleteProfile;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Middlewares\PermissionMiddleware;
use Spatie\Permission\Middlewares\RoleMiddleware;

class MediaSosialControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected string $tableName;
    /**
     * Set up the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->withViewErrors([]);        
        $this->withoutMiddleware([Authenticate::class, RoleMiddleware::class, PermissionMiddleware::class, CompleteProfile::class]);

        $this->tableName = (new MediaSosial)->getTable();
    }


    public function test_display_the_media_sosial_index_page()
    {
        $response = $this->get(route('informasi.media-sosial.index'));

        $response->assertStatus(200);
        $response->assertViewIs('informasi.media_sosial.index');
    }

    /** @test */
    public function test_create_an_media_sosial()
    {
        // URL gambar yang valid
        $imageUrl = 'https://loremflickr.com/320/240';

        $content = @file_get_contents($imageUrl);
        if ($content === false) {
            throw new \Exception("Gagal mengunduh gambar dari URL.");
        }
        $tempPath = storage_path('app/temp-image.png');
        file_put_contents($tempPath, $content);
        
        $file = new UploadedFile(
            $tempPath,
            'temp-image.png',
            mime_content_type($tempPath),
            null,
            true // true artinya ini file "test", bukan hasil upload asli dari browser
        );

        $data = [
            'logo'   => $file,
            'url'    => 'https://example.com',
            'nama'   => 'Instagram',
            'status' => 1,
        ];

        $response = $this->post(route('informasi.media-sosial.store'), $data);

        $this->assertDatabaseHas($this->tableName, [
            'nama' => $data['nama'],
        ]);

        $response->assertRedirect(route('informasi.media-sosial.index'));
        $response->assertSessionHas('success', 'Media Sosial berhasil disimpan!');

        // Hapus file sementara
        if (file_exists($tempPath)) {
            unlink($tempPath);
        }
    }

    /** @test */
    public function test_update_an_media_sosial()
    {        
        $media = MediaSosial::factory()->create();

        $data = [
            'nama' => 'Updated Media Sosial',
            'url'    => 'https://example.com',
            'status' => 1,           
        ];        

        $response = $this->put(route('informasi.media-sosial.update', $media->id), $data);
        $this->assertDatabaseHas($this->tableName, [
            'nama' => $data['nama'],
        ]);

        $response->assertRedirect(route('informasi.media-sosial.index'));
        $response->assertSessionHas('success', 'Media Sosial berhasil diubah!');
    }

    /** @test */
    public function test_delete_an_media_sosial()
    {
        $media = MediaSosial::factory()->create();

        $response = $this->delete(route('informasi.media-sosial.destroy', $media->id));

        $this->assertDatabaseMissing($this->tableName, [
            'id' => $media->id,
        ]);

        $response->assertRedirect(route('informasi.media-sosial.index'));
        $response->assertSessionHas('success', 'Media Sosial berhasil dihapus!');
    }   

}
