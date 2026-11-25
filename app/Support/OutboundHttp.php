<?php

namespace App\Support;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

/**
 * Satu-satunya tempat untuk membuat klien HTTP keluar.
 * Setiap klien yang dibuat di sini sudah memiliki batas waktu dari config/http.php.
 */
final class OutboundHttp
{
    public static function timeout(): int
    {
        return (int) config('http.timeout', 10);
    }

    public static function connectTimeout(): int
    {
        return (int) config('http.connect_timeout', 5);
    }

    public static function tteTimeout(): int
    {
        return (int) config('http.tte_timeout', 60);
    }

    /**
     * Klien Laravel (Http facade) dengan timeout standar.
     */
    public static function client(): PendingRequest
    {
        return Http::timeout(self::timeout())->connectTimeout(self::connectTimeout());
    }

    /**
     * Opsi Guzzle dengan timeout standar. Opsi yang diberikan pemanggil tetap menang.
     */
    public static function guzzleOptions(array $options = []): array
    {
        return array_merge([
            'timeout' => self::timeout(),
            'connect_timeout' => self::connectTimeout(),
        ], $options);
    }

    /**
     * Opsi Guzzle untuk server TTE, dengan timeout yang lebih panjang.
     */
    public static function tteGuzzleOptions(array $options = []): array
    {
        return array_merge([
            'timeout' => self::tteTimeout(),
            'connect_timeout' => self::connectTimeout(),
        ], $options);
    }
}
