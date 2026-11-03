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
 * Route yang mengubah state tidak boleh menerima GET (rawan CSRF lewat link/img).
 * Lihat issue OpenSID/OpenDK#1780.
 */
dataset('state_changing_routes', [
    'otp deactivate' => ['otp.deactivate', 'POST'],
    '2fa deactivate' => ['2fa.deactivate', 'POST'],
    'info sistem linkstorage' => ['setting.info-sistem.linkstorage', 'POST'],
    'info sistem queuelisten' => ['setting.info-sistem.queuelisten', 'POST'],
    'info sistem migrasi' => ['setting.info-sistem.migrasi', 'POST'],
    'backup database delete' => ['setting.pengaturan-database.delete', 'DELETE'],
    'data umum resetpeta' => ['data.data-umum.resetpeta', 'PATCH'],
    'surat permohonan setujui' => ['surat.permohonan.setujui', 'POST'],
    'sinergi program urut' => ['informasi.sinergi-program.urut', 'POST'],
    'navigation order' => ['setting.navigation.order', 'POST'],
    'user destroy' => ['setting.user.destroy', 'DELETE'],
    'user active' => ['setting.user.active', 'PATCH'],
    'pengurus lock' => ['data.pengurus.lock', 'PATCH'],
    'komentar update status' => ['informasi.komentar-artikel.updateStatus', 'PATCH'],
    'faq update' => ['informasi.faq.update', 'PUT'],
    'artikel update' => ['informasi.artikel.update', 'PUT'],
    'pesan tandai dibaca' => ['pesan.read.mark', 'PATCH'],
    'album show' => ['publikasi.album.show', 'GET'],
]);

test('route memakai verb yang sesuai dengan aksinya', function (string $name, string $method) {
    $route = RouteFacade::getRoutes()->getByName($name);

    expect($route)->not->toBeNull()
        ->and(array_values(array_diff($route->methods(), ['HEAD'])))->toBe([$method]);
})->with('state_changing_routes');

test('route yang mengubah state tidak menerima GET', function (string $name, string $method) {
    if ($method === 'GET') {
        $this->markTestSkipped('Route baca memang memakai GET.');
    }

    $route = RouteFacade::getRoutes()->getByName($name);

    expect($route->methods())->not->toContain('GET');
})->with('state_changing_routes');
