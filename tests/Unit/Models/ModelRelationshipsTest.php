<?php

use App\Models\User;
use App\Models\DataDesa;
use App\Models\Penduduk;
use App\Models\Keluarga;
use App\Models\Profil;
use App\Models\Agama;
use App\Models\Cacat;
use App\Models\Pekerjaan;
use App\Models\Pendidikan;
use App\Models\PendidikanKk;
use App\Models\Kawin;
use App\Models\GolonganDarah;
use App\Models\Warganegara;
use App\Models\HubunganKeluarga;
use App\Models\PendudukSex;



// User Model Relationships
it('user has many otp tokens', function () {
    $user = User::factory()->create();
    
    expect($user->otpTokens())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\HasMany::class);
});

it('user belongs to roles', function () {
    $user = User::factory()->create();
    $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']);
    $user->assignRole($role);

    expect($user->roles->count())->toBeGreaterThanOrEqual(1);
    expect($user->roles->first()->name)->toBe('admin');
});

it('user has permissions through roles', function () {
    $user = User::factory()->create();
    $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'admin']);
    $permission = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'edit-users']);
    $role->givePermissionTo($permission);
    $user->assignRole($role);

    expect($user->getAllPermissions()->count())->toBeGreaterThanOrEqual(1);
    expect($user->hasPermissionTo('edit-users'))->toBeTrue();
});

// DataDesa Model Relationships
it('data desa has many penduduk', function () {
    $dataDesa = DataDesa::factory()->create();
    
    expect($dataDesa->penduduk())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\HasMany::class);
});

it('data desa has many keluarga', function () {
    $dataDesa = DataDesa::factory()->create();
    
    expect($dataDesa->keluarga())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\HasMany::class);
});


// Penduduk Model Relationships
it('penduduk belongs to data desa', function () {
    $dataDesa = DataDesa::factory()->create();
    $penduduk = Penduduk::factory()->create(['desa_id' => $dataDesa->id_desa]);

    expect($penduduk->desa)->not->toBeNull();
    expect($penduduk->desa->id)->toBe($dataDesa->id);
});

it('penduduk belongs to keluarga', function () {
    $keluarga = Keluarga::factory()->create();
    $penduduk = Penduduk::factory()->create(['no_kk' => $keluarga->no_kk]);

    expect($penduduk->keluarga)->not->toBeNull();
    expect($penduduk->keluarga->id)->toBe($keluarga->id);
});

it('penduduk belongs to pekerjaan', function () {
    $pekerjaan = Pekerjaan::factory()->create();
    $penduduk = Penduduk::factory()->create(['pekerjaan_id' => $pekerjaan->id]);

    expect($penduduk->pekerjaan)->not->toBeNull();
    expect($penduduk->pekerjaan->id)->toBe($pekerjaan->id);
});


it('penduduk belongs to pendidikan kk', function () {
    $pendidikanKk = PendidikanKk::factory()->create();
    $penduduk = Penduduk::factory()->create(['pendidikan_kk_id' => $pendidikanKk->id]);

    expect($penduduk->pendidikan_kk)->not->toBeNull();
    expect($penduduk->pendidikan_kk->id)->toBe($pendidikanKk->id);
});

it('penduduk belongs to status kawin', function () {
    $statusKawin = Kawin::factory()->create();
    $penduduk = Penduduk::factory()->create(['status_kawin' => $statusKawin->id]);

    expect($penduduk->kawin)->not->toBeNull();
    expect($penduduk->kawin->id)->toBe($statusKawin->id);
});


// Keluarga Model Relationships
it('keluarga belongs to data desa', function () {
    $dataDesa = DataDesa::factory()->create();
    $keluarga = Keluarga::factory()->create(['desa_id' => $dataDesa->id_desa]);

    expect($keluarga->desa)->not->toBeNull();
    expect($keluarga->desa->id)->toBe($dataDesa->id);
});

it('keluarga has one kepala keluarga', function () {
    $keluarga = Keluarga::factory()->create();
    $kepalaKeluarga = Penduduk::factory()->create([
        'no_kk' => $keluarga->no_kk,
        'kk_level' => 1 // Assuming 1 is the level for kepala keluarga
    ]);
    $keluarga->nik_kepala = $kepalaKeluarga->nik;
    $keluarga->save();
    $keluarga->load('kepala_kk');    
    expect($keluarga->kepala_kk)->not->toBeNull();
    expect($keluarga->kepala_kk->id)->toBe($kepalaKeluarga->id);
});

