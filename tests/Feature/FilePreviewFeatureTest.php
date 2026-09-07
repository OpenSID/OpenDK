<?php

namespace Tests\Feature;

use App\Http\Middleware\CompleteProfile;
use App\Models\Potensi;
use App\Models\Prosedur;
use App\Models\Regulasi;
use App\Models\TipePotensi;
use App\Models\TipeRegulasi;
use App\Models\User;

describe('File Preview Feature & Security Tests', function () {
    beforeEach(function () {
        $this->withoutMiddleware([CompleteProfile::class]);

        $this->user = User::first();
        $this->actingAs($this->user);

        // Prepare test directory
        $this->testDir = public_path('storage/test_previews');
        if (!file_exists($this->testDir)) {
            mkdir($this->testDir, 0777, true);
        }

        // Create dummy PDF and Image files
        $this->pdfPath = $this->testDir . '/sample.pdf';
        file_put_contents($this->pdfPath, "%PDF-1.4\n%test pdf content");

        $this->imgPath = $this->testDir . '/sample.png';
        // 1x1 transparent PNG
        file_put_contents($this->imgPath, base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg=='));
    });

    afterEach(function () {
        if (file_exists($this->pdfPath)) {
            @unlink($this->pdfPath);
        }
        if (file_exists($this->imgPath)) {
            @unlink($this->imgPath);
        }
        if (is_dir($this->testDir)) {
            @rmdir($this->testDir);
        }
    });

    test('prosedur show page renders iframe for PDF and img for image', function () {
        $prosedurPdf = Prosedur::create([
            'judul_prosedur' => 'Prosedur Test PDF',
            'file_prosedur'  => 'storage/test_previews/sample.pdf',
            'mime_type'      => 'application/pdf',
        ]);

        $responsePdf = $this->get(route('informasi.prosedur.show', $prosedurPdf->id));
        $responsePdf->assertStatus(200);
        $responsePdf->assertSee('<iframe', false);
        $responsePdf->assertSee('class="showpdf"', false);

        $prosedurImg = Prosedur::create([
            'judul_prosedur' => 'Prosedur Test Image',
            'file_prosedur'  => 'storage/test_previews/sample.png',
            'mime_type'      => 'image/png',
        ]);

        $responseImg = $this->get(route('informasi.prosedur.show', $prosedurImg->id));
        $responseImg->assertStatus(200);
        $responseImg->assertSee('<img', false);

        $prosedurPdf->delete();
        $prosedurImg->delete();
    });

    test('regulasi show page renders iframe for PDF and img for image', function () {
        $tipe = TipeRegulasi::first();

        $regulasiPdf = Regulasi::create([
            'tipe_regulasi' => $tipe->id,
            'judul'         => 'Regulasi Test PDF',
            'deskripsi'     => 'Deskripsi Regulasi PDF',
            'file_regulasi' => 'storage/test_previews/sample.pdf',
            'mime_type'     => 'application/pdf',
        ]);

        $responsePdf = $this->get(route('informasi.regulasi.show', $regulasiPdf->id));
        $responsePdf->assertStatus(200);
        $responsePdf->assertSee('<iframe', false);
        $responsePdf->assertSee('class="showpdf"', false);

        $regulasiImg = Regulasi::create([
            'tipe_regulasi' => $tipe->id,
            'judul'         => 'Regulasi Test Image',
            'deskripsi'     => 'Deskripsi Regulasi Image',
            'file_regulasi' => 'storage/test_previews/sample.png',
            'mime_type'     => 'image/png',
        ]);

        $responseImg = $this->get(route('informasi.regulasi.show', $regulasiImg->id));
        $responseImg->assertStatus(200);
        $responseImg->assertSee('<img', false);

        $regulasiPdf->delete();
        $regulasiImg->delete();
    });

    test('potensi show page renders iframe for PDF and img for image', function () {
        $tipe = TipePotensi::first();

        $potensiPdf = Potensi::create([
            'kategori_id'  => $tipe->id,
            'nama_potensi' => 'Potensi Wisata PDF',
            'deskripsi'    => 'Deskripsi Potensi PDF',
            'lokasi'       => 'Lokasi Wisata',
            'file_gambar'  => 'storage/test_previews/sample.pdf',
            'mime_type'    => 'application/pdf',
        ]);

        $responsePdf = $this->get(route('informasi.potensi.show', $potensiPdf->id));
        $responsePdf->assertStatus(200);
        $responsePdf->assertSee('<iframe', false);
        $responsePdf->assertSee('class="showpdf"', false);

        $potensiImg = Potensi::create([
            'kategori_id'  => $tipe->id,
            'nama_potensi' => 'Potensi Wisata Image',
            'deskripsi'    => 'Deskripsi Potensi Image',
            'lokasi'       => 'Lokasi Wisata',
            'file_gambar'  => 'storage/test_previews/sample.png',
            'mime_type'    => 'image/png',
        ]);

        $responseImg = $this->get(route('informasi.potensi.show', $potensiImg->id));
        $responseImg->assertStatus(200);
        $responseImg->assertSee('<img', false);

        $potensiPdf->delete();
        $potensiImg->delete();
    });

    test('download rejects path traversal attempts securely', function () {
        $prosedurEvil = Prosedur::create([
            'judul_prosedur' => 'Evil Prosedur',
            'file_prosedur'  => '../../../../Windows/win.ini',
            'mime_type'      => 'application/pdf',
        ]);

        // Download attempt with traversal path should not return the file
        $downloadResponse = $this->get(route('informasi.prosedur.download', $prosedurEvil->id));
        expect($downloadResponse->status())->toBeIn([302, 403, 404]);

        $prosedurEvil->delete();
    });
});
