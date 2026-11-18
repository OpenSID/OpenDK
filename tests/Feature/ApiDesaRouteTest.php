<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 */

use Illuminate\Support\Facades\Route as RouteFacade;

/*
 * Endpoint publik daftar semua desa tidak boleh 500 untuk pengunjung.
 * Lihat issue OpenSID/OpenDK#1801.
 */
test('route api.desa terdaftar dengan nama yang benar', function () {
    expect(RouteFacade::getRoutes()->getByName('api.desa'))->not->toBeNull();
});

test('pengunjung publik bisa mengakses /api/desa tanpa error 500', function () {
    $this->getJson('/api/desa')
        ->assertOk()
        ->assertJsonStructure(['data', 'current_page', 'total']);
});
