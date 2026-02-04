<?php

use App\Models\Surat;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('preview file url is correct', function () {
    Storage::fake('public');
    $file = UploadedFile::fake()->create('surat-test.pdf', 100, 'application/pdf');
    $file->storeAs('surat', 'surat-test.pdf', 'public');

    $surat = Surat::factory()->create([
        'file' => 'surat-test.pdf',
    ]);

    $pathSurat = asset('storage/surat/' . $surat->file);
    expect($pathSurat)->toContain('surat-test.pdf');
});
