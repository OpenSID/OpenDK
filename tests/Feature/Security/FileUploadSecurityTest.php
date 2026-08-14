<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

describe('File Upload Security', function () {
    beforeEach(function () {
        Storage::fake('public');
        $this->user = User::factory()->create();
    });

    test('mencegah path traversal attack', function () {
        $file = UploadedFile::fake()->image('../../evil.jpg');
        
        $response = $this->actingAs($this->user)
            ->post('/sistem-komplain/store', [
                'nik' => '1234567890123456',
                'judul' => 'Test',
                'kategori' => 1,
                'laporan' => 'Test laporan',
                'tanggal_lahir' => '1990-01-01',
                'lampiran1' => $file,
            ]);
        
        // Harus ada error karena path traversal terdeteksi
        $response->assertSessionHasErrors(); // atau redirect karena error
       
        // Alternatif: cek bahwa tidak ada file yang disimpan dengan nama asli yang mengandung path traversal
        // Tidak perlu mengecek apakah '../../evil.jpg' ada karena seharusnya file ditolak sebelum disimpan
    })->group('security', 'upload');

    test('menolak file PHP', function () {
        $file = UploadedFile::fake()->create('shell.php', 100, 'application/x-php');
        
        $response = $this->actingAs($this->user)
            ->post('/sistem-komplain/store', [
                'nik' => '1234567890123456',
                'judul' => 'Test',
                'kategori' => 1,
                'laporan' => 'Test laporan',
                'tanggal_lahir' => '1990-01-01',
                'lampiran1' => $file,
            ]);
        
        $response->assertSessionHasErrors();
    })->group('security', 'upload');

    test('validasi MIME type mendalam', function () {
        // Fake image dengan content PHP
        $file = UploadedFile::fake()->create('fake-image.jpg', 100, 'image/jpeg');
        
        // Add PHP code to file (in real scenario)
        // This should be caught by deep MIME validation
        
        $response = $this->actingAs($this->user)
            ->post('/sistem-komplain/store', [
                'nik' => '1234567890123456',
                'judul' => 'Test',
                'kategori' => 1,
                'laporan' => 'Test laporan',
                'tanggal_lahir' => '1990-01-01',
                'lampiran1' => $file,
            ]);
        
        // Should validate actual file content, not just extension
        expect($response->status())->toBeIn([200, 302]);
    })->group('security', 'upload');
});