<?php

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CompleteProfile;
use App\Models\Album;
use App\Models\Galeri;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;

uses(DatabaseTransactions::class);

beforeEach(function () {
    $this->withViewErrors([]);
    $this->withoutMiddleware([
        Authenticate::class,
        RoleMiddleware::class,
        PermissionMiddleware::class,
        CompleteProfile::class,
    ]);
});

test('display the galeri index page', function () {
    $album = Album::factory()->create();

    $response = $this->get(route('publikasi.galeri.index', $album->id));

    $response->assertStatus(200);
    $response->assertViewIs('publikasi.galeri.index');
});

test('display the galeri create page without broken image placeholder', function () {
    $album = Album::factory()->create();
    Session::put('album_id', $album->id);

    $response = $this->get(route('publikasi.galeri.create'));

    $response->assertStatus(200);
    $response->assertViewIs('publikasi.galeri.create');
    // Ensure the old broken placeholder is not displayed
    $response->assertDontSee('id="showthumbnail"', false);
    $response->assertSee('id="file-galeri"', false);
    $response->assertSee('id="preview-container"', false);
});

test('display the galeri edit page and render valid thumbnail path', function () {
    Storage::fake('public');
    $album = Album::factory()->create();
    Session::put('album_id', $album->id);

    // Put a dummy image in storage
    Storage::disk('public')->put('publikasi/galeri/sample.jpg', 'fake content');

    $galeri = Galeri::create([
        'album_id' => $album->id,
        'judul' => 'Galeri Edit Test',
        'gambar' => ['sample.jpg'],
        'jenis' => 'file',
        'status' => true,
    ]);

    $response = $this->get(route('publikasi.galeri.edit', $galeri->id));

    $response->assertStatus(200);
    $response->assertViewIs('publikasi.galeri.edit');
    // Should see sample.jpg in existing images
    $response->assertSee('sample.jpg');
    // Should render modal image trigger and download button
    $response->assertSee('data-toggle="modal-image"', false);
    $response->assertSee('Unduh');
    $response->assertSee('id="modal-image-preview"', false);
    $response->assertDontSee('publikasi/galeri/ sample.jpg');
    $response->assertDontSee('isThumbnail(" publikasi/galeri/');
});

test('create a galeri with file upload', function () {
    Storage::fake('public');
    $album = Album::factory()->create();
    Session::put('album_id', $album->id);

    $file = UploadedFile::fake()->image('foto1.jpg', 600, 400);

    $data = [
        'judul' => 'Galeri Foto Kegiatan',
        'jenis' => 'file',
        'status' => '1',
        'gambar' => [$file],
    ];

    $response = $this->post(route('publikasi.galeri.store'), $data);

    $response->assertRedirect(route('publikasi.galeri.index', $album->id));
    $response->assertSessionHas('success', 'Galeri berhasil disimpan!');

    $galeri = Galeri::where('judul', 'Galeri Foto Kegiatan')->first();
    expect($galeri)->not->toBeNull();
    expect($galeri->jenis)->toBe('file');
    expect($galeri->gambar)->toBeArray()->toHaveCount(1);

    Storage::disk('public')->assertExists('publikasi/galeri/' . $galeri->gambar[0]);
});

test('create a galeri with url link', function () {
    $album = Album::factory()->create();
    Session::put('album_id', $album->id);

    $data = [
        'judul' => 'Galeri Video YouTube',
        'jenis' => 'url',
        'link' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
        'status' => '1',
    ];

    $response = $this->post(route('publikasi.galeri.store'), $data);

    $response->assertRedirect(route('publikasi.galeri.index', $album->id));
    $response->assertSessionHas('success', 'Galeri berhasil disimpan!');

    $galeri = Galeri::where('judul', 'Galeri Video YouTube')->first();
    expect($galeri)->not->toBeNull();
    expect($galeri->jenis)->toBe('url');
    expect($galeri->link)->toBe('https://www.youtube.com/watch?v=dQw4w9WgXcQ');
    expect($galeri->gambar)->toBeNull();
});

test('update a galeri without changing image preserves existing image', function () {
    Storage::fake('public');
    $album = Album::factory()->create();
    Session::put('album_id', $album->id);

    $galeri = Galeri::create([
        'album_id' => $album->id,
        'judul' => 'Galeri Awal',
        'gambar' => ['existing_image.jpg'],
        'jenis' => 'file',
        'status' => true,
    ]);

    $data = [
        'judul' => 'Galeri Diperbarui',
        'jenis' => 'file',
        'status' => '1',
    ];

    $response = $this->put(route('publikasi.galeri.update', $galeri->id), $data);

    $response->assertRedirect(route('publikasi.galeri.index', $album->id));
    $response->assertSessionHas('success', 'Galeri berhasil diubah!');

    $galeri->refresh();
    expect($galeri->judul)->toBe('Galeri Diperbarui');
    expect($galeri->gambar)->toBe(['existing_image.jpg']);
});

