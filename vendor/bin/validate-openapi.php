<?php

require_once __DIR__ . '/../autoload.php';

use Symfony\Component\Yaml\Yaml;

$specPath = __DIR__ . '/../../openapi/openapi.yaml';

if (!file_exists($specPath)) {
    echo 'Error: openapi/openapi.yaml not found' . PHP_EOL;
    exit(1);
}

try {
    $spec = Yaml::parseFile($specPath);

    if (!isset($spec['openapi'])) {
        echo 'Error: Not a valid OpenAPI spec (missing "openapi" version field)' . PHP_EOL;
        exit(1);
    }

    echo 'OpenAPI spec is valid (' . $spec['openapi'] . ')' . PHP_EOL;
    echo 'Title: ' . ($spec['info']['title'] ?? 'N/A') . PHP_EOL;
    echo 'Version: ' . ($spec['info']['version'] ?? 'N/A') . PHP_EOL;
    echo 'Paths: ' . count($spec['paths'] ?? []) . PHP_EOL;

    foreach ($spec['paths'] ?? [] as $path => $methods) {
        foreach ($methods as $method => $details) {
            if (is_array($details)) {
                echo '  ' . strtoupper($method) . ' ' . $path . ' - ' . ($details['summary'] ?? 'No summary') . PHP_EOL;
            }
        }
    }
} catch (\Exception $e) {
    echo 'Validation failed: ' . $e->getMessage() . PHP_EOL;
    exit(1);
}
