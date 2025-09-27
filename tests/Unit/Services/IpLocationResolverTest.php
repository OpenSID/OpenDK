<?php

namespace Tests\Unit\Services;

use App\Services\IpLocationResolver;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class IpLocationResolverTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config(['cache.default' => 'array']);
        Cache::flush();
    }

    public function test_it_returns_private_ip_message(): void
    {
        $result = IpLocationResolver::resolve('127.0.0.1');

        $this->assertSame([
            'ip_location_available' => false,
            'ip_location_message' => 'IP lokal/internal',
        ], $result);
    }

    public function test_it_fetches_location_data_from_api(): void
    {
        Http::fake([
            'https://ipwhois.app/*' => Http::response([
                'success' => true,
                'country' => 'Indonesia',
                'country_code' => 'ID',
                'region' => 'Jawa Barat',
                'city' => 'Bandung',
                'isp' => 'Example ISP',
            ], 200),
        ]);

        $result = IpLocationResolver::resolve('1.2.3.4');

        Http::assertSent(function ($request) {
            return str_contains($request->url(), '1.2.3.4');
        });

        $this->assertSame([
            'ip_location_available' => true,
            'ip_country' => 'Indonesia',
            'ip_country_code' => 'ID',
            'ip_region' => 'Jawa Barat',
            'ip_city' => 'Bandung',
            'ip_isp' => 'Example ISP',
        ], $result);
    }

    public function test_it_returns_error_message_when_api_fails(): void
    {
        Http::fake([
            'https://ipwhois.app/*' => Http::response([], 500),
        ]);

        $result = IpLocationResolver::resolve('1.2.3.4');

        $this->assertSame([
            'ip_location_available' => false,
            'ip_location_message' => 'Gagal mengambil lokasi IP',
        ], $result);
    }

    public function test_it_returns_error_message_when_payload_invalid(): void
    {
        Http::fake([
            'https://ipwhois.app/*' => Http::response([
                'success' => false,
                'message' => 'IP invalid',
            ], 200),
        ]);

        $result = IpLocationResolver::resolve('1.2.3.4');

        $this->assertSame([
            'ip_location_available' => false,
            'ip_location_message' => 'IP invalid',
        ], $result);
    }

    public function test_it_returns_generic_error_on_exception(): void
    {
        Http::fake([
            'https://ipwhois.app/*' => function () {
                throw new \RuntimeException('Timeout');
            },
        ]);

        $result = IpLocationResolver::resolve('1.2.3.4');

        $this->assertSame([
            'ip_location_available' => false,
            'ip_location_message' => 'Kesalahan saat memproses lokasi IP',
        ], $result);
    }

    public function test_it_uses_cached_value(): void
    {
        Http::fake([
            'https://ipwhois.app/*' => Http::response([
                'success' => true,
                'country' => 'Indonesia',
                'country_code' => 'ID',
                'region' => 'Jawa Tengah',
                'city' => 'Semarang',
                'isp' => 'Example ISP',
            ], 200),
        ]);

        $firstCall = IpLocationResolver::resolve('2.3.4.5');

        Http::preventStrayRequests();

        $cachedCall = IpLocationResolver::resolve('2.3.4.5');

        $this->assertSame($firstCall, $cachedCall);
    }
}
