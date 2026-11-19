<?php

use App\Models\FormDokumen;

/*
 * Regresi untuk issue #1813: endpoint unduh dokumen publik tidak boleh
 * menyajikan dokumen yang belum dipublikasi atau sudah kedaluwarsa.
 */

beforeEach(function () {
    $this->filePath = tempnam(sys_get_temp_dir(), 'dokumen_');
    file_put_contents($this->filePath, 'isi dokumen');
});

afterEach(function () {
    if (isset($this->filePath) && is_file($this->filePath)) {
        unlink($this->filePath);
    }
});

function makeFormDokumen(array $attributes, string $filePath): FormDokumen
{
    return FormDokumen::create(array_merge([
        'nama_dokumen' => 'Dokumen Uji',
        'description' => 'Deskripsi uji',
        'file_dokumen' => $filePath,
        'is_published' => true,
        'published_at' => now(),
        'expired_at' => null,
    ], $attributes));
}

it('serves a published document that has no expiry date', function () {
    $dokumen = makeFormDokumen([], $this->filePath);

    $this->get(route('unduhan.form-dokumen.download', $dokumen->id))
        ->assertOk()
        ->assertDownload();
});

it('serves a published document whose expiry date is in the future', function () {
    $dokumen = makeFormDokumen(['expired_at' => now()->addDay()], $this->filePath);

    $this->get(route('unduhan.form-dokumen.download', $dokumen->id))
        ->assertOk()
        ->assertDownload();
});

it('returns 404 for an unpublished document', function () {
    $dokumen = makeFormDokumen(['is_published' => false], $this->filePath);

    $this->get(route('unduhan.form-dokumen.download', $dokumen->id))
        ->assertNotFound();
});

it('returns 404 for an expired document', function () {
    $dokumen = makeFormDokumen(['expired_at' => now()->subDay()], $this->filePath);

    $this->get(route('unduhan.form-dokumen.download', $dokumen->id))
        ->assertNotFound();
});

it('returns 404 for an unknown document id', function () {
    $this->get(route('unduhan.form-dokumen.download', 999999))
        ->assertNotFound();
});
