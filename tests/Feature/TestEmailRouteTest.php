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

use App\Models\User;
use App\Notifications\TestEmail;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route as RouteFacade;

/*
 * Fitur kirim email uji hanya untuk admin yang sudah login.
 * Lihat issue OpenSID/OpenDK#1801.
 */
uses(DatabaseTransactions::class);

beforeEach(function () {
    $this->pengguna = User::factory()->create();
    $this->pengguna->forceFill(['status' => 1])->save();
});

test('route testEmail memakai middleware auth', function () {
    $middleware = RouteFacade::getRoutes()->getByName('testEmail')->gatherMiddleware();

    expect($middleware)->toContain('auth:web');
});

test('tamu yang belum login diarahkan ke halaman login, bukan error 500', function () {
    // TestCase dasar otomatis login; paksa keluar agar request ini sebagai tamu
    Auth::logout();
    $this->app['auth']->forgetGuards();

    $this->get(route('testEmail'))
        ->assertRedirect(route('login'));
});

test('pengguna yang sudah login bisa mengirim email uji', function () {
    Notification::fake();

    $this->actingAs($this->pengguna)
        ->get(route('testEmail'))
        ->assertOk()
        ->assertJson(['message' => 'Email berhasil dikirim']);

    Notification::assertSentTo($this->pengguna, TestEmail::class);
});