test('update a galeri with new image replaces old image in storage', function () {
    Storage::fake('public');
    $album = Album::factory()->create();
    Session::put('album_id', $album->id);

    Storage::disk('public')->put('publikasi/galeri/old_file.jpg', 'old content');

    $galeri = Galeri::create([
        'album_id' => $album->id,
        'judul' => 'Galeri File Lama',
        'gambar' => ['old_file.jpg'],
        'jenis' => 'file',
        'status' => true,
    ]);

    $newFile = UploadedFile::fake()->image('new_file.jpg', 600, 400);

    $data = [
        'judul' => 'Galeri File Baru',
        'jenis' => 'file',
        'status' => '1',
        'gambar' => [$newFile],
    ];

    $response = $this->put(route('publikasi.galeri.update', $galeri->id), $data);

    $response->assertRedirect(route('publikasi.galeri.index', $album->id));
    $response->assertSessionHas('success', 'Galeri berhasil diubah!');

    $galeri->refresh();
    expect($galeri->judul)->toBe('Galeri File Baru');
    expect($galeri->gambar[0])->not->toBe('old_file.jpg');

    // Observer should have deleted the old file
    Storage::disk('public')->assertMissing('publikasi/galeri/old_file.jpg');
    // And new file should exist
    Storage::disk('public')->assertExists('publikasi/galeri/' . $galeri->gambar[0]);
});

test('delete a galeri removes image from storage', function () {
    Storage::fake('public');
    $album = Album::factory()->create();
    Session::put('album_id', $album->id);

    Storage::disk('public')->put('publikasi/galeri/to_delete.jpg', 'content');

    $galeri = Galeri::create([
        'album_id' => $album->id,
        'judul' => 'Galeri Hapus',
        'gambar' => ['to_delete.jpg'],
        'jenis' => 'file',
        'status' => true,
    ]);

    $response = $this->delete(route('publikasi.galeri.destroy', $galeri->id));

    $response->assertRedirect(route('publikasi.galeri.index', $album->id));
    $response->assertSessionHas('success', 'Galeri sukses dihapus!');

    $this->assertDatabaseMissing('galeris', ['id' => $galeri->id]);
    Storage::disk('public')->assertMissing('publikasi/galeri/to_delete.jpg');
});

test('toggle galeri status', function () {
    $album = Album::factory()->create();
    Session::put('album_id', $album->id);

    $galeri = Galeri::create([
        'album_id' => $album->id,
        'judul' => 'Galeri Status',
        'jenis' => 'file',
        'status' => false,
    ]);

    $response = $this->put(route('publikasi.galeri.status', $galeri->id));

    $galeri->refresh();
    expect($galeri->status)->toBeTrue();
    $response->assertRedirect(route('publikasi.galeri.index', $album->id));
});

test('getGambarPathAttribute handles file, youtube url, and empty safely', function () {
    Storage::fake('public');
    $album = Album::factory()->create();

    // 1. File that exists
    Storage::disk('public')->put('publikasi/galeri/ada.jpg', 'fake content');
    $galeri1 = Galeri::create([
        'album_id' => $album->id,
        'judul' => 'Galeri Ada File',
        'gambar' => ['ada.jpg'],
        'jenis' => 'file',
        'status' => true,
    ]);
    expect($galeri1->gambar_path)->toContain('publikasi/galeri/ada.jpg');

    // 2. File that does not exist in storage -> returns fallback no-image
    $galeri2 = Galeri::create([
        'album_id' => $album->id,
        'judul' => 'Galeri Tak Ada File',
        'gambar' => ['tidak_ada.jpg'],
        'jenis' => 'file',
        'status' => true,
    ]);
    expect($galeri2->gambar_path)->toContain('no-image.png');

    // 3. YouTube link -> returns YouTube thumbnail url
    $galeri3 = Galeri::create([
        'album_id' => $album->id,
        'judul' => 'Galeri YouTube',
        'link' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
        'jenis' => 'url',
        'status' => true,
    ]);
    expect($galeri3->gambar_path)->toBe('https://img.youtube.com/vi/dQw4w9WgXcQ/hqdefault.jpg');

    // 4. Non-youtube link -> returns fallback no-image
    $galeri4 = Galeri::create([
        'album_id' => $album->id,
        'judul' => 'Galeri Web Biasa',
        'link' => 'https://opendesa.id',
        'jenis' => 'url',
        'status' => true,
    ]);
    expect($galeri4->gambar_path)->toContain('no-image.png');

    // 5. Empty / null fields -> does not crash with undefined array key
    $galeri5 = new Galeri();
    expect($galeri5->gambar_path)->toContain('no-image.png');
});
