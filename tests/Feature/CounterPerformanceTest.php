<?php

/*
 * Test performa statistik pengunjung (issue #1817).
 *
 * Tabel visitors sudah berisi ratusan ribu baris, sehingga query statistik
 * harus memakai index, dijalankan sesedikit mungkin, dan tidak memakai
 * pengelompokan pada kolom TEXT url.
 */

use App\Http\Middleware\TrackVisitors;
use App\Models\User;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    Cache::flush();
    Visitor::query()->delete();
});

function buatKunjungan(array $attributes = []): Visitor
{
    return Visitor::create(array_merge([
        'ip_address' => hash('sha256', '10.0.0.'.random_int(1, 254)),
        'url' => 'http://localhost/halaman-a',
        'user_agent' => 'test-agent',
        'visited_at' => now(),
        'page_views' => 1,
    ], $attributes));
}

// =============================================================================
// INDEX & SKEMA
// =============================================================================

test('tabel visitors memiliki kolom url_hash', function () {
    expect(Schema::hasColumn('visitors', 'url_hash'))->toBeTrue();
});

test('tabel visitors memiliki index pada visited_at, url_hash, dan kombinasi ip_address-url_hash-visited_at', function () {
    $indexes = collect(Schema::getIndexes('visitors'))->map(fn ($index) => $index['columns']);

    expect($indexes->contains(['visited_at']))->toBeTrue()
        ->and($indexes->contains(['url_hash']))->toBeTrue()
        ->and($indexes->contains(['ip_address', 'url_hash', 'visited_at']))->toBeTrue();
});

// =============================================================================
// PENULISAN url_hash
// =============================================================================

test('url_hash terisi otomatis dari sha256 url saat kunjungan disimpan', function () {
    $kunjungan = buatKunjungan(['url' => 'http://localhost/berita/1?x=2']);

    expect($kunjungan->fresh()->url_hash)->toBe(hash('sha256', 'http://localhost/berita/1?x=2'));
});

test('TrackVisitors menyimpan url_hash untuk request GET', function () {
    $url = 'http://localhost/profil/desa';

    (new TrackVisitors)->handle(Request::create($url, 'GET'), fn () => response('ok'));

    expect(Visitor::latest('id')->first()->url_hash)->toBe(hash('sha256', $url));
});

test('TrackVisitors menaikkan page_views untuk ip dan url yang sama di hari yang sama', function () {
    $url = 'http://localhost/profil/desa';
    $middleware = new TrackVisitors;

    $middleware->handle(Request::create($url, 'GET'), fn () => response('ok'));
    $middleware->handle(Request::create($url, 'GET'), fn () => response('ok'));

    expect(Visitor::count())->toBe(1)
        ->and(Visitor::first()->page_views)->toBe(2);
});

// =============================================================================
// RINGKASAN STATISTIK
// =============================================================================

test('summaryStats mengembalikan enam periode dalam satu query', function () {
    buatKunjungan(['visited_at' => now(), 'page_views' => 2]);
    buatKunjungan(['visited_at' => now()->subDay(), 'page_views' => 3]);

    DB::flushQueryLog();
    DB::enableQueryLog();
    $summary = Visitor::summaryStats();
    $queries = DB::getQueryLog();
    DB::disableQueryLog();

    expect($queries)->toHaveCount(1)
        ->and($summary)->toHaveKeys(['today', 'yesterday', 'thisWeek', 'thisMonth', 'thisYear', 'all'])
        ->and((int) $summary['today']->page_views)->toBe(2)
        ->and((int) $summary['yesterday']->page_views)->toBe(3)
        ->and((int) $summary['all']->page_views)->toBe(5);
});

test('summaryStats konsisten dengan stats per periode', function () {
    buatKunjungan(['visited_at' => now(), 'page_views' => 4]);
    buatKunjungan(['visited_at' => now()->subYears(2), 'page_views' => 6]);

    $summary = Visitor::summaryStats();

    expect((int) $summary['today']->page_views)->toBe((int) Visitor::stats(\App\Enums\VisitorFilterEnum::TODAY)->page_views)
        ->and((int) $summary['thisYear']->page_views)->toBe((int) Visitor::stats(\App\Enums\VisitorFilterEnum::THIS_YEAR)->page_views)
        ->and((int) $summary['all']->page_views)->toBe((int) Visitor::stats(\App\Enums\VisitorFilterEnum::ALL)->page_views);
});

// =============================================================================
// HALAMAN POPULER
// =============================================================================

test('getTopPagesVisited mengelompokkan per url dan menjumlahkan page_views', function () {
    buatKunjungan(['url' => 'http://localhost/a', 'page_views' => 3]);
    buatKunjungan(['url' => 'http://localhost/a', 'page_views' => 2]);
    buatKunjungan(['url' => 'http://localhost/b', 'page_views' => 1]);

    $top = Visitor::getTopPagesVisited();

    expect($top)->toHaveCount(2)
        ->and($top[0]->url)->toBe('http://localhost/a')
        ->and((int) $top[0]->total_views)->toBe(5)
        ->and((int) $top[0]->unique_visitors)->toBe(2);
});

// =============================================================================
// JUMLAH QUERY HALAMAN COUNTER
// =============================================================================

test('halaman statistik pengunjung menjalankan paling banyak 3 query ke tabel visitors', function () {
    $this->actingAs(User::factory()->create());
    buatKunjungan(['visited_at' => now()]);
    buatKunjungan(['visited_at' => now()->subDays(40)]);

    DB::flushQueryLog();
    DB::enableQueryLog();
    $this->get(route('counter.index'))->assertSuccessful();
    $queries = collect(DB::getQueryLog());
    DB::disableQueryLog();

    $queriesVisitors = $queries->filter(fn ($query) => str_contains($query['query'], 'visitors'));

    expect($queriesVisitors->count())->toBeLessThanOrEqual(3);
});
