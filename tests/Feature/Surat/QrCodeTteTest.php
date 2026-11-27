<?php

namespace Tests\Feature\Surat;

use App\Enums\JenisJabatan;
use App\Enums\LogVerifikasiSurat;
use App\Enums\StatusSurat;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CompleteProfile;
use App\Models\Jabatan;
use App\Models\LogTte;
use App\Models\Pengurus;
use App\Models\Surat;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use setasign\Fpdi\Fpdi;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;

uses(DatabaseTransactions::class);

beforeEach(function () {
    $this->withoutMiddleware([
        Authenticate::class,
        RoleMiddleware::class,
        PermissionMiddleware::class,
        CompleteProfile::class,
    ]);

    $jabatanCamat = Jabatan::factory()->create([
        'nama' => 'Camat',
        'jenis' => JenisJabatan::Camat,
    ]);

    $this->pengurusCamat = Pengurus::factory()->create([
        'jabatan_id' => $jabatanCamat->id,
        'status' => 1,
    ]);

    $this->userCamat = User::factory()->create([
        'pengurus_id' => $this->pengurusCamat->id,
    ]);

    $this->direktoriSurat = public_path('storage/surat');

    if (! is_dir($this->direktoriSurat)) {
        mkdir($this->direktoriSurat, 0755, true);
    }
});

afterEach(function () {
    foreach (glob($this->direktoriSurat.'/*_uji_*.pdf') as $file) {
        @unlink($file);
    }
});

function buatPdfUji(string $direktori): string
{
    $path = $direktori.'/surat_uji_'.uniqid().'.pdf';

    $pdf = new Fpdi();
    $pdf->AddPage();
    $pdf->SetFont('Helvetica', '', 12);
    $pdf->Cell(0, 10, 'Surat Uji OpenDK');
    $pdf->Output('F', $path);

    return $path;
}

test('tandatangan qr menolak surat yang belum masuk tahap proses tte', function () {
    $this->actingAs($this->userCamat);
    $this->userCamat->givePermissionTo('access.surat');

    $surat = Surat::factory()->create([
        'log_verifikasi' => LogVerifikasiSurat::Camat,
    ]);

    $this->postJson(route('surat.permohonan.tandatangan_qr', $surat->id))
        ->assertStatus(400)
        ->assertJson(['status' => false]);
});

test('tandatangan qr hanya dapat dilakukan oleh camat', function () {
    $jabatanSekretaris = Jabatan::factory()->create([
        'nama' => 'Sekretaris',
        'jenis' => JenisJabatan::Sekretaris,
    ]);

    $pengurusSekretaris = Pengurus::factory()->create([
        'jabatan_id' => $jabatanSekretaris->id,
    ]);

    $userSekretaris = User::factory()->create([
        'pengurus_id' => $pengurusSekretaris->id,
    ]);

    $this->actingAs($userSekretaris);
    $userSekretaris->givePermissionTo('access.surat');

    $surat = Surat::factory()->menungguTTE()->create();

    $this->postJson(route('surat.permohonan.tandatangan_qr', $surat->id))
        ->assertStatus(403)
        ->assertJson(['status' => false]);
});

test('tandatangan qr menyematkan qr code, menyimpan hash, dan mengarsipkan surat', function () {
    $this->actingAs($this->userCamat);
    $this->userCamat->givePermissionTo('access.surat');

    $path = buatPdfUji($this->direktoriSurat);

    $surat = Surat::factory()->menungguTTE()->create([
        'file' => 'surat/'.basename($path),
    ]);

    $this->postJson(route('surat.permohonan.tandatangan_qr', $surat->id))
        ->assertStatus(200)
        ->assertJson(['status' => true]);

    $surat->refresh();

    expect($surat->status)->toBe(StatusSurat::Arsip)
        ->and($surat->log_verifikasi)->toBe(LogVerifikasiSurat::SudahTTE)
        ->and($surat->file_hash)->toBe(hash_file('sha256', $path))
        ->and(file_get_contents($path))->toContain('/Subtype /Image');

    expect(LogTte::where('surat_id', $surat->id)->where('jenis', 'QRCode')->exists())->toBeTrue();
});

test('verifikasi menolak unggahan yang tidak memiliki file', function () {
    $this->post(route('surat.verifikasi.store'))
        ->assertSessionHasErrors('file');
});

test('verifikasi menolak file yang hashnya tidak dikenal', function () {
    $file = UploadedFile::fake()->createWithContent('asing.pdf', '%PDF-1.4 dokumen asing');

    $this->from(route('surat.verifikasi'))
        ->post(route('surat.verifikasi.store'), ['file' => $file])
        ->assertRedirect(route('surat.verifikasi'))
        ->assertSessionHas('error');
});

test('verifikasi menampilkan surat yang hashnya cocok', function () {
    $isi = '%PDF-1.4 surat resmi kecamatan';

    $surat = Surat::factory()->arsip()->create([
        'file_hash' => hash('sha256', $isi),
    ]);

    $file = UploadedFile::fake()->createWithContent('surat.pdf', $isi);

    $this->post(route('surat.verifikasi.store'), ['file' => $file])
        ->assertStatus(200)
        ->assertViewIs('surat.verifikasi.hasil')
        ->assertViewHas('surat', fn ($hasil) => $hasil->id === $surat->id);
});