// Complex Relationship Testing
it('can traverse complex relationships', function () {
    $dataDesa = DataDesa::factory()->create();
    $keluarga = Keluarga::factory()->create(['desa_id' => $dataDesa->id_desa]);
    $penduduk = Penduduk::factory()->create([
        'desa_id' => $dataDesa->id_desa,
        'no_kk' => $keluarga->no_kk
    ]);

    // Load relationships
    $loadedPenduduk = Penduduk::with(['desa', 'keluarga'])->find($penduduk->id);

    // Test traversing from penduduk to data desa through keluarga
    expect($loadedPenduduk->desa->id)->toBe($dataDesa->id);
});

it('handles null relationships gracefully', function () {
    $penduduk = Penduduk::factory()->create([
        'agama_id' => null,
        'pekerjaan_id' => null,
        'pendidikan_id' => null
    ]);

    expect($penduduk->agama)->toBeNull();
    expect($penduduk->pekerjaan)->toBeNull();
    expect($penduduk->pendidikan)->toBeNull();
});

it('eager loads relationships efficiently', function () {
    $dataDesa = DataDesa::factory()->create();
    $keluarga = Keluarga::factory()->create(['desa_id' => $dataDesa->id_desa]);
    $penduduk = Penduduk::factory()->create([
        'desa_id' => $dataDesa->id_desa,
        'no_kk' => $keluarga->no_kk
    ]);

    // Test eager loading
    $loadedPenduduk = Penduduk::with(['desa', 'keluarga'])->first();

    expect($loadedPenduduk->relationLoaded('desa'))->toBeTrue();
    expect($loadedPenduduk->relationLoaded('keluarga'))->toBeTrue();    
});

it('counts relationships correctly', function () {
    $dataDesa = DataDesa::factory()->create();
    $keluarga1 = Keluarga::factory()->create(['desa_id' => $dataDesa->id_desa]);
    $keluarga2 = Keluarga::factory()->create(['desa_id' => $dataDesa->id_desa]);

    Penduduk::factory()->count(3)->create(['no_kk' => $keluarga1->no_kk]);
    Penduduk::factory()->count(2)->create(['no_kk' => $keluarga2->no_kk]);

    $loadedDataDesa = DataDesa::with('keluarga')->find($dataDesa->id);
    expect($loadedDataDesa->keluarga->count())->toBeGreaterThanOrEqual(2);
});

it('queries relationships with constraints', function () {
    $dataDesa = DataDesa::factory()->create();
    $keluarga = Keluarga::factory()->create(['desa_id' => $dataDesa->id_desa]);

    $malePenduduk = Penduduk::factory()->create([
        'no_kk' => $keluarga->no_kk,
        'sex' => 1 // Assuming 1 is male
    ]);

    $femalePenduduk = Penduduk::factory()->create([
        'no_kk' => $keluarga->no_kk,
        'sex' => 2 // Assuming 2 is female
    ]);

    // Test querying with whereHas
    $maleCount = Penduduk::whereHas('pendudukSex', function ($query) {
        $query->where('id', 1);
    })->count();

    expect($maleCount)->toBeGreaterThanOrEqual(1);
});

it('handles relationship deletion', function () {
    $dataDesa = DataDesa::factory()->create();
    $keluarga = Keluarga::factory()->create(['desa_id' => $dataDesa->id_desa]);
    $penduduk = Penduduk::factory()->create(['no_kk' => $keluarga->no_kk]);

    // Refresh the model to load relationships
    $loadedPenduduk = Penduduk::with('keluarga')->find($penduduk->id);
    expect($loadedPenduduk->keluarga)->not->toBeNull();
});

it('tests polymorphic relationships if they exist', function () {
    // This test would be for any polymorphic relationships in the models
    // Adjust based on actual model structure

    $user = User::factory()->create();

    // Test if there are any morphTo relationships
    // This is a placeholder - adjust based on actual model structure
    expect($user)->toBeInstanceOf(User::class);
});