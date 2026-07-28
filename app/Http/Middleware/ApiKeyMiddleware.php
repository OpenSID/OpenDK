<?php

namespace App\Http\Middleware;

use App\Services\KeyService;
use Closure;
use Illuminate\Http\Request;

class ApiKeyMiddleware
{
    public function __construct(
        private readonly KeyService $keyService,
    ) {
    }

    public function handle(Request $request, Closure $next, ?string $scope = null)
    {
        $rawKey = $request->bearerToken();

        if (empty($rawKey)) {
            return response()->json([
                'message' => 'Missing API key',
            ], 401);
        }

        $result = $this->keyService->validate($rawKey, $scope);

        if (!$result['valid']) {
            $statusCode = match ($result['status'] ?? 'invalid') {
                'revoked', 'disabled', 'insufficient_scope' => 403,
                'expired' => 403,
                default => 401,
            };

            return response()->json([
                'message' => $result['message'] ?? 'Invalid API key',
                'status' => $result['status'] ?? 'invalid',
            ], $statusCode);
        }

        $request->merge(['api_key' => $result['api_key']]);

        return $next($request);
    }
}
