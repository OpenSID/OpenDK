<?php

namespace App\Http\Controllers\Api\Frontend;

use Illuminate\Routing\Controller;

class BaseController extends Controller
{
    protected $prefix;
    protected function fractal(
        $data,
        null|callable|\League\Fractal\TransformerAbstract $transformer,
        null|string $resourceName = null,
    ): \Spatie\Fractal\Fractal {
        return fractal(
            $data,
            $transformer,
            \League\Fractal\Serializer\JsonApiSerializer::class
        )
            ->withResourceName($resourceName);
    }

    /**
     * Get cache key for Artikel API
     */
    protected function getCacheKey(string $method, array $params = []): string
    {
        $prefix = $this->prefix ?? 'theme:api';
        $key = "{$prefix}:{$method}";
        if (!empty($params)) {
            $key .= ':' . md5(serialize($params));
        }
        return $key;
    }

    /**
     * Get cache duration from config
     */
    protected function getCacheDuration(): int
    {
        return config('theme-api.cache.duration', 3600);
    }
}