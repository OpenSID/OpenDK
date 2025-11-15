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

        // Load theme hooks if exists
        $this->loadThemeHooks($themes->name);

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

            if ($file->getClientOriginalExtension() !== 'zip') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'File harus berformat .zip',
                ]);
            }

            $fileName = $file->getClientOriginalName();
            $filePath = storage_path('framework/themes');
            $file->move($filePath, $fileName);

            $zip = new \ZipArchive;
            if ($zip->open("$filePath/$fileName") === true) {
                $extractedPath = base_path('themes/extracted');
                $zip->extractTo($extractedPath);
                $zip->close();

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

                        // Load theme hooks
                        $this->loadThemeHooks($composerData['name']);

                        return response()->json([
                            'status' => 'success',
                            'message' => 'Tema berhasil diunggah dan siap digunakan',
                        ]);
                    } else {
                        File::deleteDirectory($extractedPath);
                        File::deleteDirectory($filePath);
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('File upload failed: ' . $e->getMessage());
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
     * Load theme hooks/filters
     */
    private function loadThemeHooks(string $themePath)
    {
        $hooksFile = base_path("themes/{$themePath}/hooks.php");

        if (file_exists($hooksFile)) {
            try {
                include_once $hooksFile;
                Log::info("Theme hooks loaded: {$themePath}");
            } catch (\Exception $e) {
                Log::error("Failed to load theme hooks: {$e->getMessage()}");
            }
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

        // Validate theme.json
        $themeConfig = json_decode(file_get_contents("$path/$folder/theme.json"), true);

        if (!isset($themeConfig['api_version'])) {
            Log::warning("Tema tidak mendefinisikan api_version, gunakan v1 sebagai default");
        }

        return true;
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
