<?php

use App\Models\DataUmum;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Cache;

uses(DatabaseTransactions::class);

beforeEach(function () {
    Cache::flush();
});

test('perubahan data umum langsung tampil pada api profil', function () {
    $dataUmum = DataUmum::firstOrFail();
    $dataUmum->update(['bts_wil_utara' => 'Batas Lama Cache']);

    // Respons pertama disimpan ke cache oleh endpoint profil
    $this->getJson('/api/frontend/v1/profil?include=dataUmum')
        ->assertSuccessful()
        ->assertSee('Batas Lama Cache', false);

    // Data umum diubah melalui admin
    $dataUmum->update(['bts_wil_utara' => 'Batas Baru Setelah Edit']);

    // Website harus langsung menampilkan data baru, bukan cache lama
    $this->getJson('/api/frontend/v1/profil?include=dataUmum')
        ->assertSuccessful()
        ->assertSee('Batas Baru Setelah Edit', false)
        ->assertDontSee('Batas Lama Cache', false);
});
