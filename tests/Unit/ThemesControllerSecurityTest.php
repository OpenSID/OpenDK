<?php

/**
 * Unit tests for ThemesController security methods.
 *
 * Tests:
 *   - scanZipForPhp()   (Phase 2 — ZIP content scanning)
 *   - validateHooksSource() (Phase 3 — token-based hooks validation)
 */

use App\Http\Controllers\BackEnd\ThemesController;
use Tests\TestCase;

beforeEach(function () {
    $this->controller = new ThemesController();
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

    $ref = new ReflectionClass($this->controller);
    $m = $ref->getMethod('scanZipForPhp');

    expect(fn () => $m->invoke($this->controller, $z))
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

    $ref = new ReflectionClass($this->controller);
    $m = $ref->getMethod('scanZipForPhp');
    $m->invoke($this->controller, $z);

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

    $ref = new ReflectionClass($this->controller);
    $m = $ref->getMethod('scanZipForPhp');

    expect(fn () => $m->invoke($this->controller, $z))
        ->toThrow(RuntimeException::class);

    $z->close();
    @unlink($zipPath);
});

// ── validateHooksSource tests ──

test('validateHooksSource rejects system() call', function () {
    $r = invokePrivate('validateHooksSource', ['<?php system("echo x");', 'evil']);
    expect($r['valid'])->toBeFalse();
});

test('validateHooksSource rejects exec() call', function () {
    $r = invokePrivate('validateHooksSource', ['<?php exec("echo x");', 'evil']);
    expect($r['valid'])->toBeFalse();
});

test('validateHooksSource rejects eval() call', function () {
    $r = invokePrivate('validateHooksSource', ['<?php eval("echo 1;");', 'evil']);
    expect($r['valid'])->toBeFalse();
});

test('validateHooksSource rejects file_put_contents() call', function () {
    $r = invokePrivate('validateHooksSource', ['<?php file_put_contents("/tmp/x","d");', 'evil']);
    expect($r['valid'])->toBeFalse();
});

test('validateHooksSource rejects shell_exec() call', function () {
    $r = invokePrivate('validateHooksSource', ['<?php shell_exec("ls");', 'evil']);
    expect($r['valid'])->toBeFalse();
});

test('validateHooksSource allows harmless function declaration', function () {
    $r = invokePrivate('validateHooksSource', ['<?php function my_hook(): string { return "ok"; }', 'clean']);
    expect($r['valid'])->toBeTrue();
});

test('validateHooksSource allows array return', function () {
    $r = invokePrivate('validateHooksSource', ['<?php return ["a" => "b"];', 'clean']);
    expect($r['valid'])->toBeTrue();
});

test('validateHooksSource rejects inline HTML', function () {
    $r = invokePrivate('validateHooksSource', ['<?php $x=1; ?><h1>x</h1><?php return $x;', 'evil']);
    expect($r['valid'])->toBeFalse();
});

// ── Helper ──

function invokePrivate(string $method, array $args): mixed
{
    // Access via the controller stored in $this (Pest test closure binding)
    $controller = test()->controller ?? new ThemesController();
    $ref = new ReflectionClass($controller);
    $m = $ref->getMethod($method);
    return $m->invokeArgs($controller, $args);
}