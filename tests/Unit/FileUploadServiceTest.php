<?php

use App\Services\FileUploadService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

describe('FileUploadService', function () {
    beforeEach(function () {
        Storage::fake('public');
        $this->service = new FileUploadService();
    });

    test('dapat upload file dengan aman', function () {
        $file = UploadedFile::fake()->image('test.jpg', 800, 600);
        
        $path = $this->service->uploadSecure(
            $file,
            'test-directory',
            ['image/jpeg'],
            2048
        );
        
        expect($path)->toBeString();
        Storage::disk('public')->assertExists($path);
    });

    test('menolak file dengan MIME type tidak diizinkan', function () {
        $file = UploadedFile::fake()->create('test.pdf', 100);
        
        $this->service->uploadSecure(
            $file,
            'test-directory',
            ['image/jpeg'], // Hanya izinkan JPEG
            2048
        );
    })->throws(\InvalidArgumentException::class);

    test('menolak file yang terlalu besar', function () {
        $file = UploadedFile::fake()->image('large.jpg')->size(5000); // 5MB
        
        $this->service->uploadSecure(
            $file,
            'test-directory',
            ['image/jpeg'],
            1024 // Max 1MB
        );
    })->throws(\InvalidArgumentException::class);

    test('generate filename yang aman', function () {
        $file = UploadedFile::fake()->image('../../evil.jpg');
        
        $path = $this->service->uploadSecure(
            $file,
            'test-directory',
            ['image/jpeg'],
            2048
        );
        
        expect($path)->not->toContain('..');
        expect($path)->not->toContain('evil');
    });

    test('dapat upload multiple files', function () {
        $files = [
            UploadedFile::fake()->image('test1.jpg'),
            UploadedFile::fake()->image('test2.jpg'),
        ];
        
        $paths = $this->service->uploadMultiple(
            $files,
            'test-directory',
            ['image/jpeg'],
            2048
        );
        
        expect($paths)->toHaveCount(2);
        foreach ($paths as $path) {
            Storage::disk('public')->assertExists($path);
        }
    });
});