<?php

use App\Models\Album;
use App\Models\Galeri;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Galeri media resolvers
|--------------------------------------------------------------------------
|
| Galeri dengan `jenis = url` harus bisa ditampilkan baik di web maupun di
| admin, baik berupa foto, video Google Drive, maupun video YouTube.
|
*/

beforeEach(function () {
    Storage::fake('public');
    $this->album = Album::factory()->create();
});

it('exposes image media for google drive photo links', function () {
    $galeri = Galeri::create([
        'album_id' => $this->album->id,
        'judul' => 'Foto Google Drive',
        'jenis' => 'url',
        'link' => 'https://drive.google.com/file/d/1AbCdEf123/view?usp=sharing',
        'media_type' => 'image',
        'status' => true,
    ]);

    expect($galeri->media_type)->toBe('image')
        ->and($galeri->media_url)->toBe('https://drive.google.com/uc?export=view&id=1AbCdEf123')
        ->and($galeri->media_embed_url)->toBeNull()
        ->and($galeri->gambar_path)->toBe('https://drive.google.com/uc?export=view&id=1AbCdEf123');
});

it('exposes video media for google drive video links', function () {
    $galeri = Galeri::create([
        'album_id' => $this->album->id,
        'judul' => 'Video Google Drive',
        'jenis' => 'url',
        'link' => 'https://drive.google.com/file/d/1AbCdEf123/view',
        'media_type' => 'video',
        'status' => true,
    ]);

    expect($galeri->media_type)->toBe('video')
        ->and($galeri->media_embed_url)->toBe('https://drive.google.com/file/d/1AbCdEf123/preview')
        ->and($galeri->media_url)->toContain('id=1AbCdEf123');
});

it('exposes embed media for youtube links', function () {
    $galeri = Galeri::create([
        'album_id' => $this->album->id,
        'judul' => 'Video YouTube',
        'jenis' => 'url',
        'link' => 'https://youtu.be/dQw4w9WgXcQ',
        'status' => true,
    ]);

    expect($galeri->media_type)->toBe('youtube')
        ->and($galeri->media_embed_url)->toBe('https://www.youtube.com/embed/dQw4w9WgXcQ')
        ->and($galeri->gambar_path)->toBe('https://img.youtube.com/vi/dQw4w9WgXcQ/hqdefault.jpg');
});

it('exposes media url for direct video files', function () {
    $galeri = Galeri::create([
        'album_id' => $this->album->id,
        'judul' => 'Video MP4',
        'jenis' => 'url',
        'link' => 'https://sitim.go.id/video/kegiatan.mp4',
        'status' => true,
    ]);

    expect($galeri->media_type)->toBe('video')
        ->and($galeri->media_url)->toBe('https://sitim.go.id/video/kegiatan.mp4')
        ->and($galeri->media_embed_url)->toBeNull();
});

it('falls back to a local file thumbnail for uploaded media', function () {
    Storage::disk('public')->put('publikasi/galeri/unggah.jpg', 'fake content');

    $galeri = Galeri::create([
        'album_id' => $this->album->id,
        'judul' => 'Foto Unggah',
        'jenis' => 'file',
        'gambar' => ['unggah.jpg'],
        'status' => true,
    ]);

    expect($galeri->media_type)->toBe('image')
        ->and($galeri->media_url)->toContain('publikasi/galeri/unggah.jpg')
        ->and($galeri->media_embed_url)->toBeNull();
});

it('returns a no image placeholder when nothing can be displayed', function () {
    $galeri = new Galeri();

    expect($galeri->media_type)->toBe('unknown')
        ->and($galeri->media_url)->toBeNull()
        ->and($galeri->media_embed_url)->toBeNull()
        ->and($galeri->gambar_path)->toContain('no-image.png');
});

it('keeps the original link intact for the detail page', function () {
    $galeri = Galeri::create([
        'album_id' => $this->album->id,
        'judul' => 'Link Asli',
        'jenis' => 'url',
        'link' => 'https://drive.google.com/file/d/1AbCdEf123/view?usp=sharing',
        'media_type' => 'video',
        'status' => true,
    ]);

    expect($galeri->link)->toBe('https://drive.google.com/file/d/1AbCdEf123/view?usp=sharing');
});

it('serializes media fields for the api', function () {
    $galeri = Galeri::create([
        'album_id' => $this->album->id,
        'judul' => 'Serialisasi',
        'jenis' => 'url',
        'link' => 'https://drive.google.com/file/d/1AbCdEf123/view',
        'media_type' => 'video',
        'status' => true,
    ]);

    $data = $galeri->toArray();

    expect($data)->toHaveKeys(['media_type', 'media_url', 'media_embed_url', 'gambar_path'])
        ->and($data['media_type'])->toBe('video')
        ->and($data['media_embed_url'])->toBe('https://drive.google.com/file/d/1AbCdEf123/preview');
});
