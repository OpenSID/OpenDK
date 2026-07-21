<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\BackEndController;
use App\Models\Themes;
use App\Services\ThemeService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log;

class ThemesController extends BackEndController
{
    protected ThemeService $themeService;

    public function __construct(ThemeService $themeService)
    {
        parent::__construct();
        $this->themeService = $themeService;
    }

    public function index(): View
    {
        $page_title = 'Tema';
        $page_description = 'Daftar Tema';
        $themes = Themes::orderBy('active', 'desc')->get();
        $showUnggahButton = ! $this->isDatabaseGabungan();

        return view('backend.themes.index', compact('page_title', 'page_description', 'themes', 'showUnggahButton'));
    }

    public function activate(Themes $themes)
    {
        try {
            $this->themeService->activate($themes);
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
        $this->themeService->reScan();

        return redirect()->route('setting.themes.index')
            ->with('success', 'Tema berhasil dipindai ulang');
    }

    public function upload()
    {
        if($this->isDatabaseGabungan()){
            return response()->json([
                'status' => 'error',
                'message' => 'Unggah tema tidak diijinkan pada database gabungan',
            ], 403);
        }
        try {
            $file = request()->file('file');

            if (!$file) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'File tema tidak ditemukan',
                ], 400);
            }

            if (!$file->isValid()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Upload file gagal: ' . $file->getErrorMessage(),
                ]);
            }

            $result = $this->themeService->installFromZip($file);

            return response()->json($result);

        } catch (\Exception $e) {
            Log::error('Theme upload failed: ' . $e->getMessage(), [
                'action' => 'theme_upload_failed',
                'user_id' => auth()->id(),
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Tema gagal diunggah. Silakan periksa log untuk detail.',
            ]);
        }
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
        $this->themeService->clearCache();

        return redirect()->route('setting.themes.index')
            ->with('success', 'Tema berhasil dihapus');
    }

    /**
     * Clear API cache manually (untuk admin).
     */
    public function clearCache()
    {
        $this->themeService->clearCache();

        return redirect()->route('setting.themes.index')
            ->with('success', 'Cache API tema berhasil dibersihkan');
    }
}
