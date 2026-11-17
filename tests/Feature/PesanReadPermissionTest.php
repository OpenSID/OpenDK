<?php

use App\Models\Pesan;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Permission;

uses(DatabaseTransactions::class);

beforeEach(function () {
    $this->withViewErrors([]);
    Pesan::query()->delete();

    $this->pengguna = User::factory()->create();
    $this->pengguna->forceFill(['status' => 1])->save();
});

test('pengguna tanpa hak akses pesan tidak bisa menandai pesan dibaca', function () {
    $pesan = Pesan::factory()->create(['sudah_dibaca' => Pesan::BELUM_DIBACA]);

    $this->actingAs($this->pengguna)
        ->patch(route('pesan.read.mark', $pesan->id))
        ->assertForbidden();

    expect($pesan->fresh()->sudah_dibaca)->toBe(Pesan::BELUM_DIBACA);
});

test('pengguna tanpa hak akses pesan tidak bisa membuka halaman baca pesan', function () {
    $pesan = Pesan::factory()->create();

    $this->actingAs($this->pengguna)
        ->get(route('pesan.read', $pesan->id))
        ->assertForbidden();
});

test('pengguna dengan hak akses pesan bisa menandai pesan dibaca', function () {
    Permission::firstOrCreate(['name' => 'access.pesan', 'guard_name' => 'web']);
    $this->pengguna->givePermissionTo('access.pesan');
    $pesan = Pesan::factory()->create(['sudah_dibaca' => Pesan::BELUM_DIBACA]);

    $this->actingAs($this->pengguna)
        ->patch(route('pesan.read.mark', $pesan->id))
        ->assertOk();

    expect($pesan->fresh()->sudah_dibaca)->toBe(Pesan::SUDAH_DIBACA);
});
