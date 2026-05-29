<?php

/**
 * Unit tests for ThemeHooksValidator security methods.
 *
 * Tests:
 *   - scanZipForPhp()   (Phase 2 — ZIP content scanning)
 *   - validateSource() (Phase 3 — token-based hooks validation)
 */

use App\Services\ThemeHooksValidator;

beforeEach(function () {
    $this->validator = new ThemeHooksValidator();
});

// ── scanZipForPhp tests ──

test('scanZipForPhp rejects ZIP containing .php file', function () {
    $zipPath = sys_get_temp_dir() . '/' . uniqid() . '.zip';
    $z = new ZipArchive();
    $z->open($zipPath, ZipArchive::CREATE);
    $z->addFromString('evil.php', '<?php echo 1;');
    $z->close();

    $z = new ZipArchive();
    $z->open($zipPath);

    expect(fn () => $this->validator->scanZipForPhp($z))
        ->toThrow(RuntimeException::class);

    $z->close();
    @unlink($zipPath);
});

test('scanZipForPhp allows clean ZIP without PHP files', function () {
    $zipPath = sys_get_temp_dir() . '/' . uniqid() . '.zip';
    $z = new ZipArchive();
    $z->open($zipPath, ZipArchive::CREATE);
    $z->addFromString('theme.json', '{}');
    $z->addFromString('style.css', 'body{}');
    $z->close();

    $z = new ZipArchive();
    $z->open($zipPath);

    $this->validator->scanZipForPhp($z);

    expect(true)->toBeTrue(); // no exception = pass

    $z->close();
    @unlink($zipPath);
});

test('scanZipForPhp rejects PHP file in subfolder', function () {
    $zipPath = sys_get_temp_dir() . '/' . uniqid() . '.zip';
    $z = new ZipArchive();
    $z->open($zipPath, ZipArchive::CREATE);
    $z->addFromString('includes/backdoor.php', '<?php system("id");');
    $z->close();

    $z = new ZipArchive();
    $z->open($zipPath);

    expect(fn () => $this->validator->scanZipForPhp($z))
        ->toThrow(RuntimeException::class);

    $z->close();
    @unlink($zipPath);
});

// ── validateHooksSource tests ──

test('validateHooksSource rejects system() call', function () {
    $r = $this->validator->validateSource('<?php system("echo x");', 'evil');
    expect($r['valid'])->toBeFalse();
});

test('validateHooksSource rejects exec() call', function () {
    $r = $this->validator->validateSource('<?php exec("echo x");', 'evil');
    expect($r['valid'])->toBeFalse();
});

test('validateHooksSource rejects eval() call', function () {
    $r = $this->validator->validateSource('<?php eval("echo 1;");', 'evil');
    expect($r['valid'])->toBeFalse();
});

test('validateHooksSource rejects file_put_contents() call', function () {
    $r = $this->validator->validateSource('<?php file_put_contents("/tmp/x","d");', 'evil');
    expect($r['valid'])->toBeFalse();
});

test('validateHooksSource rejects shell_exec() call', function () {
    $r = $this->validator->validateSource('<?php shell_exec("ls");', 'evil');
    expect($r['valid'])->toBeFalse();
});

test('validateHooksSource allows harmless function declaration', function () {
    $r = $this->validator->validateSource('<?php function my_hook(): string { return "ok"; }', 'clean');
    expect($r['valid'])->toBeTrue();
});

test('validateHooksSource allows array return', function () {
    $r = $this->validator->validateSource('<?php return ["a" => "b"];', 'clean');
    expect($r['valid'])->toBeTrue();
});

test('validateHooksSource rejects inline HTML', function () {
    $r = $this->validator->validateSource('<?php $x=1; ?><h1>x</h1><?php return $x;', 'evil');
    expect($r['valid'])->toBeFalse();
});