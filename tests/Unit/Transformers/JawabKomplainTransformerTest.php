<?php

use App\Models\JawabKomplain;
use App\Transformers\JawabKomplainTransformer;

test('nik tidak dikembalikan pada jawaban komplain', function () {
    $jawab = new JawabKomplain();
    $jawab->komplain_id = 123456;
    $jawab->jawaban = 'Sudah kami tindak lanjuti';
    $jawab->penjawab = 'Admin';
    $jawab->nik = '1234567890123456';

    $output = (new JawabKomplainTransformer())->transform($jawab);

    expect($output)->not->toHaveKey('nik')
        ->and($output['penjawab'])->toBe('Admin');
});
