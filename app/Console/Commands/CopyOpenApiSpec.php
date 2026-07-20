<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CopyOpenApiSpec extends Command
{
    protected $signature = 'scribe:copy-openapi
        {--target= : Target path for the OpenAPI spec (default: openapi/openapi.yaml)}';

    protected $description = 'Copy generated OpenAPI spec from storage to repo root';

    public function handle(): int
    {
        $source = Storage::disk('local')->path('scribe/openapi.yaml');

        if (!file_exists($source)) {
            $this->error('OpenAPI spec not found at ' . $source);
            $this->info('Run `php artisan scribe:generate` first.');
            return self::FAILURE;
        }

        $target = $this->option('target') ?? base_path('openapi/openapi.yaml');
        $targetDir = dirname($target);

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0o755, true);
        }

        copy($source, $target);

        $this->info("OpenAPI spec copied to {$target}");
        return self::SUCCESS;
    }
}
