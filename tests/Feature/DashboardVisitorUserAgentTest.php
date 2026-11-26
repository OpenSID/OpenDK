<?php

use App\Models\User;
use App\Models\Visitor;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;

uses(DatabaseTransactions::class);

beforeEach(function () {
    // Kosongkan visitors di dalam transaksi test agar hitungan tidak terpengaruh data lama
    Visitor::query()->delete();
});

const UA_CHROME_DESKTOP = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36';
const UA_IPHONE = 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1';

function buatVisitor(string $userAgent, int $n = 1): void
{
    for ($i = 0; $i < $n; $i++) {
        Visitor::create([
            'ip_address' => hash('sha256', $userAgent.$i),
            'url' => 'http://localhost/halaman-'.$i,
            'user_agent' => $userAgent,
            'visited_at' => now(),
            'page_views' => 1,
        ]);
    }
}

test('hitung user agent dikelompokkan per string dengan jumlah yang benar', function () {
    buatVisitor(UA_CHROME_DESKTOP, 3);
    buatVisitor(UA_IPHONE, 2);

    $counts = Visitor::countByUserAgent();

    expect($counts[UA_CHROME_DESKTOP])->toBe(3)
        ->and($counts[UA_IPHONE])->toBe(2)
        ->and($counts)->toHaveCount(2);
});

test('hitung user agent dilakukan di database dengan satu query GROUP BY', function () {
    buatVisitor(UA_CHROME_DESKTOP, 50);
    buatVisitor(UA_IPHONE, 50);

    DB::enableQueryLog();
    Visitor::countByUserAgent();
    $queries = collect(DB::getQueryLog())->pluck('query');
    DB::disableQueryLog();

    expect($queries)->toHaveCount(1)
        ->and(strtolower($queries->first()))->toContain('group by');
});

test('dashboard menghitung browser dari user agent yang dikelompokkan', function () {
    $this->withoutMiddleware();
    $this->actingAs(User::factory()->create());

    buatVisitor(UA_CHROME_DESKTOP, 3);
    buatVisitor(UA_IPHONE, 2);

    $response = $this->get(route('dashboard'));

    $response->assertOk();

    $browserData = collect($response->viewData('browserData'))->keyBy('name');

    expect($browserData['Chrome']['y'])->toBe(3)
        ->and($browserData['Safari']['y'])->toBe(2);
});
