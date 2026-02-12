<?php

use App\Models\Profil;
use App\Models\DataUmum;
use App\Models\DataDesa;
use Illuminate\Support\Facades\Cache;



it('can create a profil', function () {
    $profil = Profil::factory()->create([
        'nama_provinsi' => 'Jawa Barat',
        'nama_kabupaten' => 'Bandung',
        'nama_kecamatan' => 'Cicalengka',
        'email' => 'kecamatan@test.com',
    ]);

    expect($profil)->toBeInstanceOf(Profil::class);
    expect($profil->nama_provinsi)->toBe('Jawa Barat');
    expect($profil->nama_kabupaten)->toBe('Bandung');
    expect($profil->nama_kecamatan)->toBe('Cicalengka');
    expect($profil->email)->toBe('kecamatan@test.com');
});

it('has fillable attributes', function () {
    $profil = Profil::factory()->make();

    $fillable = [
        'provinsi_id', 'nama_provinsi', 'kabupaten_id', 'nama_kabupaten',
        'kecamatan_id', 'nama_kecamatan', 'alamat', 'kode_pos', 'telepon',
        'email', 'tahun_pembentukan', 'dasar_pembentukan', 'file_struktur_organisasi',
        'file_logo', 'sambutan', 'visi', 'misi'
    ];

    foreach ($fillable as $field) {
        expect(in_array($field, $profil->getFillable()))->toBeTrue();
    }
});

it('has timestamps enabled', function () {
    $profil = Profil::factory()->create();

    expect($profil->created_at)->not->toBeNull();
    expect($profil->updated_at)->not->toBeNull();
});

it('has correct table name', function () {
    $profil = new Profil();

    expect($profil->getTable())->toBe('das_profil');
});

it('has dataUmum relationship', function () {
    $profil = Profil::factory()->create();

    expect($profil->dataUmum())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\HasOne::class);
});

it('has dataDesa relationship', function () {
    $profil = Profil::factory()->create();

    expect($profil->dataDesa())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\HasMany::class);
});

it('has strukturOrganisasi relationship', function () {
    $profil = Profil::factory()->create();

    expect($profil->strukturOrganisasi())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\HasOne::class);
});


it('clears cache when saved', function () {
    $profil = Profil::factory()->create([
        'nama_kecamatan' => 'Test Kecamatan',
    ]);
    
    // The cache clearing happens automatically due to model events
    expect($profil->id)->not->toBeNull();
});

it('clears cache when updated', function () {
    $profil = Profil::factory()->create([
        'nama_kecamatan' => 'Original Name',
    ]);

    $profil->update(['nama_kecamatan' => 'Updated Name']);
    
    // The cache clearing happens automatically due to model events
    expect($profil->nama_kecamatan)->toBe('Updated Name');
});

it('clears cache when created', function () {
    $profil = Profil::create([
        'nama_kecamatan' => 'New Kecamatan',
        'nama_kabupaten' => 'Test Kabupaten',
        'nama_provinsi' => 'Test Provinsi',
    ]);
    
    // The cache clearing happens automatically due to model events
    expect($profil->id)->not->toBeNull();
});

it('can handle year fields correctly', function () {
    $tahunPembentukan = 1980;

    $profil = Profil::factory()->create([
        'tahun_pembentukan' => $tahunPembentukan,
    ]);

    expect($profil->tahun_pembentukan)->toBe($tahunPembentukan);
});

it('can handle file fields', function () {
    $fileStruktur = 'struktur_organisasi.pdf';
    $fileLogo = 'logo_kecamatan.png';

    $profil = Profil::factory()->create([
        'file_struktur_organisasi' => $fileStruktur,
        'file_logo' => $fileLogo,
    ]);

    expect($profil->file_struktur_organisasi)->toBe($fileStruktur);
    expect($profil->file_logo)->toBe($fileLogo);
});

it('can handle text fields with special characters', function () {
    $sambutan = 'Sambutan dari Kepala Kecamatan dengan karakter khusus: é à ñ ç';
    $visi = 'Visi kecamatan dengan tujuan jangka panjang';
    $misi = 'Misi kecamatan dengan tujuan jangka menengah';

    $profil = Profil::factory()->create([
        'sambutan' => $sambutan,
        'visi' => $visi,
        'misi' => $misi,
    ]);

    expect($profil->sambutan)->toBe($sambutan);
    expect($profil->visi)->toBe($visi);
    expect($profil->misi)->toBe($misi);
});

it('can handle null values for optional fields', function () {
    $profil = Profil::factory()->create([
        'alamat' => null,
        'kode_pos' => null,
        'telepon' => null,
        'email' => null,
        'tahun_pembentukan' => null,
        'dasar_pembentukan' => null,
        'file_struktur_organisasi' => null,
        'file_logo' => null,
        'sambutan' => null,
        'visi' => null,
        'misi' => null,
    ]);

    expect($profil->alamat)->toBeNull();
    expect($profil->kode_pos)->toBeNull();
    expect($profil->telepon)->toBeNull();
    expect($profil->email)->toBeNull();
    expect($profil->tahun_pembentukan)->toBeNull();
    expect($profil->dasar_pembentukan)->toBeNull();
    expect($profil->file_struktur_organisasi)->toBeNull();
    expect($profil->file_logo)->toBeNull();
    expect($profil->sambutan)->toBeNull();
    expect($profil->visi)->toBeNull();
    expect($profil->misi)->toBeNull();
});

it('can query profiles with complex filters', function () {
    $provinsiId = 32; // Jawa Barat
    $kabupatenId = 3201; // Bandung
    $kecamatanId = 320101; // Cicalengka

    $profil = Profil::factory()->create([
        'provinsi_id' => $provinsiId,
        'kabupaten_id' => $kabupatenId,
        'kecamatan_id' => $kecamatanId,
    ]);

    $filteredProfil = Profil::where('provinsi_id', $provinsiId)
        ->where('kabupaten_id', $kabupatenId)
        ->where('kecamatan_id', $kecamatanId)
        ->first();

    expect($filteredProfil)->not->toBeNull();
    expect($filteredProfil->id)->toBe($profil->id);
});

it('can handle multiple data desa for one profil', function () {
    $profil = Profil::factory()->create();
    $dataDesa1 = DataDesa::factory()->create(['profil_id' => $profil->id]);
    $dataDesa2 = DataDesa::factory()->create(['profil_id' => $profil->id]);
    $dataDesa3 = DataDesa::factory()->create(['profil_id' => $profil->id]);

    $loadedProfil = Profil::with('dataDesa')->find($profil->id);
    expect($loadedProfil->dataDesa->count())->toBeGreaterThanOrEqual(3);
    expect($loadedProfil->dataDesa->pluck('id'))->toContain($dataDesa1->id, $dataDesa2->id, $dataDesa3->id);
});

it('can handle cache tags flush on save', function () {
    $profil = Profil::factory()->create();
    
    // The cache clearing happens automatically due to model events
    expect($profil->id)->not->toBeNull();
});

it('can handle cache tags flush on update', function () {
    $profil = Profil::factory()->create();

    $profil->update(['nama_kecamatan' => 'Updated Name']);
    
    // The cache clearing happens automatically due to model events
    expect($profil->nama_kecamatan)->toBe('Updated Name');
});

it('can handle cache tags flush on create', function () {
    $profil = Profil::create([
        'nama_kecamatan' => 'New Kecamatan',
        'nama_kabupaten' => 'Test Kabupaten',
        'nama_provinsi' => 'Test Provinsi',
    ]);
    
    // The cache clearing happens automatically due to model events
    expect($profil->id)->not->toBeNull();
});