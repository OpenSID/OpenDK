<?php

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CompleteProfile;
use App\Http\Middleware\GlobalShareMiddleware;
use App\Models\SettingAplikasi;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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

    Storage::fake('public');

    // Nonaktifkan upload_limit agar tidak mengganggu test
    SettingAplikasi::updateOrCreate(
        ['key' => 'upload_limit'],
        ['value' => '0', 'type' => 'boolean', 'kategori' => 'sistem', 'description' => 'test', 'option' => '{}']
    );
});

describe('restoreBackup - validasi ekstensi', function () {
    test('menolak file dengan ekstensi tidak diizinkan (.txt)', function () {
        $file = UploadedFile::fake()->create('backup.txt', 100);

        $response = $this->postJson(
            route('setting.pengaturan-database.runrestore'),
            ['backupFile' => $file],
            ['X-Requested-With' => 'XMLHttpRequest']
        );

        $response->assertStatus(422);
        $response->assertJson(['message' => 'File harus berupa .sql atau .zip']);
    });

    test('menolak file dengan ekstensi tidak diizinkan (.exe)', function () {
        $file = UploadedFile::fake()->create('malware.exe', 100);

        $response = $this->postJson(
            route('setting.pengaturan-database.runrestore'),
            ['backupFile' => $file],
            ['X-Requested-With' => 'XMLHttpRequest']
        );

        $response->assertStatus(422);
    });

    test('menolak request tanpa file', function () {
        $response = $this->postJson(
            route('setting.pengaturan-database.runrestore'),
            [],
            ['X-Requested-With' => 'XMLHttpRequest']
        );

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['backupFile']);
    });
});

describe('restoreBackup - file ZIP corrupt', function () {
    test('mengembalikan error 500 untuk ZIP yang rusak', function () {
        // Buat file dengan ZIP magic bytes (PK) tapi content corrupt
        // agar pass MIME validation tapi gagal di ZipArchive::open()
        $tmpFile = tempnam(sys_get_temp_dir(), 'test_') . '.zip';
        file_put_contents($tmpFile, "PK\x03\x04" . str_repeat("\x00", 100));

        $file = new UploadedFile($tmpFile, 'corrupt.zip', 'application/zip', null, true);

        $response = $this->postJson(
            route('setting.pengaturan-database.runrestore'),
            ['backupFile' => $file],
            ['X-Requested-With' => 'XMLHttpRequest']
        );

        $response->assertStatus(500);
        $response->assertJsonPath('message', 'Gagal membuka file ZIP. Pastikan file tidak rusak.');

        // Cleanup
        if (file_exists($tmpFile)) {
            unlink($tmpFile);
        }
    });
});

describe('restoreBackup - file SQL valid diterima', function () {
    test('menerima file .sql dan memproses restore', function () {
        // Buat file SQL sederhana — restore akan gagal karena mysql command,
        // tapi kita hanya memverifikasi bahwa file diterima (tidak 422)
        $sqlContent = "-- Test SQL dump\n-- Nothing to execute\n";
        $tmpFile = tempnam(sys_get_temp_dir(), 'test_') . '.sql';
        file_put_contents($tmpFile, $sqlContent);

        $file = new UploadedFile($tmpFile, 'test-backup.sql', 'text/plain', null, true);

        $response = $this->postJson(
            route('setting.pengaturan-database.runrestore'),
            ['backupFile' => $file],
            ['X-Requested-With' => 'XMLHttpRequest']
        );

        // Akan 500 karena mysql command tidak tersedia di test env,
        // tapi yang penting bukan 422 (validasi ekstensi lolos)
        expect($response->status())->not->toBe(422);

        // Cleanup
        if (file_exists($tmpFile)) {
            unlink($tmpFile);
        }
    });
});
