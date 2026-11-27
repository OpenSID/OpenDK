<?php

namespace Tests\Feature\Surat;

use App\Enums\JenisJabatan;
use App\Enums\LogVerifikasiSurat;
use App\Enums\StatusSurat;
use App\Http\Middleware\CompleteProfile;
use App\Models\Jabatan;
use App\Models\Pengurus;
use App\Models\Surat;
use Illuminate\Foundation\Testing\DatabaseTransactions;

uses(DatabaseTransactions::class);

beforeEach(function () {
    // Halaman QR Code dipindai penerima surat yang tidak memiliki akun,
    // jadi middleware autentikasi sengaja tidak dimatikan di sini.
    $this->withoutMiddleware([CompleteProfile::class]);
});

test('halaman verifikasi qr code dapat diakses tanpa login', function () {
    $this->get(route('surat.verifikasi'))->assertStatus(200);
});

test('halaman qr code surat arsip dapat diakses tanpa login', function () {
    $jabatan = Jabatan::factory()->create([
        'nama' => 'Camat',
        'jenis' => JenisJabatan::Camat,
    ]);

    $pengurus = Pengurus::factory()->create([
        'jabatan_id' => $jabatan->id,
    ]);

    $surat = Surat::factory()->arsip()->create([
        'pengurus_id' => $pengurus->id,
        'log_verifikasi' => LogVerifikasiSurat::SudahTTE,
    ]);

    $this->get(route('surat.arsip.qrcode', $surat->id))
        ->assertStatus(200)
        ->assertSee($surat->nomor);
});

test('halaman qr code menolak surat yang belum diarsipkan', function () {
    $surat = Surat::factory()->create([
        'status' => StatusSurat::Permohonan,
    ]);

    $this->get(route('surat.arsip.qrcode', $surat->id))->assertStatus(404);
});
