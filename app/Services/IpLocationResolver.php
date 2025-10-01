<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class IpLocationResolver
{
    private const CACHE_PREFIX = 'ip_location_';
    private const CACHE_TTL = 86400; // 1 day
    private const API_ENDPOINT = 'https://ipwhois.app/json/';

    private const PRIVATE_IP_PATTERNS = [
        '/^127\./',
        '/^10\./',
        '/^192\.168\./',
        '/^172\.(1[6-9]|2[0-9]|3[0-1])\./',
        '/^::1$/',
        '/^fc/',
        '/^fd/'
    ];

    public static function resolve(?string $ip): array
    {
        if (! $ip || self::isPrivateIp($ip)) {
            return [
                'ip_location_available' => false,
                'ip_location_message' => 'IP lokal/internal',
            ];
        }

        $cacheKey = self::CACHE_PREFIX . $ip;

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($ip) {
            try {
                $response = Http::timeout(5)->get(self::API_ENDPOINT . $ip);

                if (! $response->ok()) {
                    return [
                        'ip_location_available' => false,
                        'ip_location_message' => 'Gagal mengambil lokasi IP',
                    ];
                }

                $payload = $response->json();

                if (! $payload || ($payload['success'] ?? true) === false) {
                    return [
                        'ip_location_available' => false,
                        'ip_location_message' => $payload['message'] ?? 'Lokasi IP tidak ditemukan',
                    ];
                }

                return [
                    'ip_location_available' => true,
                    'ip_country' => $payload['country'] ?? null,
                    'ip_country_code' => $payload['country_code'] ?? null,
                    'ip_region' => $payload['region'] ?? null,
                    'ip_city' => $payload['city'] ?? null,
                    'ip_isp' => $payload['isp'] ?? null,
                ];
            } catch (\Throwable $e) {
                report($e);

                return [
                    'ip_location_available' => false,
                    'ip_location_message' => 'Kesalahan saat memproses lokasi IP',
                ];
            }
        });
    }

    private static function isPrivateIp(string $ip): bool
    {
        foreach (self::PRIVATE_IP_PATTERNS as $pattern) {
            if (preg_match($pattern, $ip)) {
                return true;
            }
        }

        return false;
    }
}
