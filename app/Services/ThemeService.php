<?php

namespace App\Services;

use App\Models\Themes;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ThemeService
{
    protected ThemeHooksValidator $validator;
    protected CacheService $cacheService;
    protected FileUploadService $fileUploadService;

    public function __construct(
        ThemeHooksValidator $validator, 
        CacheService $cacheService, 
        FileUploadService $fileUploadService
    ) {
        $this->validator = $validator;
        $this->cacheService = $cacheService;
        $this->fileUploadService = $fileUploadService;
    }

    public function activate(Themes $themes): void
    {
        Themes::where('active', 1)->update(['active' => 0]);
        $themes->update(['active' => 1]);

        $this->clearCache();

        $this->validator->loadHooks($themes->name);
    }

    public function reScan(): void
    {
        scan_themes();
        $this->clearCache();
    }

    public function clearCache(): void
    {
        $this->cacheService->removeCacheByKey('active_theme');
        \Illuminate\Support\Facades\Cache::forget('das_themes_table_exists');
        $store = \Illuminate\Support\Facades\Cache::getStore();
        if (method_exists($store, 'tags')) {
            \Illuminate\Support\Facades\Cache::tags(['theme_api'])->flush();
            Log::info('Theme cache cleared via tags');
        } else {
            $this->cacheService->removeCachePrefix('theme_api');
            Log::info('Theme cache cleared via prefix');
        }
    }

    public function installFromZip(UploadedFile $file): array
    {
        $allowedMimes = FileUploadService::getAllowedMimes('archive');
        
        $fileMimeType = $file->getMimeType();
        if (!in_array($fileMimeType, $allowedMimes)) {
            return [
                'status' => 'error',
                'message' => 'File harus berformat .zip',
            ];
        }

        $path = $this->fileUploadService->uploadSecure($file, 'framework/themes', $allowedMimes, 51200);
        
        $fileName = basename($path);
        $filePath = storage_path('framework/themes');

        $zip = new \ZipArchive;
        $zipOpened = false;

        try {
            if ($zip->open("$filePath/$fileName") === true) {
                $zipOpened = true;

                $this->validator->scanZipForPhp($zip);
                $this->validator->validateZipEntryPaths($zip);

                $extractedPath = base_path('themes/extracted');
                $zip->extractTo($extractedPath);
                $zip->close();
                $zipOpened = false;

                $folderTheme = explode('.', $fileName)[0];
                $composerPath = "$extractedPath/$folderTheme/composer.json";

                if (file_exists($composerPath)) {
                    $composerData = json_decode(file_get_contents($composerPath), true);

                    $this->validateThemeStructure($extractedPath, $folderTheme);

                    $newFolder = base_path('themes/' . $composerData['name']);

                    if (File::move("$extractedPath/$folderTheme", $newFolder)) {
                        File::deleteDirectory($extractedPath);
                        File::deleteDirectory($filePath);

                        scan_themes();

                        $themeName = $composerData['name'];
                        $userId = auth()->id();
                        $userEmail = auth()->user()?->email ?? 'unknown';
                        Log::warning("Theme uploaded (hooks NOT auto-loaded): theme={$themeName}, user_id={$userId}, email={$userEmail}", [
                            'action' => 'theme_upload',
                            'theme' => $themeName,
                            'user_id' => $userId,
                        ]);

                        return [
                            'status' => 'success',
                            'message' => 'Tema berhasil diunggah. Review hooks.php melalui menu aktivasi tema sebelum mengaktifkan.',
                        ];
                    } else {
                        File::deleteDirectory($extractedPath);
                        File::deleteDirectory($filePath);
                    }
                }
            }
        } finally {
            if ($zipOpened) {
                $zip->close();
            }
        }
        
        return [
            'status' => 'error',
            'message' => 'Tema gagal diunggah (struktur arsip tidak valid atau file hilang)',
        ];
    }

    private function validateThemeStructure(string $path, string $folder): bool
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
}
