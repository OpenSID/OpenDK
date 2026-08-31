<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class ThemeHooksValidator
{
    /**
     * Dangerous PHP functions that are blocked in hooks.php.
     */
    private const DANGEROUS_FUNCTIONS = [
        // OS command & process execution
        'system', 'exec', 'passthru', 'shell_exec', 'popen', 'proc_open',
        'pcntl_exec', 'pcntl_fork', 'pcntl_alarm', 'pcntl_signal', 'pcntl_wait',
        'pcntl_waitpid', 'pcntl_wexitstatus', 'dl', 'assert', 'create_function', 'eval',
        // Dynamic callback & indirect invocation
        'call_user_func', 'call_user_func_array',
        'forward_static_call', 'forward_static_call_array',
        'register_shutdown_function', 'register_tick_function',
        'preg_replace_callback', 'preg_replace_callback_array', 'mb_ereg_replace_callback',
        'array_map', 'array_filter', 'array_reduce', 'array_walk', 'array_walk_recursive',
        'unserialize',
        // File system & I/O
        'file_put_contents', 'file_get_contents', 'fopen', 'fwrite', 'fputs',
        'unlink', 'mkdir', 'rmdir', 'rename', 'copy', 'chmod', 'chown',
        'symlink', 'link', 'tmpfile', 'move_uploaded_file', 'touch',
        'readfile', 'file', 'fpassthru', 'highlight_file', 'show_source',
        'fileperms', 'fileowner', 'filegroup', 'chgrp', 'lchown', 'lchgrp',
        'glob', 'scandir', 'opendir', 'readdir', 'dir',
        // Environment, configuration & variable manipulation
        'extract', 'parse_str', 'putenv',
        'ini_set', 'ini_alter',
        'header', 'setcookie',
        'define', 'defined',
        // Encoding / Obfuscation helpers
        'base64_decode', 'urldecode', 'hex2bin',
        'gzinflate', 'gzuncompress', 'gzdecode', 'str_rot13', 'convert_uudecode',
    ];

    /**
     * Dangerous PHP language construct tokens that are blocked in hooks.php.
     */
    private const DANGEROUS_LANGUAGE_TOKENS = [
        T_EVAL,
        T_INCLUDE,
        T_INCLUDE_ONCE,
        T_REQUIRE,
        T_REQUIRE_ONCE,
        T_HALT_COMPILER,
    ];

    /**
     * Dangerous PHP file extensions that are rejected inside ZIP archives.
     */
    private const DANGEROUS_EXTENSIONS = [
        'php', 'phtml', 'php3', 'php4', 'php5', 'pht', 'inc', 'phar',
    ];

    /**
     * Scan ZIP archive for files with dangerous PHP extensions and malicious blade templates.
     * Must be called BEFORE extractTo().
     *
     * @throws \RuntimeException if dangerous files are detected.
     */
    public function scanZipForPhp(\ZipArchive $zip): void
    {
        $dangerousFiles = [];

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $filename = $zip->getNameIndex($i);

            // Skip directories
            if (str_ends_with($filename, '/')) {
                continue;
            }

            // Validate Blade template files
            if (str_ends_with($filename, '.blade.php')) {
                // Blade templates must be inside resources/views/
                if (! str_contains($filename, 'resources/views/')) {
                    $dangerousFiles[] = "{$filename} (Blade template must reside in resources/views/)";
                    continue;
                }

                // Scan Blade content for backtick execution operators
                $bladeContent = $zip->getFromIndex($i);
                if ($bladeContent !== false && str_contains($bladeContent, '`')) {
                    $dangerousFiles[] = "{$filename} (Backtick execution operator detected in blade template)";
                    continue;
                }

                continue;
            }

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
     * Validate hooks.php source code using multi-layer token and string analysis.
     *
     * Returns ['valid' => bool, 'reason' => string, 'details' => array]
     */
    public function validateSource(string $source, string $themeName): array
    {
        // === LAYER 1: Raw character scan ===
        // Reject backtick execution operators immediately
        if (str_contains($source, '`')) {
            return [
                'valid' => false,
                'reason' => 'Backtick execution operator is not allowed',
                'details' => [
                    [
                        'function' => 'backtick_execution',
                        'line' => 1,
                    ],
                ],
            ];
        }

        // === LAYER 2: Token-based AST analysis ===
        $tokens = @token_get_all($source);
        if (! is_array($tokens)) {
            return ['valid' => false, 'reason' => 'hooks.php cannot be parsed', 'details' => []];
        }

        $count = count($tokens);
        $dangerousCalls = [];
        $hasInlineHtml = false;
        $hasBadCharacter = false;

        for ($i = 0; $i < $count; $i++) {
            $token = $tokens[$i];

            // Handle single-character literal tokens (e.g. '`', '$', '(', etc.)
            if (! is_array($token)) {
                if ($token === '`') {
                    $dangerousCalls[] = [
                        'function' => 'backtick_execution',
                        'line' => 1,
                    ];
                } elseif ($token === '$') {
                    // Check for variable variables like $$var
                    $nextIdx = $this->getNextMeaningfulTokenIndex($tokens, $i, $count);
                    if ($nextIdx < $count && is_array($tokens[$nextIdx]) && $tokens[$nextIdx][0] === T_VARIABLE) {
                        $dangerousCalls[] = [
                            'function' => 'variable_variable:' . $tokens[$nextIdx][1],
                            'line' => $tokens[$nextIdx][2],
                        ];
                    }
                }
                continue;
            }

            $tokenId   = $token[0];
            $tokenText = $token[1];
            $tokenLine = $token[2];

            // Double check for backtick inside any token text
            if (str_contains($tokenText, '`')) {
                $dangerousCalls[] = [
                    'function' => 'backtick_execution',
                    'line' => $tokenLine,
                ];
            }

            // Detect syntax errors via T_BAD_CHARACTER
            if ($tokenId === T_BAD_CHARACTER) {
                $hasBadCharacter = true;
            }

            // Reject inline HTML — hooks.php should be pure PHP
            if ($tokenId === T_INLINE_HTML) {
                $content = trim((string) $tokenText);
                if ($content !== '') {
                    $hasInlineHtml = true;
                }
            }

            // Detect dangerous language constructs (eval, include, require, etc.)
            if (in_array($tokenId, self::DANGEROUS_LANGUAGE_TOKENS, true)) {
                $dangerousCalls[] = [
                    'function' => token_name($tokenId),
                    'line' => $tokenLine,
                ];
                continue;
            }

            // Detect dynamic class instantiation: new $var()
            if ($tokenId === T_NEW) {
                $nextIdx = $this->getNextMeaningfulTokenIndex($tokens, $i, $count);
                if ($nextIdx < $count && is_array($tokens[$nextIdx]) && $tokens[$nextIdx][0] === T_VARIABLE) {
                    $dangerousCalls[] = [
                        'function' => 'dynamic_instantiation:' . $tokens[$nextIdx][1],
                        'line' => $tokenLine,
                    ];
                }
                continue;
            }

            // Detect dynamic method/property calls: $obj->$method or $obj->{$method}
            $isNullsafe = defined('T_NULLSAFE_OBJECT_OPERATOR') && $tokenId === T_NULLSAFE_OBJECT_OPERATOR;
            if ($tokenId === T_OBJECT_OPERATOR || $isNullsafe) {
                $nextIdx = $this->getNextMeaningfulTokenIndex($tokens, $i, $count);
                if ($nextIdx < $count) {
                    if (is_array($tokens[$nextIdx]) && $tokens[$nextIdx][0] === T_VARIABLE) {
                        $dangerousCalls[] = [
                            'function' => 'dynamic_member_access:' . $tokens[$nextIdx][1],
                            'line' => $tokenLine,
                        ];
                    } elseif (! is_array($tokens[$nextIdx]) && $tokens[$nextIdx] === '{') {
                        $dangerousCalls[] = [
                            'function' => 'dynamic_member_expression',
                            'line' => $tokenLine,
                        ];
                    }
                }
                continue;
            }

            // Detect dynamic static calls: Class::$method() or $class::$method()
            if ($tokenId === T_PAAMAYIM_NEKUDOTAYIM) {
                $nextIdx = $this->getNextMeaningfulTokenIndex($tokens, $i, $count);
                if ($nextIdx < $count) {
                    if (is_array($tokens[$nextIdx]) && $tokens[$nextIdx][0] === T_VARIABLE) {
                        $dangerousCalls[] = [
                            'function' => 'dynamic_static_call:' . $tokens[$nextIdx][1],
                            'line' => $tokenLine,
                        ];
                    } elseif (! is_array($tokens[$nextIdx]) && $tokens[$nextIdx] === '$') {
                        $dangerousCalls[] = [
                            'function' => 'dynamic_static_expression',
                            'line' => $tokenLine,
                        ];
                    }
                }
                continue;
            }

            // Look for function calls: T_STRING followed by '('
            if ($tokenId === T_STRING) {
                $funcName = strtolower((string) $tokenText);
                $nextIdx  = $this->getNextMeaningfulTokenIndex($tokens, $i, $count);

                if ($nextIdx < $count && ! is_array($tokens[$nextIdx]) && $tokens[$nextIdx] === '(') {
                    // Skip if this T_STRING is part of a function/method definition
                    $prevIdx = $this->getPrevMeaningfulTokenIndex($tokens, $i);
                    $isDefinition = ($prevIdx >= 0 && is_array($tokens[$prevIdx]) && $tokens[$prevIdx][0] === T_FUNCTION);

                    if (! $isDefinition && in_array($funcName, self::DANGEROUS_FUNCTIONS, true)) {
                        $dangerousCalls[] = [
                            'function' => $funcName,
                            'line' => $tokenLine,
                        ];
                    }
                }
            }

            // Detect variable function calls, e.g. $f('id')
            if ($tokenId === T_VARIABLE) {
                $nextIdx = $this->getNextMeaningfulTokenIndex($tokens, $i, $count);

                if ($nextIdx < $count && ! is_array($tokens[$nextIdx]) && $tokens[$nextIdx] === '(') {
                    $dangerousCalls[] = [
                        'function' => 'variable_function_call:' . $tokenText,
                        'line' => $tokenLine,
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

    /**
     * Get index of next non-whitespace, non-comment token.
     */
    private function getNextMeaningfulTokenIndex(array $tokens, int $currentIndex, int $count): int
    {
        $next = $currentIndex + 1;
        while ($next < $count) {
            if (is_array($tokens[$next]) && in_array($tokens[$next][0], [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT], true)) {
                $next++;
                continue;
            }
            break;
        }

        return $next;
    }

    /**
     * Get index of previous non-whitespace, non-comment token.
     */
    private function getPrevMeaningfulTokenIndex(array $tokens, int $currentIndex): int
    {
        $prev = $currentIndex - 1;
        while ($prev >= 0) {
            if (is_array($tokens[$prev]) && in_array($tokens[$prev][0], [T_WHITESPACE, T_COMMENT, T_DOC_COMMENT], true)) {
                $prev--;
                continue;
            }
            break;
        }

        return $prev;
    }
}
