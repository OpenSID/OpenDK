<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Rules\SecureFileUpload;
use Illuminate\Http\UploadedFile;

class SecureFileUploadTest extends TestCase
{
    /** @test */
    public function test_validasi_pass_untuk_file_valid()
    {
        $rule = new SecureFileUpload(['image/jpeg'], 2048);
        $file = UploadedFile::fake()->image('test.jpg', 100, 100);
        
        $hasFailed = false;
        $rule->validate('file', $file, function($message) use (&$hasFailed) {
            $hasFailed = true;
        });
        
        $this->assertFalse($hasFailed, 'File valid seharusnya lolos validasi');
    }

    /** @test */
    public function test_tolak_ekstensi_berbahaya_php()
    {
        $rule = new SecureFileUpload();
        $file = UploadedFile::fake()->create('malicious.php', 100);
        
        $failMessage = null;
        $rule->validate('file', $file, function($message) use (&$failMessage) {
            $failMessage = $message;
        });
        
        $this->assertNotNull($failMessage);
        $this->assertStringContainsString('forbidden file extension', $failMessage);
    }

    /** @test */
    public function test_tolak_ekstensi_berbahaya_exe()
    {
        $rule = new SecureFileUpload();
        $file = UploadedFile::fake()->create('virus.exe', 100);
        
        $failMessage = null;
        $rule->validate('file', $file, function($message) use (&$failMessage) {
            $failMessage = $message;
        });
        
        $this->assertStringContainsString('forbidden file extension', $failMessage);
    }

    /** @test */
    public function test_tolak_mime_type_tidak_sesuai()
    {
        $rule = new SecureFileUpload(['image/jpeg'], 2048);
        $file = UploadedFile::fake()->create('document.pdf', 100);
        
        $failMessage = null;
        $rule->validate('file', $file, function($message) use (&$failMessage) {
            $failMessage = $message;
        });
        
        $this->assertNotNull($failMessage);
        $this->assertStringContainsString('must be one of', $failMessage);
    }

    /** @test */
    public function test_tolak_file_terlalu_besar()
    {
        $rule = new SecureFileUpload(['image/jpeg'], 100); // Max 100KB
        $file = UploadedFile::fake()->create('large.jpg', 500); // 500KB
        
        $failMessage = null;
        $rule->validate('file', $file, function($message) use (&$failMessage) {
            $failMessage = $message;
        });
        
        $this->assertNotNull($failMessage);
        $this->assertStringContainsString('may not be greater than', $failMessage);
    }

    /** @test */
    public function test_tolak_bukan_uploaded_file()
    {
        $rule = new SecureFileUpload();
        
        $failMessage = null;
        $rule->validate('file', 'not-a-file', function($message) use (&$failMessage) {
            $failMessage = $message;
        });
        
        $this->assertStringContainsString('must be a valid file', $failMessage);
    }

    /** @test */
    public function test_semua_ekstensi_berbahaya_ditolak()
    {
        $dangerousExtensions = ['php', 'phtml', 'php3', 'php4', 'php5', 'exe', 'bat', 'sh'];
        $rule = new SecureFileUpload();
        
        foreach ($dangerousExtensions as $ext) {
            $file = UploadedFile::fake()->create("malicious.{$ext}", 100);
            
            $failMessage = null;
            $rule->validate('file', $file, function($message) use (&$failMessage) {
                $failMessage = $message;
            });
            
            $this->assertNotNull($failMessage, "Extension .{$ext} should be rejected");
        }
    }
}