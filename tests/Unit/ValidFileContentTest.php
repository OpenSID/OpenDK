<?php

use App\Rules\SafeFileContent;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;

function validPdfBinary(): string
{
    return "%PDF-1.4\n".
        "1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj\n".
        "2 0 obj<</Type/Pages/Kids[3 0 R]/Count 1>>endobj\n".
        "3 0 obj<</Type/Page/Parent 2 0 R/MediaBox[0 0 612 792]>>endobj\n".
        "stream\nBT /F1 12 Tf 72 720 Td (Function of the Quarterly Report) Tj ET\nendstream\n".
        "xref\n0 4\n".
        "trailer<</Size 4/Root 1 0 R>>\n".
        "startxref\n0\n%%EOF";
}

function svgWith(string $body): string
{
    return '<?xml version="1.0" encoding="UTF-8"?>'."\n".
        '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="100" height="100">'.
        $body.
        '</svg>';
}

function passRule(mixed $file): bool
{
    $failed = false;
    (new SafeFileContent())->validate('file', $file, function () use (&$failed) {
        $failed = true;
    });

    return ! $failed;
}

describe('valid_file content inspection', function () {
    test('pdf valid yang mengandung string Function tetap lolos', function () {
        $file = UploadedFile::fake()->createWithContent('renja.pdf', validPdfBinary());

        expect(passRule($file))->toBeTrue();
    });

    test('pdf tanpa konten berbahaya lolos', function () {
        $file = UploadedFile::fake()->createWithContent('dokumen.pdf', "%PDF-1.7\n%%EOF");

        expect(passRule($file))->toBeTrue();
    });

    test('pdf yang menyamar dengan header pdf tetapi berisi kode php ditolak', function () {
        $polyglot = "%PDF-1.4\n<?php system(\$_GET['c']); ?>\n%%EOF";
        $file = UploadedFile::fake()->createWithContent('shell.pdf', $polyglot);

        expect(passRule($file))->toBeFalse();
    });

    test('file php yang diberi ekstensi pdf ditolak', function () {
        $file = UploadedFile::fake()->createWithContent('shell.pdf', '<?php system($_GET["c"]); ?>');

        expect(passRule($file))->toBeFalse();
    });

    test('svg dengan tag script ditolak', function () {
        $file = UploadedFile::fake()->createWithContent('logo.svg', svgWith('<script>alert(1)</script>'));

        expect(passRule($file))->toBeFalse();
    });

    test('svg dengan event handler onload ditolak', function () {
        $file = UploadedFile::fake()->createWithContent('logo.svg', svgWith('<rect onload="alert(1)"/>'));

        expect(passRule($file))->toBeFalse();
    });

    test('svg dengan javascript pada xlink href ditolak', function () {
        $file = UploadedFile::fake()->createWithContent('logo.svg', svgWith('<a xlink:href="javascript:alert(1)">x</a>'));

        expect(passRule($file))->toBeFalse();
    });

    test('svg dengan foreignObject berisi html ditolak', function () {
        $file = UploadedFile::fake()->createWithContent('logo.svg', svgWith('<foreignObject><body onload="alert(1)"/></foreignObject>'));

        expect(passRule($file))->toBeFalse();
    });

    test('svg bersih lolos', function () {
        $file = UploadedFile::fake()->createWithContent('logo.svg', svgWith('<rect width="100" height="100" fill="red"/>'));

        expect(passRule($file))->toBeTrue();
    });

    test('html dengan script ditolak', function () {
        $file = UploadedFile::fake()->createWithContent('page.html', '<html><script>alert(1)</script></html>');

        expect(passRule($file))->toBeFalse();
    });

    test('file yang bukan uploaded file ditolak', function () {
        expect(passRule('bukan-file'))->toBeFalse();
        expect(passRule(null))->toBeFalse();
    });

    test('gambar png dan jpeg asli tetap lolos', function () {
        expect(passRule(UploadedFile::fake()->image('foto.png', 20, 20)))->toBeTrue();
        expect(passRule(UploadedFile::fake()->image('foto.jpg', 20, 20)))->toBeTrue();
    });

    test('file yang menyamar sebagai gambar tetapi berisi html ditolak', function () {
        $file = UploadedFile::fake()->createWithContent('foto.png', '<html><script>alert(1)</script></html>');

        expect(passRule($file))->toBeFalse();
    });
});

describe('valid_file rule registration', function () {
    test('rule valid_file menerima pdf berisi string Function', function () {
        $validator = Validator::make(
            ['file_dokumen' => UploadedFile::fake()->createWithContent('renja.pdf', validPdfBinary())],
            ['file_dokumen' => 'valid_file']
        );

        expect($validator->passes())->toBeTrue();
    });

    test('rule valid_file menolak svg berbahaya', function () {
        $validator = Validator::make(
            ['file_dokumen' => UploadedFile::fake()->createWithContent('logo.svg', svgWith('<script>alert(1)</script>'))],
            ['file_dokumen' => 'valid_file']
        );

        expect($validator->fails())->toBeTrue();
        expect($validator->errors()->first('file_dokumen'))
            ->toBe(trans('validation.valid_file', ['attribute' => 'file dokumen']));
    });

    test('pesan valid_file tidak menyisakan placeholder', function () {
        $pesan = trans('validation.valid_file', ['attribute' => 'file dokumen']);

        expect($pesan)->not->toMatch('/:\w+/');
    });
});
