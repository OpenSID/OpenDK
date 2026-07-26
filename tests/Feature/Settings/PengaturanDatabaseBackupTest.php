<?php

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CompleteProfile;
use App\Http\Middleware\GlobalShareMiddleware;
use App\Models\SettingAplikasi;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;

uses(DatabaseTransactions::class);

beforeEach(function () {
    $this->withoutMiddleware([
        Authenticate::class,
        RoleMiddleware::class,
        PermissionMiddleware::class,
        CompleteProfile::class,
        GlobalShareMiddleware::class,
    ]);
});

describe('createBackup - response handling', function () {
    test('mengembalikan success=true ketika backup berhasil (exit code 0)', function () {
        Artisan::shouldReceive('call')
            ->with('backup:run')
            ->andReturn(0);

        Artisan::shouldReceive('output')
            ->andReturn('Backup completed!');

        $response = $this->postJson(
            route('setting.pengaturan-database.runbackup'),
            [],
            ['X-Requested-With' => 'XMLHttpRequest']
        );

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    });

    test('mengembalikan success=false ketika backup gagal (exit code non-zero)', function () {
        Artisan::shouldReceive('call')
            ->with('backup:run')
            ->andReturn(1);

        Artisan::shouldReceive('output')
            ->andReturn('mysqldump not found');

        $response = $this->postJson(
            route('setting.pengaturan-database.runbackup'),
            [],
            ['X-Requested-With' => 'XMLHttpRequest']
        );

        $response->assertStatus(500);
        $response->assertJson(['success' => false]);
        $response->assertJsonPath('message', 'Backup gagal. Periksa log aplikasi untuk detail.');
    });
});
