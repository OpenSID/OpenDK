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

// ── Backtick execution operator tests (Issue #57) ──

test('validateHooksSource rejects simple backtick execution', function () {
    $r = $this->validator->validateSource('<?php `id`;', 'evil');
    expect($r['valid'])->toBeFalse()
        ->and($r['reason'])->toContain('Backtick execution operator');
});

test('validateHooksSource rejects live PoC backtick execution', function () {
    $r = $this->validator->validateSource('<?php `id > themes/default/rce_proof.txt 2>&1`;', 'evil');
    expect($r['valid'])->toBeFalse()
        ->and($r['reason'])->toContain('Backtick execution operator');
});

test('validateHooksSource rejects backtick assigned to variable', function () {
    $r = $this->validator->validateSource('<?php $output = `whoami`;', 'evil');
    expect($r['valid'])->toBeFalse()
        ->and($r['reason'])->toContain('Backtick execution operator');
});

// ── Language constructs tests (include, require) ──

test('validateHooksSource rejects include construct', function () {
    $r = $this->validator->validateSource('<?php include "payload.php";', 'evil');
    expect($r['valid'])->toBeFalse();
});

test('validateHooksSource rejects include_once construct', function () {
    $r = $this->validator->validateSource('<?php include_once "payload.php";', 'evil');
    expect($r['valid'])->toBeFalse();
});

test('validateHooksSource rejects require construct', function () {
    $r = $this->validator->validateSource('<?php require "payload.php";', 'evil');
    expect($r['valid'])->toBeFalse();
});

test('validateHooksSource rejects require_once construct', function () {
    $r = $this->validator->validateSource('<?php require_once "payload.php";', 'evil');
    expect($r['valid'])->toBeFalse();
});

// ── Dynamic invocation tests ──

test('validateHooksSource rejects dynamic class instantiation', function () {
    $r = $this->validator->validateSource('<?php $class = "Evil"; new $class();', 'evil');
    expect($r['valid'])->toBeFalse();
});

test('validateHooksSource rejects dynamic method access', function () {
    $r = $this->validator->validateSource('<?php $obj->$method();', 'evil');
    expect($r['valid'])->toBeFalse();
});

test('validateHooksSource rejects dynamic static call', function () {
    $r = $this->validator->validateSource('<?php ClassName::$method();', 'evil');
    expect($r['valid'])->toBeFalse();
});

test('validateHooksSource rejects variable variables', function () {
    $r = $this->validator->validateSource('<?php $$dynamicVar = "val";', 'evil');
    expect($r['valid'])->toBeFalse();
});

test('validateHooksSource rejects variable function call with comments and whitespace', function () {
    $r = $this->validator->validateSource('<?php $f = "system"; $f /* comment */ ("id");', 'evil');
    expect($r['valid'])->toBeFalse();
});

// ── Expanded dangerous functions tests ──

test('validateHooksSource rejects readfile() call', function () {
    $r = $this->validator->validateSource('<?php readfile("/etc/passwd");', 'evil');
    expect($r['valid'])->toBeFalse();
});

test('validateHooksSource rejects forward_static_call() call', function () {
    $r = $this->validator->validateSource('<?php forward_static_call("system", "id");', 'evil');
    expect($r['valid'])->toBeFalse();
});

test('validateHooksSource rejects register_shutdown_function() call', function () {
    $r = $this->validator->validateSource('<?php register_shutdown_function("system", "id");', 'evil');
    expect($r['valid'])->toBeFalse();
});

test('validateHooksSource rejects unserialize() call', function () {
    $r = $this->validator->validateSource('<?php unserialize($data);', 'evil');
    expect($r['valid'])->toBeFalse();
});

test('validateHooksSource rejects scandir() call', function () {
    $r = $this->validator->validateSource('<?php scandir(".");', 'evil');
    expect($r['valid'])->toBeFalse();
});

test('validateHooksSource rejects glob() call', function () {
    $r = $this->validator->validateSource('<?php glob("*.php");', 'evil');
    expect($r['valid'])->toBeFalse();
});

// ── ZIP Blade template scanning tests ──

test('scanZipForPhp allows valid blade templates inside resources/views/', function () {
    $zipPath = sys_get_temp_dir() . '/' . uniqid() . '.zip';
    $z = new ZipArchive();
    $z->open($zipPath, ZipArchive::CREATE);
    $z->addFromString('theme.json', '{}');
    $z->addFromString('resources/views/layouts/app.blade.php', '<div>{{ $page_title }}</div>');
    $z->close();

    $z = new ZipArchive();
    $z->open($zipPath);

    $this->validator->scanZipForPhp($z);

    expect(true)->toBeTrue();

    $z->close();
    @unlink($zipPath);
});

test('scanZipForPhp rejects blade template placed outside resources/views/', function () {
    $zipPath = sys_get_temp_dir() . '/' . uniqid() . '.zip';
    $z = new ZipArchive();
    $z->open($zipPath, ZipArchive::CREATE);
    $z->addFromString('evil.blade.php', '<h1>Bad location</h1>');
    $z->close();

    $z = new ZipArchive();
    $z->open($zipPath);

    expect(fn () => $this->validator->scanZipForPhp($z))
        ->toThrow(RuntimeException::class);

    $z->close();
    @unlink($zipPath);
});

test('scanZipForPhp allows blade template with JavaScript ES6 template literals', function () {
    $zipPath = sys_get_temp_dir() . '/' . uniqid() . '.zip';
    $z = new ZipArchive();
    $z->open($zipPath, ZipArchive::CREATE);
    $z->addFromString('theme.json', '{}');
    $z->addFromString('resources/views/widgets/custom.blade.php', '<script>var str = `hello ${name}`;</script>');
    $z->close();

    $z = new ZipArchive();
    $z->open($zipPath);

    $this->validator->scanZipForPhp($z);

    expect(true)->toBeTrue();

    $z->close();
    @unlink($zipPath);
});

test('scanZipForPhp rejects blade template containing backtick operator in PHP/Blade directive', function () {
    $zipPath = sys_get_temp_dir() . '/' . uniqid() . '.zip';
    $z = new ZipArchive();
    $z->open($zipPath, ZipArchive::CREATE);
    $z->addFromString('resources/views/pages/evil.blade.php', '<div>{{ `id` }}</div>');
    $z->close();

    $z = new ZipArchive();
    $z->open($zipPath);

    expect(fn () => $this->validator->scanZipForPhp($z))
        ->toThrow(RuntimeException::class);

    $z->close();
    @unlink($zipPath);
});
