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
        $response->assertJson(['message' => 'Hanya file .zip dari backup system yang diizinkan untuk restore.']);
    });

    test('menolak file .sql (tidak lagi didukung)', function () {
        $file = UploadedFile::fake()->create('backup.sql', 100);

        $response = $this->postJson(
            route('setting.pengaturan-database.runrestore'),
            ['backupFile' => $file],
            ['X-Requested-With' => 'XMLHttpRequest']
        );

        $response->assertStatus(422);
        $response->assertJson([
            'success'  => false,
            'message'  => 'Hanya file .zip dari backup system yang diizinkan untuk restore.',
        ]);
    });

    test('menolak file dengan ekstensi tidak diizinkan (.exe)', function () {
        $file = UploadedFile::fake()->create('malware.exe', 100);

        $response = $this->postJson(
            route('setting.pengaturan-database.runrestore'),
            ['backupFile' => $file],
            ['X-Requested-With' => 'XMLHttpRequest']
        );

        $response->assertStatus(422);
        $response->assertJson(['success' => false]);
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
    test('menolak file .sql dan mengembalikan 422', function () {
        $sqlContent = "-- Test SQL dump\n-- Nothing to execute\n";
        $tmpFile = tempnam(sys_get_temp_dir(), 'test_') . '.sql';
        file_put_contents($tmpFile, $sqlContent);

        $file = new UploadedFile($tmpFile, 'test-backup.sql', 'text/plain', null, true);

        $response = $this->postJson(
            route('setting.pengaturan-database.runrestore'),
            ['backupFile' => $file],
            ['X-Requested-With' => 'XMLHttpRequest']
        );

        // .sql tidak lagi diterima
        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'Hanya file .zip dari backup system yang diizinkan untuk restore.',
        ]);

        if (file_exists($tmpFile)) {
            unlink($tmpFile);
        }
    });
});

describe('restoreBackup - multiple db-dump entries dalam ZIP', function () {
    test('mengembalikan error 500 jika ZIP mengandung lebih dari 1 db-dump', function () {
        // Buat ZIP dengan 2 file di db-dumps/
        $tmpZip = tempnam(sys_get_temp_dir(), 'test_') . '.zip';
        $zip = new ZipArchive();
        $zip->open($tmpZip, ZipArchive::CREATE);
        $zip->addFromString('db-dumps/dump1.sql', '-- dump 1');
        $zip->addFromString('db-dumps/dump2.sql', '-- dump 2');
        $zip->close();

        $file = new UploadedFile($tmpZip, 'multi-dump.zip', 'application/zip', null, true);

        $response = $this->postJson(
            route('setting.pengaturan-database.runrestore'),
            ['backupFile' => $file],
            ['X-Requested-With' => 'XMLHttpRequest']
        );

        $response->assertStatus(500);
        $response->assertJsonPath('success', false);
        expect($response->json('message'))->toContain('database dump');

        if (file_exists($tmpZip)) {
            unlink($tmpZip);
        }
    });
});

describe('restoreBackup - ZIP slip pada db-dumps/', function () {
    test('entry db-dumps dengan path traversal dilewati, tidak error fatal', function () {
        // ZIP berisi 1 entry db-dumps dengan traversal path
        // Entry harus di-skip, tidak crash, dan tidak mengekstrak file di luar tempBase
        $tmpZip = tempnam(sys_get_temp_dir(), 'test_') . '.zip';
        $zip = new ZipArchive();
        $zip->open($tmpZip, ZipArchive::CREATE);
        $zip->addFromString('db-dumps/../../evil.sql', '-- traversal');
        $zip->close();

        $file = new UploadedFile($tmpZip, 'slip-test.zip', 'application/zip', null, true);

        $response = $this->postJson(
            route('setting.pengaturan-database.runrestore'),
            ['backupFile' => $file],
            ['X-Requested-With' => 'XMLHttpRequest']
        );

        // Entry traversal di-skip → tidak ada dump valid → response 200 dengan 0 file
        // Yang penting: tidak 422 (file ZIP diterima) dan evil.sql tidak ada di luar tempBase
        $response->assertStatus(200);
        $response->assertJsonPath('success', true);

        // Pastikan file traversal tidak diekstrak ke luar direktori backup-temp
        expect(file_exists(storage_path('evil.sql')))->toBeFalse();
        expect(file_exists(storage_path('app/evil.sql')))->toBeFalse();

        if (file_exists($tmpZip)) {
            unlink($tmpZip);
        }
    });
});

