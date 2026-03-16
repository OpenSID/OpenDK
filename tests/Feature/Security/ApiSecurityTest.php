<?php

use App\Enums\JenisJabatan;
use App\Models\DataDesa;
use App\Models\DataUmum;
use App\Models\Jabatan;
use App\Models\Profil;
use App\Models\SettingAplikasi;
use Database\Seeders\RoleSpatieSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Stevebauman\Purify\Facades\Purify;
use function Pest\Laravel\getJson;
use function Pest\Laravel\seed;

uses(DatabaseTransactions::class);

beforeEach(function () {
    seed(RoleSpatieSeeder::class);

    $profil = Profil::first();
    if (!$profil) {
        $profil = Profil::create([
            'provinsi_id' => 32,
            'nama_provinsi' => 'Jawa Barat',
            'kabupaten_id' => 3201,
            'nama_kabupaten' => 'Bogor',
            'kecamatan_id' => 320101,
            'nama_kecamatan' => 'Kecamatan Test',
            'alamat' => 'Jl. Test No. 1',
        ]);
    }

    if (!DataUmum::exists()) {
        DB::table('das_data_umum')->insert([
            'profil_id' => $profil->id,
            'bts_wil_utara' => '-',
            'bts_wil_timur' => '-',
            'bts_wil_selatan' => '-',
            'bts_wil_barat' => '-',
            'lat' => 0,
            'lng' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    if (!Jabatan::where('jenis', JenisJabatan::Camat)->exists()) {
        DB::table('ref_jabatan')->insert([
            ['nama' => 'Camat', 'jenis' => JenisJabatan::Camat, 'tupoksi' => '-', 'created_at' => now(), 'updated_at' => now()],
            ['nama' => 'Sekretaris', 'jenis' => JenisJabatan::Sekretaris, 'tupoksi' => '-', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    $requiredSettings = [
        ['key' => 'tte', 'value' => '0'],
        ['key' => 'pemeriksaan_camat', 'value' => '0'],
        ['key' => 'pemeriksaan_sekretaris', 'value' => '0'],
        ['key' => 'judul_aplikasi', 'value' => 'OpenDK Test'],
    ];
    foreach ($requiredSettings as $s) {
        if (!SettingAplikasi::where('key', $s['key'])->exists()) {
            $setting = new SettingAplikasi();
            $setting->key = $s['key'];
            $setting->value = $s['value'];
            $setting->type = 'input';
            $setting->description = $s['key'];
            $setting->kategori = 'aplikasi';
            $setting->option = '[]';
            $setting->save();
        }
    }
});

// ─── XSS Sanitization (Unit Tests for Purify) ─────────────────────────────────

test('Purify strips script tags from input', function () {
    $dirty = '<script>alert("xss")</script>Hello World';
    $clean = Purify::clean($dirty);

    expect($clean)->not->toContain('<script>');
    expect($clean)->toContain('Hello World');
});

test('Purify strips onerror event handlers from input', function () {
    $dirty = '<p>Safe</p><img src=x onerror="alert(1)">More text';
    $clean = Purify::clean($dirty);

    expect($clean)->not->toContain('onerror');
    expect($clean)->toContain('Safe');
});

test('Purify strips javascript: href links from input', function () {
    $dirty = '<a href="javascript:void(0)">Click me</a>';
    $clean = Purify::clean($dirty);

    expect($clean)->not->toContain('javascript:');
});

test('Purify preserves safe HTML content', function () {
    $safe = '<p>Normal <strong>bold</strong> text</p>';
    $clean = Purify::clean($safe);

    expect($clean)->toContain('Normal');
    expect($clean)->toContain('bold');
});

// ─── Security Headers ─────────────────────────────────────────────────────────

test('api returns proper JSON content type header', function () {
    $response = getJson('/api/frontend/v1/artikel');
    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/json');
});

// ─── CORS Headers ─────────────────────────────────────────────────────────────

test('api returns Access-Control-Allow-Origin header on OPTIONS request', function () {
    $response = $this->call('OPTIONS', '/api/frontend/v1/artikel', [], [], [], [
        'HTTP_ORIGIN' => 'http://example.com',
        'HTTP_ACCESS_CONTROL_REQUEST_METHOD' => 'GET',
    ]);

    $response->assertHeader('Access-Control-Allow-Origin', '*');
});

test('api allows any frontend origin via wildcard CORS policy', function () {
    $response = $this->call('OPTIONS', '/api/v1/auth/login', [], [], [], [
        'HTTP_ORIGIN' => 'http://desa.my.id',
        'HTTP_ACCESS_CONTROL_REQUEST_METHOD' => 'POST',
    ]);

    $response->assertHeader('Access-Control-Allow-Origin', '*');
});

// ─── Performance ──────────────────────────────────────────────────────────────

test('public artikel API responds within 1 second', function () {
    $start = microtime(true);
    getJson('/api/frontend/v1/artikel');
    $durationMs = (microtime(true) - $start) * 1000;

    expect($durationMs)->toBeLessThan(1000);
});

test('public desa API responds within 1 second', function () {
    $start = microtime(true);
    getJson('/api/frontend/v1/desa');
    $durationMs = (microtime(true) - $start) * 1000;

    expect($durationMs)->toBeLessThan(1000);
});

test('unauthenticated requests to internal API are rejected quickly', function () {
    $start = microtime(true);
    getJson('/api/v1/penduduk');
    $durationMs = (microtime(true) - $start) * 1000;

    expect($durationMs)->toBeLessThan(500);
});
