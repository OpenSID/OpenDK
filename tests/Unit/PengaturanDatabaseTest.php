<?php

use App\Http\Controllers\Setting\PengaturanDatabaseController;

describe('PengaturanDatabaseController - mapZipEntryToStoragePath', function () {
    beforeEach(function () {
        $controller = app(PengaturanDatabaseController::class);
        $this->method = new \ReflectionMethod($controller, 'mapZipEntryToStoragePath');
        $this->method->setAccessible(true);
        $this->controller = $controller;
    });

    test('memetakan path relative baru dengan benar', function () {
        $result = $this->method->invoke($this->controller, 'storage/app/public/artikel/foto.jpg');

        expect($result)->toBe('public/artikel/foto.jpg');
    });

    test('memetakan path absolute Windows', function () {
        $result = $this->method->invoke($this->controller, 'C:/laragon/www/OpenDK/storage/app/public/artikel/foto.jpg');

        expect($result)->toBe('public/artikel/foto.jpg');
    });

    test('memetakan path absolute Linux', function () {
        $result = $this->method->invoke($this->controller, '/var/www/html/storage/app/public/artikel/foto.jpg');

        expect($result)->toBe('public/artikel/foto.jpg');
    });

    test('menangani path dengan backslash yang sudah dinormalkan', function () {
        // Backslash normalization (str_replace('\\', '/')) dilakukan di restoreFromZip()
        // sebelum memanggil method ini. Di sini kita menguji dengan path yang sudah dinormalkan.
        $result = $this->method->invoke($this->controller, 'storage/app/public/foto.jpg');

        expect($result)->toBe('public/foto.jpg');
    });

    test('menolak path traversal', function () {
        $entries = [
            'storage/app/public/../../../etc/passwd',
            'storage/app/../../config/database.php',
        ];

        foreach ($entries as $entry) {
            $result = $this->method->invoke($this->controller, $entry);
            expect($result)->toBeNull();
        }
    });

    test('menolak entri db-dumps', function () {
        $result = $this->method->invoke($this->controller, 'db-dumps/mysql-opendk.sql');

        expect($result)->toBeNull();
    });

    test('menolak direktori yang dikecualikan', function () {
        $skipDirs = [
            'storage/app/backup-storage/old-backup.zip',
            'storage/app/backup-temp/restore-dump.sql',
            'storage/app/framework/cache/data',
            'storage/app/logs/laravel.log',
            'storage/app/debugbar/trace.json',
        ];

        foreach ($skipDirs as $entry) {
            $result = $this->method->invoke($this->controller, $entry);
            expect($result)->toBeNull();
        }
    });

    test('menolak entri tanpa marker storage/app/', function () {
        $entries = [
            'vendor/laravel/framework/src/Illuminate/Foundation/Application.php',
            'composer.json',
            'app/Models/User.php',
        ];

        foreach ($entries as $entry) {
            $result = $this->method->invoke($this->controller, $entry);
            expect($result)->toBeNull();
        }
    });

    test('menolak path kosong setelah marker', function () {
        $result = $this->method->invoke($this->controller, 'storage/app/');

        expect($result)->toBeNull();
    });

    test('menangani path dengan subdirektori dalam', function () {
        $result = $this->method->invoke($this->controller, 'storage/app/public/artikel/2024/01/foto-artikel.jpg');

        expect($result)->toBe('public/artikel/2024/01/foto-artikel.jpg');
    });
});

describe('PengaturanDatabaseController - deleteTemporaryDirectory', function () {
    beforeEach(function () {
        $controller = app(App\Http\Controllers\Setting\PengaturanDatabaseController::class);
        $this->method = new \ReflectionMethod($controller, 'deleteTemporaryDirectory');
        $this->method->setAccessible(true);
        $this->controller = $controller;
    });

    test('menolak path di luar storage/ dan mengembalikan false', function () {
        // Path di luar storage (misal: direktori sistem)
        $outsidePath = sys_get_temp_dir() . '/outside-storage-test';

        $result = $this->method->invoke($this->controller, $outsidePath);

        expect($result)->toBeFalse();
    });

    test('mengembalikan false untuk path yang tidak dapat diresolve', function () {
        $nonExistentPath = storage_path('app/non-existent-dir-xyz123');

        $result = $this->method->invoke($this->controller, $nonExistentPath);

        // Path tidak ada → realpath() return false → method return false
        expect($result)->toBeFalse();
    });

    test('berhasil menghapus direktori kosong di dalam storage/', function () {
        // Buat direktori sementara di dalam storage/
        $testDir = storage_path('app/test-delete-temp-' . uniqid());
        mkdir($testDir, 0755, true);

        expect(is_dir($testDir))->toBeTrue();

        $result = $this->method->invoke($this->controller, $testDir);

        expect($result)->not->toBeFalse();
        expect(is_dir($testDir))->toBeFalse();
    });
});
