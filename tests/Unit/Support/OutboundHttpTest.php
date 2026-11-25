<?php

use App\Support\OutboundHttp;
use Illuminate\Support\Facades\Http;

it('reads default timeouts from config/http.php', function () {
    expect(OutboundHttp::timeout())->toBe(10)
        ->and(OutboundHttp::connectTimeout())->toBe(5)
        ->and(OutboundHttp::tteTimeout())->toBe(60);
});

it('applies timeout and connect timeout to the pending http request', function () {
    $options = OutboundHttp::client()->getOptions();

    expect($options['timeout'])->toBe(10)
        ->and($options['connect_timeout'])->toBe(5);
});

it('merges timeouts into guzzle options without overriding explicit values', function () {
    $options = OutboundHttp::guzzleOptions([
        'base_uri' => 'https://example.test',
        'timeout' => 3,
    ]);

    expect($options)->toMatchArray([
        'base_uri' => 'https://example.test',
        'timeout' => 3,
        'connect_timeout' => 5,
    ]);
});

it('uses the longer tte timeout for the tte guzzle options', function () {
    $options = OutboundHttp::tteGuzzleOptions(['base_uri' => 'https://tte.test']);

    expect($options)->toMatchArray([
        'base_uri' => 'https://tte.test',
        'timeout' => 60,
        'connect_timeout' => 5,
    ]);
});

it('sends requests through the shared client without failing on non-json errors', function () {
    Http::fake(['*' => Http::response('<html>Bad Gateway</html>', 502)]);

    $response = OutboundHttp::client()->get('https://example.test/ping');

    expect($response->failed())->toBeTrue()
        ->and($response->json('message'))->toBeNull();
});
