<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class ThemeHooksValidator
{
    /**
     * Dangerous PHP functions that are blocked in hooks.php.
     */
    private const DANGEROUS_FUNCTIONS = [
        'system', 'exec', 'passthru', 'shell_exec', 'popen', 'proc_open',
        'pcntl_exec', 'assert', 'create_function', 'eval',
        // BUG FIX: call_user_func / call_user_func_array bypass (Severity: Critical)
        'call_user_func', 'call_user_func_array',
        'file_put_contents', 'file_get_contents', 'fopen', 'fwrite', 'fputs',
        'unlink', 'mkdir', 'rmdir', 'rename', 'copy', 'chmod', 'chown',
        'symlink', 'link', 'tmpfile', 'move_uploaded_file',
        'extract', 'parse_str', 'putenv',
        'ini_set', 'ini_alter',
        'header', 'setcookie',
        'define', 'defined',
        'base64_decode', 'urldecode',
    ];

    /**
     * Dangerous PHP file extensions that are rejected inside ZIP archives.
     */
    private const DANGEROUS_EXTENSIONS = [
        'php', 'phtml', 'php3', 'php4', 'php5', 'pht', 'inc', 'phar',
    ];

    /**
     * Scan ZIP archive for files with dangerous PHP extensions.
     * Must be called BEFORE extractTo().
     *
     * @throws \RuntimeException if dangerous files are detected.
     */
    public function scanZipForPhp(\ZipArchive $zip): void
    {
        $dangerousFiles = [];

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            if (in_array($ext, self::DANGEROUS_EXTENSIONS, true)) {
                $dangerousFiles[] = $filename;
            }
        }

        if (! empty($dangerousFiles)) {
            $list = implode(', ', $dangerousFiles);
            Log::critical("Dangerous files detected in theme ZIP: {$list}", [
                'action' => 'theme_zip_blocked',
                'files' => $dangerousFiles,
                'user_id' => auth()->id(),
            ]);
            throw new \RuntimeException("Arsip mengandung file PHP berbahaya: {$list}");
        }
    }

    /**
     * Validate that no ZIP entry contains path traversal sequences.
     * Must be called BEFORE extractTo().
     *
     * @throws \RuntimeException if any entry contains ".." or starts with "/".
     */
    public function validateZipEntryPaths(\ZipArchive $zip): void
    {
        $traversalEntries = [];

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);

            // Reject entries that start with '/' (absolute path)
            // or contain '..' (directory traversal)
            if (str_starts_with($filename, '/') || str_contains($filename, '..')) {
                $traversalEntries[] = $filename;
            }
        }

        if (! empty($traversalEntries)) {
            $list = implode(', ', $traversalEntries);
            Log::critical("Path traversal detected in theme ZIP: {$list}", [
                'action'  => 'theme_zip_traversal_blocked',
                'entries' => $traversalEntries,
                'user_id' => auth()->id(),
            ]);
            throw new \RuntimeException("Arsip mengandung entri path traversal berbahaya: {$list}");
        }
    }

    /**
     * Load theme hooks/filters with token-based security validation.
     */
    public function loadHooks(string $themePath): void
    {
        $hooksFile = base_path("themes/{$themePath}/hooks.php");

        if (! file_exists($hooksFile)) {
            return;
        }

        // === PHASE 3: Token-based validation ===
        $source = file_get_contents($hooksFile);
        if ($source === false || trim($source) === '') {
            Log::warning("Theme hooks file empty or unreadable: {$themePath}");
            return;
        }

        $validationResult = $this->validateSource($source, $themePath);
        if (! $validationResult['valid']) {
            Log::critical("Theme hooks REJECTED: {$themePath} — {$validationResult['reason']}", [
                'action' => 'theme_hooks_rejected',
                'theme' => $themePath,
                'reason' => $validationResult['reason'],
                'details' => $validationResult['details'] ?? [],
            ]);
            throw new \RuntimeException("hooks.php mengandung kode berbahaya: {$validationResult['reason']}");
        }

        // Safe to include after token validation
        try {
            include_once $hooksFile;
            Log::info("Theme hooks loaded (validated): {$themePath}", [
                'action' => 'theme_hooks_loaded',
                'theme' => $themePath,
            ]);
        } catch (\Exception $e) {
            Log::error("Failed to load theme hooks: {$e->getMessage()}", [
                'action' => 'theme_hooks_error',
                'theme' => $themePath,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Validate hooks.php source code using token_get_all().
     *
     * Returns ['valid' => bool, 'reason' => string, 'details' => array]
     */
    public function validateSource(string $source, string $themeName): array
    {
        $tokens = @token_get_all($source);
        if (! is_array($tokens)) {
            return ['valid' => false, 'reason' => 'hooks.php cannot be parsed', 'details' => []];
        }

        $count = count($tokens);
        $dangerousCalls = [];
        $hasInlineHtml = false;
        $hasBadCharacter = false;

        for ($i = 0; $i < $count; $i++) {
            if (! is_array($tokens[$i])) {
                continue;
            }

            // Detect syntax errors via T_BAD_CHARACTER
            if ($tokens[$i][0] === T_BAD_CHARACTER) {
                $hasBadCharacter = true;
            }

            // Reject inline HTML — hooks.php should be pure PHP
            if ($tokens[$i][0] === T_INLINE_HTML) {
                $content = trim((string) $tokens[$i][1]);
                if ($content !== '') {
                    $hasInlineHtml = true;
                }
            }

            // Detect eval() — T_EVAL is a language construct token, not T_STRING
            if ($tokens[$i][0] === T_EVAL) {
                $dangerousCalls[] = [
                    'function' => 'eval',
                    'line' => $tokens[$i][2],
                ];
                continue;
            }

            // Look for function calls: T_STRING followed by '('
            if ($tokens[$i][0] === T_STRING) {
                $funcName = strtolower((string) $tokens[$i][1]);

                // Check if the next non-whitespace token is '('
                $nextIdx = $i + 1;
                while ($nextIdx < $count && is_array($tokens[$nextIdx]) && $tokens[$nextIdx][0] === T_WHITESPACE) {
                    $nextIdx++;
                }

                if ($nextIdx < $count && ! is_array($tokens[$nextIdx]) && $tokens[$nextIdx] === '(') {
                    // Skip if this T_STRING is part of a function/method definition
                    $prevIdx = $i - 1;
                    while ($prevIdx >= 0 && is_array($tokens[$prevIdx]) && $tokens[$prevIdx][0] === T_WHITESPACE) {
                        $prevIdx--;
                    }
                    $isDefinition = ($prevIdx >= 0 && is_array($tokens[$prevIdx]) && $tokens[$prevIdx][0] === T_FUNCTION);

                    if (! $isDefinition && in_array($funcName, self::DANGEROUS_FUNCTIONS, true)) {
                        $dangerousCalls[] = [
                            'function' => $funcName,
                            'line' => $tokens[$i][2],
                        ];
                    }
                }
            }

            // BUG FIX (High): Detect variable function calls, e.g. $f('id') or $obj->method().
            if ($tokens[$i][0] === T_VARIABLE) {
                $nextIdx = $i + 1;
                while ($nextIdx < $count && is_array($tokens[$nextIdx]) && $tokens[$nextIdx][0] === T_WHITESPACE) {
                    $nextIdx++;
                }

                if ($nextIdx < $count && ! is_array($tokens[$nextIdx]) && $tokens[$nextIdx] === '(') {
                    $dangerousCalls[] = [
                        'function' => 'variable_function_call:' . $tokens[$i][1],
                        'line' => $tokens[$i][2],
                    ];
                }
            }
        }

        if ($hasBadCharacter) {
            return [
                'valid' => false,
                'reason' => 'hooks.php contains syntax errors',
                'details' => [],
            ];
        }

        if ($hasInlineHtml) {
            return [
                'valid' => false,
                'reason' => 'hooks.php must contain only PHP code, no HTML',
                'details' => [],
            ];
        }

        if (! empty($dangerousCalls)) {
            return [
                'valid' => false,
                'reason' => 'Dangerous function calls detected: ' . implode(', ', array_column($dangerousCalls, 'function')),
                'details' => $dangerousCalls,
            ];
        }

        return ['valid' => true, 'reason' => '', 'details' => []];
    }
}
