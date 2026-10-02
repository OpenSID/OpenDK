<?php

use Symfony\Component\Yaml\Yaml;
use JsonSchema\Validator as JsonSchemaValidator;
use JsonSchema\Constraints\Constraint;

function getRequestSchema(array $spec, string $path, string $method): ?array
{
    $method = strtolower($method);
    if (!isset($spec['paths'][$path][$method])) {
        return null;
    }
    $operation = $spec['paths'][$path][$method];
    if (!isset($operation['requestBody']['content']['application/json']['schema'])) {
        return null;
    }
    return $operation['requestBody']['content']['application/json']['schema'];
}

dataset('contract_examples', function () {
    $specPath = __DIR__ . '/../../openapi/openapi.yaml';
    expect(file_exists($specPath))->toBeTrue('OpenAPI spec not found at ' . $specPath);

    $spec = Yaml::parseFile($specPath);

    $examples = [];
    $base = __DIR__ . '/examples';

    $files = [
        'auth/login-success.json' => [
            'label' => 'Auth login success',
            'path' => '/api/v1/auth/login',
            'method' => 'post',
            'should_succeed' => true,
        ],
        'auth/login-missing-password.json' => [
            'label' => 'Auth login missing password',
            'path' => '/api/v1/auth/login',
            'method' => 'post',
            'should_succeed' => false,
        ],
        'auth/login-missing-email.json' => [
            'label' => 'Auth login missing email',
            'path' => '/api/v1/auth/login',
            'method' => 'post',
            'should_succeed' => false,
        ],
        'penduduk/hapus-success.json' => [
            'label' => 'Penduduk hapus success',
            'path' => '/api/v1/penduduk',
            'method' => 'post',
            'should_succeed' => true,
        ],
        'penduduk/hapus-missing-field.json' => [
            'label' => 'Penduduk hapus missing desa_id',
            'path' => '/api/v1/penduduk',
            'method' => 'post',
            'should_succeed' => false,
        ],
        'laporan-apbdes/sync-success.json' => [
            'label' => 'Laporan APBDes sync success',
            'path' => '/api/v1/laporan-apbdes',
            'method' => 'post',
            'should_succeed' => true,
        ],
        'laporan-penduduk/sync-success.json' => [
            'label' => 'Laporan Penduduk sync success',
            'path' => '/api/v1/laporan-penduduk',
            'method' => 'post',
            'should_succeed' => true,
        ],
        'pesan/kirim-success.json' => [
            'label' => 'Pesan kirim success',
            'path' => '/api/v1/pesan',
            'method' => 'post',
            'should_succeed' => true,
        ],
        'pesan/kirim-missing-pesan.json' => [
            'label' => 'Pesan kirim missing pesan',
            'path' => '/api/v1/pesan',
            'method' => 'post',
            'should_succeed' => false,
        ],
        'pesan/getpesan-success.json' => [
            'label' => 'Pesan getpesan success',
            'path' => '/api/v1/pesan/getpesan',
            'method' => 'post',
            'should_succeed' => true,
        ],
        'identitas-desa/sync-success.json' => [
            'label' => 'Identitas desa sync success',
            'path' => '/api/v1/identitas-desa',
            'method' => 'post',
            'should_succeed' => true,
        ],
    ];

    foreach ($files as $file => $meta) {
        $payloadPath = "$base/$file";
        if (!file_exists($payloadPath)) {
            throw new RuntimeException("Example payload not found: $payloadPath");
        }

        $schema = getRequestSchema($spec, $meta['path'], $meta['method']);

        $examples[] = [
            $meta['label'],
            $meta['path'],
            strtoupper($meta['method']),
            json_decode(file_get_contents($payloadPath)),
            $schema,
            $meta['should_succeed'],
        ];
    }

    return $examples;
});

test('request payload matches OpenAPI spec: {$_data}', function (
    string $label,
    string $path,
    string $method,
    object $payload,
    ?array $schema,
    bool $shouldSucceed,
) {
    expect($schema)->not->toBeNull(
        "No JSON request body schema found for $method $path"
    );

    $validator = new JsonSchemaValidator();
    $validator->validate($payload, $schema, Constraint::CHECK_MODE_NORMAL);

    if ($shouldSucceed) {
        expect($validator->isValid())->toBeTrue(
            sprintf(
                "Payload for %s %s should be valid but got errors:\n%s",
                $method,
                $path,
                json_encode($validator->getErrors(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            )
        );
    } else {
        expect($validator->isValid())->toBeFalse(
            "Payload for $method $path should be INVALID but passed validation"
        );
    }
})->group('contract')->with('contract_examples');
