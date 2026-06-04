<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\BackEndController;
use App\Models\Themes;
use App\Services\ThemeService;
use Illuminate\Support\Facades\Log;

class ThemesController extends BackEndController
{
    protected ThemeService $themeService;

    public function __construct(ThemeService $themeService)
    {
        $this->themeService = $themeService;
        // In Laravel, if parent has no constructor this is fine, if it does, 
        // we might need parent::__construct(), but usually it's injected.
    }

    public function index()
    {
        $page_title = 'Tema';
        $page_description = 'Daftar Tema';
        $themes = Themes::orderBy('active', 'desc')->get();

        return view('backend.themes.index', compact('page_title', 'page_description', 'themes'));
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
        try {
            $file = request()->file('file');
            
            if (!$file) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'File tema tidak ditemukan',
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
     * Clear API cache manually (untuk admin)
     */
    public function clearCache()
    {
        $this->themeService->clearCache();

        return redirect()->route('setting.themes.index')
            ->with('success', 'Cache API tema berhasil dibersihkan');
    }
}
