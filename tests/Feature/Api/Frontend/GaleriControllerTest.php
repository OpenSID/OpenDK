<?php

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CompleteProfile;
use App\Http\Middleware\GlobalShareMiddleware;
use App\Models\Album;
use App\Models\Galeri;
use App\Models\SettingAplikasi;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;

/*
|--------------------------------------------------------------------------
| Galeri Frontend API
|--------------------------------------------------------------------------
|
| Tema web merender galeri dari API, jadi API wajib mengembalikan informasi
| media (tipe, URL, embed) agar foto maupun video berbasis link bisa
| ditampilkan dengan benar.
|
*/

uses(DatabaseTransactions::class);

beforeEach(function () {
    $this->withViewErrors([]);
    $this->withoutMiddleware([
        Authenticate::class,
        RoleMiddleware::class,
        PermissionMiddleware::class,
        CompleteProfile::class,
        GlobalShareMiddleware::class,
    ]);

    // Respons API di-cache; DatabaseTransactions membuat rollback pada tabel
    // cache_keys sehingga cache dari test sebelumnya tidak bisa dihapus observer.
    Cache::flush();

    SettingAplikasi::updateOrCreate(
        ['key' => 'sinkronisasi_database_gabungan'],
        ['value' => '0']
    );

    $this->album = Album::factory()->create([
        'judul' => 'Album Galeri Uji',
        'slug' => 'album-galeri-uji',
    ]);
});

function makeGaleri(array $attributes = []): Galeri
{
    return Galeri::create(array_merge([
        'album_id' => test()->album->id,
        'judul' => 'Galeri API',
        'jenis' => 'url',
        'status' => true,
    ], $attributes));
}

it('returns media details for a google drive video', function () {
    makeGaleri([
        'judul' => 'Video Drive',
        'link' => 'https://drive.google.com/file/d/1AbCdEf123/view',
        'media_type' => 'video',
    ]);

    $response = $this->getJson('/api/frontend/v1/galeri?filter[status]=1&filter[album.slug]=album-galeri-uji');

    $response->assertStatus(200);

    $attributes = collect($response->json('data'))->firstWhere('attributes.judul', 'Video Drive')['attributes'];

    expect($attributes['media_type'])->toBe('video')
        ->and($attributes['media_embed_url'])->toBe('https://drive.google.com/file/d/1AbCdEf123/preview')
        ->and($attributes['media_url'])->toBe('https://drive.google.com/uc?export=download&id=1AbCdEf123');
});

it('returns media details for a google drive photo', function () {
    makeGaleri([
        'judul' => 'Foto Drive',
        'link' => 'https://drive.google.com/file/d/1AbCdEf123/view',
        'media_type' => 'image',
    ]);

    $response = $this->getJson('/api/frontend/v1/galeri?filter[status]=1&filter[album.slug]=album-galeri-uji');

    $attributes = collect($response->json('data'))->firstWhere('attributes.judul', 'Foto Drive')['attributes'];

    expect($attributes['media_type'])->toBe('image')
        ->and($attributes['media_url'])->toBe('https://drive.google.com/uc?export=view&id=1AbCdEf123')
        ->and($attributes['gambar_path'])->toBe('https://drive.google.com/uc?export=view&id=1AbCdEf123');
});

it('returns an embed url and thumbnail for a youtube video', function () {
    makeGaleri([
        'judul' => 'Video YouTube',
        'link' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
    ]);

    $response = $this->getJson('/api/frontend/v1/galeri?filter[status]=1&filter[album.slug]=album-galeri-uji');

    $attributes = collect($response->json('data'))->firstWhere('attributes.judul', 'Video YouTube')['attributes'];

    expect($attributes['media_type'])->toBe('youtube')
        ->and($attributes['media_embed_url'])->toBe('https://www.youtube.com/embed/dQw4w9WgXcQ')
        ->and($attributes['gambar_path'])->toBe('https://img.youtube.com/vi/dQw4w9WgXcQ/hqdefault.jpg');
});

it('exposes the media keys for every galeri record', function () {
    makeGaleri(['judul' => 'Tanpa Link', 'link' => null]);

    $response = $this->getJson('/api/frontend/v1/galeri?filter[status]=1&filter[album.slug]=album-galeri-uji');

    $attributes = collect($response->json('data'))->firstWhere('attributes.judul', 'Tanpa Link')['attributes'];

    expect($attributes)->toHaveKeys(['media_type', 'media_url', 'media_embed_url', 'gambar_path'])
        ->and($attributes['media_type'])->toBe('unknown');
});
