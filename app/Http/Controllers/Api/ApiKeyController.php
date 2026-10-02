<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreApiKeyRequest;
use App\Models\ApiKey;
use App\Services\KeyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiKeyController extends Controller
{
    public function __construct(
        private readonly KeyService $keyService,
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $apiKeys = ApiKey::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'data' => $apiKeys,
        ]);
    }

    public function store(StoreApiKeyRequest $request): JsonResponse
    {
        $result = $this->keyService->create($request->validated(), $request->user()->id);

        return response()->json([
            'data' => [
                'api_key' => $result['api_key'],
                'raw_key' => $result['raw_key'],
            ],
            'message' => 'API key created successfully',
        ], 201);
    }

    public function show(Request $request, ApiKey $apiKey): JsonResponse
    {
        if ($apiKey->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return response()->json([
            'data' => $apiKey,
        ]);
    }

    public function revoke(Request $request, ApiKey $apiKey): JsonResponse
    {
        if ($apiKey->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $this->keyService->revoke($apiKey->id, $request->user()->id);

        return response()->json([
            'message' => 'API key revoked successfully',
        ]);
    }

    public function destroy(Request $request, ApiKey $apiKey): JsonResponse
    {
        if ($apiKey->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $this->keyService->revoke($apiKey->id, $request->user()->id);

        return response()->json([
            'message' => 'API key deleted successfully',
        ]);
    }
}
