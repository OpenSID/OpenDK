<?php

use App\Http\Middleware\SecurityHeaders;
use App\Models\Potensi;
use App\Models\Prosedur;
use App\Models\Regulasi;
use App\Traits\HandlesFileUpload;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

// Dummy class to test HandlesFileUpload trait
class FileUploadSecurityTestHelper
{
    use HandlesFileUpload;

    public function testResolvePath(?string $path): ?string
    {
        return $this->resolveSecureFilePath($path);
    }
}

describe('File Preview Security & Detection Tests', function () {

    test('is_pdf helper correctly identifies PDF paths and MIME types', function () {
        expect(is_pdf('doc.pdf'))->toBeTrue();
        expect(is_pdf('DOC.PDF'))->toBeTrue();
        expect(is_pdf('storage/files/prosedur.pdf'))->toBeTrue();
        expect(is_pdf(null, 'application/pdf'))->toBeTrue();
        expect(is_pdf(null, 'pdf'))->toBeTrue();
        expect(is_pdf('doc.png', 'application/pdf'))->toBeTrue();
        expect(is_pdf('photo.png', 'image/png'))->toBeFalse();
        expect(is_pdf('photo.jpg'))->toBeFalse();
        expect(is_pdf(null, null))->toBeFalse();
    });

    test('models have functional is_pdf attribute', function () {
        $prosedurPdf = new Prosedur(['file_prosedur' => 'storage/doc.pdf', 'mime_type' => 'application/pdf']);
        expect($prosedurPdf->is_pdf)->toBeTrue();

        $prosedurImg = new Prosedur(['file_prosedur' => 'storage/pic.jpg', 'mime_type' => 'image/jpeg']);
        expect($prosedurImg->is_pdf)->toBeFalse();

        $regulasiPdf = new Regulasi(['file_regulasi' => 'storage/sk.pdf', 'mime_type' => 'application/pdf']);
        expect($regulasiPdf->is_pdf)->toBeTrue();

        $regulasiImg = new Regulasi(['file_regulasi' => 'storage/banner.png', 'mime_type' => 'image/png']);
        expect($regulasiImg->is_pdf)->toBeFalse();

        $potensiPdf = new Potensi(['file_gambar' => 'storage/wisata.pdf', 'mime_type' => 'application/pdf']);
        expect($potensiPdf->is_pdf)->toBeTrue();

        $potensiImg = new Potensi(['file_gambar' => 'storage/wisata.png', 'mime_type' => 'image/png']);
        expect($potensiImg->is_pdf)->toBeFalse();
    });

    test('HandlesFileUpload rejects path traversal and files outside allowed directories', function () {
        $helper = new FileUploadSecurityTestHelper();

        // Null and empty paths
        expect($helper->testResolvePath(null))->toBeNull();
        expect($helper->testResolvePath(''))->toBeNull();

        // Path traversal attempts
        expect($helper->testResolvePath('../../../Windows/win.ini'))->toBeNull();
        expect($helper->testResolvePath('..\\..\\..\\Windows\\win.ini'))->toBeNull();
        expect($helper->testResolvePath('storage/../../../../etc/passwd'))->toBeNull();
        expect($helper->testResolvePath('/etc/passwd'))->toBeNull();

        // Safe resolution for real file inside public_path
        $tempFile = public_path('test_preview_security_probe.txt');
        file_put_contents($tempFile, 'safe content');

        try {
            $resolved = $helper->testResolvePath('test_preview_security_probe.txt');
            expect($resolved)->not->toBeNull();
            expect(realpath($resolved))->toBe(realpath($tempFile));
        } finally {
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }
        }
    });

    test('SecurityHeaders middleware enforces secure CSP in production', function () {
        app()['env'] = 'production';
        try {
            $middleware = new SecurityHeaders();
            $request = Request::create('/', 'GET');
            $response = $middleware->handle($request, function () {
                return new Response('<html><body>Hello</body></html>');
            });

            $csp = $response->headers->get('Content-Security-Policy');
            expect($csp)->not->toBeNull();

            // Verify CSP directives for secure PDF preview
            expect($csp)->toContain("object-src 'self' blob:");
            expect($csp)->toContain("frame-src 'self' blob: data:");
            expect($csp)->toContain("img-src 'self' * data: blob:");
            expect($csp)->toContain("frame-ancestors 'self'");
            expect($csp)->not->toContain('report-uri ;');
        } finally {
            app()['env'] = 'testing';
        }
    });

});
