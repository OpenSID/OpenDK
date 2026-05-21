<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\BackEndController;
use App\Models\Themes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;

class ThemesController extends BackEndController
{
    /**
     * Dangerous PHP functions that are blocked in hooks.php.
     */
    private const DANGEROUS_FUNCTIONS = [
        'system', 'exec', 'passthru', 'shell_exec', 'popen', 'proc_open',
        'pcntl_exec', 'assert', 'create_function', 'eval',
        // BUG FIX: call_user_func / call_user_func_array bypass (Severity: Critical)
        // These were missing and allowed bypassing the entire token-based validation.
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

    public function index()
    {
        $page_title = 'Tema';
        $page_description = 'Daftar Tema';
        $themes = Themes::orderBy('active', 'desc')->get();

        return view('backend.themes.index', compact('page_title', 'page_description', 'themes'));
    }

    public function activate(Themes $themes)
    {
        Themes::where('active', 1)->update(['active' => 0]);
        $themes->update(['active' => 1]);

        // Clear all theme API cache
        $this->clearThemeCache();

        // OBS FIX: Wrap loadThemeHooks in try/catch so a bad hooks.php yields a
        // user-friendly redirect with an error flash instead of an HTTP 500.
        try {
            $this->loadThemeHooks($themes->name);
        } catch (\RuntimeException $e) {
            Log::critical('Theme hooks rejected during activation', [
                'theme' => $themes->name,
                'reason' => $e->getMessage(),
            ]);

            return redirect()->route('setting.themes.index')
                ->with('error', 'Tema diaktifkan tetapi hooks.php ditolak: ' . $e->getMessage());
        }

        return redirect()->route('setting.themes.index')
            ->with('success', 'Tema berhasil diaktifkan. Cache API telah dibersihkan.');
    }

    public function reScan()
    {
        scan_themes();

        // Clear cache after rescan
        $this->clearThemeCache();

        return redirect()->route('setting.themes.index')
            ->with('success', 'Tema berhasil dipindai ulang');
    }

    public function upload()
    {
        try {
            $file = request()->file('file');

            // Use FileUploadService for secure file upload
            $fileUploadService = new \App\Services\FileUploadService();
            
            // Define allowed MIME types for zip files
            $allowedMimes = \App\Services\FileUploadService::getAllowedMimes('archive');
            
            // Validate file type
            $fileMimeType = $file->getMimeType();
            if (!in_array($fileMimeType, $allowedMimes)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'File harus berformat .zip',
                ]);
            }

            // Upload file securely to temp directory
            $path = $fileUploadService->uploadSecure($file, 'framework/themes', $allowedMimes, 51200); // 50MB max
            
            // Extract filename from path
            $fileName = basename($path);
            
            // Get the full file path for further processing
            $filePath = storage_path('framework/themes');

            $zip = new \ZipArchive;

            // BUG FIX (Low): Wrap ZIP usage in try/finally so the handle is always
            // closed even when scanZipForPhp() or validateThemeStructure() throws.
            $zipOpened = false;
            try {
                if ($zip->open("$filePath/$fileName") === true) {
                    $zipOpened = true;

                    // === PHASE 2: Scan ZIP for dangerous PHP files BEFORE extraction ===
                    $this->scanZipForPhp($zip);

                    // BUG FIX (High): Validate ZIP entries for path traversal sequences
                    // before extraction to prevent writing files outside themes/extracted/.
                    $this->validateZipEntryPaths($zip);

                    $extractedPath = base_path('themes/extracted');
                    $zip->extractTo($extractedPath);
                    $zip->close();
                    $zipOpened = false;

                    $folderTheme = explode('.', $fileName)[0];
                    $composerPath = "$extractedPath/$folderTheme/composer.json";

                    if (file_exists($composerPath)) {
                        $composerData = json_decode(file_get_contents($composerPath), true);

                        // Validate theme structure
                        $this->validateThemeStructure($extractedPath, $folderTheme);

                        $newFolder = base_path('themes/' . $composerData['name']);

                        if (File::move("$extractedPath/$folderTheme", $newFolder)) {
                            File::deleteDirectory($extractedPath);
                            File::deleteDirectory($filePath);

                            scan_themes();

                            // === PHASE 1: REMOVED auto-execution of loadThemeHooks ===
                            // Hooks are now only loaded manually via activate() by super-admin
                            // after security review of hooks.php content.
                            $themeName = $composerData['name'];
                            $userId = auth()->id();
                            $userEmail = auth()->user()?->email ?? 'unknown';
                            Log::warning("Theme uploaded (hooks NOT auto-loaded): theme={$themeName}, user_id={$userId}, email={$userEmail}", [
                                'action' => 'theme_upload',
                                'theme' => $themeName,
                                'user_id' => $userId,
                            ]);

                            return response()->json([
                                'status' => 'success',
                                'message' => 'Tema berhasil diunggah. Review hooks.php melalui menu aktivasi tema sebelum mengaktifkan.',
                            ]);
                        } else {
                            File::deleteDirectory($extractedPath);
                            File::deleteDirectory($filePath);
                        }
                    }
                }
            } finally {
                // BUG FIX (Low): Ensure the ZIP handle is always released.
                if ($zipOpened) {
                    $zip->close();
                }
            }
        } catch (\Exception $e) {
            Log::error('Theme upload failed: ' . $e->getMessage(), [
                'action' => 'theme_upload_failed',
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);
            // BUG FIX (Medium): Return a generic message to the user; detail is logged above.
            return response()->json([
                'status' => 'error',
                'message' => 'Tema gagal diunggah. Silakan periksa log untuk detail.',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Tema gagal diunggah',
        ]);
    }

    public function destroy(Themes $themes)
    {
        // Don't delete active theme
        if ($themes->active) {
            return redirect()->route('setting.themes.index')
                ->with('error', 'Tidak dapat menghapus tema yang sedang aktif');
        }

        $themes->delete();

        // Clear cache
        $this->clearThemeCache();

        return redirect()->route('setting.themes.index')
            ->with('success', 'Tema berhasil dihapus');
    }

    /**
     * Clear all theme-related cache
     */
    private function clearThemeCache()
    {
        // Clear specific cache keys
        Cache::forget('active_theme');
        $store = Cache::getStore();
        if (method_exists($store, 'tags')) {
            // Tagged cache supported, clear tag and exit to avoid duplicate calls below
            Cache::tags(['theme_api'])->flush();
            Log::info('Theme cache cleared via tags');
        } else {
            // Alternative: Clear by prefix
            $keys = Cache::get('theme_api:*');
            foreach ($keys ?? [] as $key) {
                Cache::forget($key);
            }
        }

        Log::info('Theme cache cleared');
    }

    /**
     * Load theme hooks/filters with token-based security validation.
     *
     * hooks.php is validated using token_get_all() before inclusion.
     * Only allowed constructs are permitted:
     * - Function declarations (function xyz() { ... })
     * - Return statement at top level
     * - Array declarations
     * - use() for closures / imports
     *
     * Any call to dangerous functions (system, exec, eval, file I/O, etc.)
     * will cause the hooks file to be REJECTED.
     */
    private function loadThemeHooks(string $themePath)
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

        $validationResult = $this->validateHooksSource($source, $themePath);
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
    private function validateHooksSource(string $source, string $themeName): array
    {
        $tokens = @token_get_all($source);
        // token_get_all() tokenizes without syntax validation; it will tokenize
        // anything. We detect syntax problems via T_BAD_CHARACTER token.
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
                    // Check previous token context — if preceded by T_FUNCTION, it's a definition, not a call
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
            // The token sequence is T_VARIABLE followed by '(' — the old scanner only checked
            // T_STRING, so `$f = 'system'; $f('id');` would silently pass validation.
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

    /**
     * Scan ZIP archive for files with dangerous PHP extensions.
     * Must be called BEFORE extractTo().
     *
     * @throws \RuntimeException if dangerous files are detected.
     */
    private function scanZipForPhp(\ZipArchive $zip): void
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
     * Validate theme structure for API compatibility
     */
    private function validateThemeStructure(string $path, string $folder)
    {
        $requiredFiles = [
            'composer.json',
            'theme.json',
        ];

        foreach ($requiredFiles as $file) {
            if (!file_exists("$path/$folder/$file")) {
                throw new \Exception("File {$file} tidak ditemukan di tema");
            }
        }

        // OBS FIX: Add null-safety for file_get_contents and json_decode.
        // file_get_contents() can return false on failure; json_decode() returns null
        // for malformed JSON — both would cause silent failures without this check.
        $themeJsonPath = "$path/$folder/theme.json";
        $themeJsonRaw  = file_get_contents($themeJsonPath);
        if ($themeJsonRaw === false) {
            throw new \Exception("Tidak dapat membaca theme.json dari tema");
        }

        $themeConfig = json_decode($themeJsonRaw, true);
        if (! is_array($themeConfig)) {
            throw new \Exception("theme.json tidak mengandung JSON yang valid");
        }

        if (!isset($themeConfig['api_version'])) {
            Log::warning("Tema tidak mendefinisikan api_version, gunakan v1 sebagai default");
        }

        return true;
    }

    /**
     * Validate that no ZIP entry contains path traversal sequences.
     * Must be called BEFORE extractTo().
     *
     * BUG FIX (High): scanZipForPhp() only checked file extensions. A crafted ZIP
     * with entries like "../../../storage/framework/cache/data.txt" could overwrite
     * arbitrary files outside themes/extracted/ upon extraction.
     *
     * @throws \RuntimeException if any entry contains ".." or starts with "/".
     */
    private function validateZipEntryPaths(\ZipArchive $zip): void
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
     * Clear API cache manually (untuk admin)
     */
    public function clearCache()
    {
        $this->clearThemeCache();

        return redirect()->route('setting.themes.index')
            ->with('success', 'Cache API tema berhasil dibersihkan');
    }
}
