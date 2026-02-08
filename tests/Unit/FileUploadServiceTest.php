<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\FileUploadService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileUploadServiceTest extends TestCase
{
    protected FileUploadService $service;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->service = new FileUploadService();
    }

    /** @test */
    public function test_upload_gambar_valid()
    {
        // Arrange: Buat fake image
        $file = UploadedFile::fake()->image('test.jpg', 100, 100);
        
        // Act: Upload file
        $path = $this->service->uploadSecure(
            $file,
            'test-uploads',
            ['image/jpeg'],
            2048
        );
        
        // Assert: File tersimpan
        $this->assertNotNull($path);
        $this->assertStringContainsString('test-uploads/', $path);
        Storage::disk('public')->assertExists($path);
    }

    /** @test */
    public function test_tolak_mime_type_salah()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('File type not allowed');
        
        // Buat PDF file tapi hanya boleh JPEG
        $file = UploadedFile::fake()->create('document.pdf', 100);
        
        $this->service->uploadSecure(
            $file,
            'uploads',
            ['image/jpeg'], // Only allow images
            2048
        );
    }

    /** @test */
    public function test_tolak_file_terlalu_besar()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('File size exceeds');
        
        // Buat file 3MB
        $file = UploadedFile::fake()->create('large.jpg', 3072);
        
        // Max hanya 2MB
        $this->service->uploadSecure(
            $file,
            'uploads',
            ['image/jpeg'],
            2048
        );
    }

    /** @test */
    public function test_hapus_file_berhasil()
    {
        // Upload dulu
        $file = UploadedFile::fake()->image('test.jpg');
        $path = $this->service->uploadSecure($file, 'uploads');
        
        // Hapus file
        $result = $this->service->delete($path);
        
        // Verify
        $this->assertTrue($result);
        Storage::disk('public')->assertMissing($path);
    }

    /** @test */
    public function test_upload_multiple_files()
    {
        $files = [
            UploadedFile::fake()->image('test1.jpg'),
            UploadedFile::fake()->image('test2.jpg'),
            UploadedFile::fake()->image('test3.png'),
        ];
        
        $paths = $this->service->uploadMultiple(
            $files, 
            'uploads',
            ['image/jpeg', 'image/png']
        );
        
        $this->assertCount(3, $paths);
        
        foreach ($paths as $path) {
            Storage::disk('public')->assertExists($path);
        }
    }

    /** @test */
    public function test_generate_safe_filename()
    {
        $file = UploadedFile::fake()->image('my test file.jpg');
        $path = $this->service->uploadSecure($file, 'uploads');
        
        // Filename harus tidak mengandung spasi atau karakter aneh
        $filename = basename($path);
        $this->assertMatchesRegularExpression('/^\d+_[a-zA-Z0-9]+\.jpg$/', $filename);
    }

    /** @test */
    public function test_get_allowed_mimes_untuk_image()
    {
        $mimes = FileUploadService::getAllowedMimes('image');
        
        $this->assertIsArray($mimes);
        $this->assertContains('image/jpeg', $mimes);
        $this->assertContains('image/png', $mimes);
    }

    /** @test */
    public function test_get_allowed_mimes_untuk_document()
    {
        $mimes = FileUploadService::getAllowedMimes('document');
        
        $this->assertContains('application/pdf', $mimes);
    }
}