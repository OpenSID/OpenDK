<?php

namespace Tests\Feature\Surat;

use App\Models\Surat;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PermohonanPratinjauSuratTest extends TestCase
{
    /** @test */
    public function test_preview_file_url_is_correct()
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->create('surat-test.pdf', 100, 'application/pdf');
        $file->storeAs('surat', 'surat-test.pdf', 'public');

        $surat = Surat::factory()->create([
            'file' => 'surat-test.pdf',
        ]);

        $pathSurat = asset('storage/surat/' . $surat->file);
        $this->assertStringContainsString('surat-test.pdf', $pathSurat);
    }
}
