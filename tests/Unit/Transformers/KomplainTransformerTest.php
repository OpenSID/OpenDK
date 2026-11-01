<?php

use App\Enums\Anonim;
use App\Models\Komplain;
use App\Transformers\KomplainTransformer;

function komplainPelapor(int $anonim): Komplain
{
    $komplain = new Komplain([
        'judul' => 'Jalan Rusak',
        'laporan' => 'Jalan di depan rumah rusak parah',
        'nik' => '1234567890123456',
        'nama' => 'Budi Santoso',
        'anonim' => $anonim,
    ]);
    $komplain->detail_penduduk = json_encode(['nik' => '1234567890123456', 'nama' => 'Budi Santoso']);

    return $komplain;
}

test('komplain anonim menyembunyikan identitas pelapor', function () {
    $output = (new KomplainTransformer())->transform(komplainPelapor(Anonim::Sembunyikan));

    expect($output['nama'])->toBeNull()
        ->and($output['detail_penduduk'])->toBeNull()
        ->and($output['anonim'])->toBe(Anonim::Sembunyikan);
});

test('komplain tidak anonim menampilkan nama tetapi tidak detail penduduk', function () {
    $output = (new KomplainTransformer())->transform(komplainPelapor(Anonim::Tampilkan));

    expect($output['nama'])->toBe('Budi Santoso')
        ->and($output['detail_penduduk'])->toBeNull();
});

test('nik tidak pernah dikembalikan pada respons komplain', function () {
    foreach ([Anonim::Sembunyikan, Anonim::Tampilkan] as $anonim) {
        $output = (new KomplainTransformer())->transform(komplainPelapor($anonim));

        expect($output)->not->toHaveKey('nik');
    }
});

test('include penduduk tidak tersedia pada komplain', function () {
    expect((new KomplainTransformer())->getAvailableIncludes())->not->toContain('penduduk');
});

test('komplain tanpa nilai anonim dianggap anonim', function () {
    $komplain = komplainPelapor(Anonim::Tampilkan);
    $komplain->anonim = null;

    $output = (new KomplainTransformer())->transform($komplain);

    expect($output['nama'])->toBeNull()
        ->and($output['detail_penduduk'])->toBeNull();
});
