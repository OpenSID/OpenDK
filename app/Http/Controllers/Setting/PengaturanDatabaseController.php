<?php

namespace App\Http\Controllers\Setting;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Spatie\Backup\Tasks\Backup\BackupJobFactory;

use Illuminate\Support\Facades\Artisan;

use Illuminate\Support\Facades\File;

use Exception;


class PengaturanDatabaseController extends Controller
{
    // lokasi folder backup disimpan
    protected $destination;

    public function __construct()
    {
        $this->destination = config('backup.backup.name');
    }

    public function index()
    {
        $page_title = 'Backup Database';
        $page_description = 'Pengaturan Database';

        return view('setting.pengaturan_database.table-backup', compact('page_title', 'page_description'));
    }

    public function getDataBackup()
    {
        $disk = Storage::disk('local');
        $files = $disk->files($this->destination); // path file

        $backups = collect($files)->map(function ($file) use ($disk) {
            return [
                'name' => basename($file),
                'size' => $this->formatSizeUnits($disk->size($file)),
                'date' => Carbon::createFromTimestamp($disk->lastModified($file))->format('d-m-Y H:i:s'),
                'path' => $disk->path($file),
                'location' => $file,
            ];
        });

        return DataTables::of($backups)
            ->addIndexColumn()
            ->addColumn('aksi', function ($row) {
                $downloadUrl = route('setting.pengaturan-database.download', ['file' => basename($row['location'])]);
                $deleteUrl = route('setting.pengaturan-database.delete', ['file' => basename($row['location'])]);

                return '
                <a href="' . $downloadUrl . '" class="btn btn-primary btn-sm" title="Unduh">
                    <i class="fa fa-download"></i>
                </a>
                <a href="javascript:void(0)" onclick="deleteBackup(\'' . $deleteUrl . '\')" class="btn btn-danger btn-sm" title="Hapus">
                    <i class="fa fa-trash"></i>
                </a>';
            })
            ->make(true);
    }

    public function createBackup()
    {
        try {
            Log::info('Starting backup process.');

            $exitCode = Artisan::call('backup:run');
            $output = Artisan::output();

            Log::info('Backup command output: ' . $output);
            Log::info('Backup exit code: ' . $exitCode);

            if ($exitCode !== 0) {
                Log::error('Backup process failed with exit code: ' . $exitCode);

                return response()->json([
                    'success' => false,
                    'message' => 'Backup gagal. Periksa log aplikasi untuk detail.',
                ], 500);
            }

            Log::info('Ending backup process.');

            return response()->json(['success' => true, 'message' => 'Backup completed successfully']);
        } catch (\Exception $e) {
            Log::error('Backup process failed: ' . $e->getMessage(), ['exception' => $e]);

            return response()->json(['success' => false, 'message' => 'Backup process failed', 'error' => $e->getMessage()], 500);
        }
    }

    public function downloadBackup($file)
    {
        $disk = Storage::disk('local');
        $filePath = "{$this->destination}/{$file}";

        if ($disk->exists($filePath)) {
            return $disk->download($filePath);
        }

        return redirect()->route('setting.pengaturan-database.backup')->with('error', 'File backup tidak ditemukan.');
    }

    public function deleteBackup($file)
    {
        $disk = Storage::disk('local');
        $filePath = "{$this->destination}/{$file}";

        if ($disk->exists($filePath)) {
            $disk->delete($filePath);
            return redirect()->route('setting.pengaturan-database.backup')->with('success', 'Backup berhasil dihapus');
        }

        return redirect()->route('setting.pengaturan-database.backup')->with('error', 'Backup tidak ditemukan');
    }

    /**
     * Fungsi untuk format ukuran file
     */
    private function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            return $bytes . ' bytes';
        } elseif ($bytes == 1) {
            return $bytes . ' byte';
        } else {
            return '0 bytes';
        }
    }

    // RESTORE DATABASE

    public function restoreDatabase()
    {
        $page_title = 'Restore Database';
        $page_description = 'Pengaturan Database';

        return view('setting.pengaturan_database.table-restore', compact('page_title', 'page_description'));
    }


    public function restoreBackup(Request $request)
    {
        $request->validate([
            'backupFile' => 'required|file',
        ]);

        $file = $request->file('backupFile');
        $extension = strtolower($file->getClientOriginalExtension());

        // Fix #4: Hanya terima .zip dari backup system — .sql tidak lagi didukung
        if ($extension !== 'zip') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya file .zip dari backup system yang diizinkan untuk restore.',
            ], 422);
        }

        $fileUploadService = new \App\Services\FileUploadService();
        $allowedMimes = \App\Services\FileUploadService::getAllowedMimes('archive');
        $maxSize = 512000; // 500MB

        $path = $fileUploadService->uploadSecure($file, 'backup-temp', $allowedMimes, $maxSize);

        $filename = basename($path);
        $allowedChars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789._-';

        if (! preg_match("/^[" . $allowedChars . "]+$/", $filename)) {
            return response()->json([
                'success' => false,
                'message' => 'Nama file tidak valid. Hanya boleh mengandung huruf (a-z/A-Z), angka (0-9), titik (.), dan garis bawah (_).',
            ], 422);
        }

        $setDir = 'backup-temp';

        try {
            Log::info('Starting restore process.');

            // Fix #3: Pre-restore backup otomatis (best-effort, tidak memblokir restore jika gagal)
            try {
                Log::info('Menjalankan pre-restore backup otomatis...');
                $backupExit = Artisan::call('backup:run');
                if ($backupExit !== 0) {
                    Log::warning('Pre-restore backup gagal (exit code: ' . $backupExit . '). Restore tetap dilanjutkan.');
                } else {
                    Log::info('Pre-restore backup berhasil.');
                }
            } catch (\Exception $backupEx) {
                Log::warning('Pre-restore backup exception: ' . $backupEx->getMessage() . '. Restore tetap dilanjutkan.');
            }

            $finalPath = Storage::disk('public')->path($path);

            Log::info('Restore from ZIP: ' . $finalPath);
            $result = $this->restoreFromZip($finalPath);

            return response()->json([
                'success' => true,
                'message' => "Restore berhasil. Database dan {$result['files_restored']} file asset telah dipulihkan.",
            ], 200);
        } catch (\Exception $e) {
            Log::error('Restore error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        } finally {
            $this->deleteTemporaryDirectory(Storage::disk('public')->path($setDir));
            Log::info('Direktori sementara berhasil dihapus.');
        }
    }

    private function runRestoreDatabase($sqlFilePath)
    {
        // Ambil konfigurasi database
        $dbHost = config('database.connections.mysql.host');
        $dbPort = config('database.connections.mysql.port');
        $dbName = config('database.connections.mysql.database');
        $dbUser = config('database.connections.mysql.username');
        $dbPass = config('database.connections.mysql.password');

        // Gunakan path binary dari konfigurasi (sama seperti spatie untuk mysqldump)
        $binaryPath = config('database.connections.mysql.dump.dump_binary_path', '');
        $mysqlBinary = $binaryPath
            ? rtrim($binaryPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'mysql'
            : 'mysql';

        // Fix #1: Tambahkan escapeshellarg pada $sqlFilePath untuk mencegah shell injection
        $command = sprintf(
            '%s -h%s -P%s -u%s %s %s < %s 2>&1',
            escapeshellarg($mysqlBinary),
            escapeshellarg($dbHost),
            escapeshellarg($dbPort),
            escapeshellarg($dbUser),
            $dbPass !== '' ? '--password=' . escapeshellarg($dbPass) : '',
            escapeshellarg($dbName),
            escapeshellarg($sqlFilePath)
        );

        exec($command, $output, $returnVar);

        // cek hasil
        if ($returnVar !== 0) {
            Log::error('Database restore failed. Command output: ' . implode("\n", $output));
            throw new \Exception('Gagal melakukan restore database. Periksa file SQL atau konfigurasi database.');
        }

        Log::info('Database restored successfully.');
    }

    /**
     * Restore file asset dan database dari file ZIP backup (spatie/laravel-backup).
     */
    private function restoreFromZip($zipFilePath)
    {
        $zip = new \ZipArchive();

        if ($zip->open($zipFilePath) !== true) {
            throw new \Exception('Gagal membuka file ZIP. Pastikan file tidak rusak.');
        }

        $sqlDumpPath = null;
        $filesRestored = 0;
        $storageBase = storage_path('app');
        $tempBase = storage_path('app/public/backup-temp');
        $tempSqlPath = $tempBase . '/restore-dump.sql';

        try {
            // Fix #6: Hitung jumlah db-dump entries sebelum proses — tolak jika > 1
            $dbDumpCount = 0;
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $name = str_replace('\\', '/', $zip->getNameIndex($i));
                if (str_starts_with($name, 'db-dumps/') && ! str_ends_with($name, '/')) {
                    $dbDumpCount++;
                }
            }
            if ($dbDumpCount > 1) {
                throw new \Exception("File ZIP mengandung {$dbDumpCount} database dump. Hanya 1 yang diizinkan.");
            }

            for ($i = 0; $i < $zip->numFiles; $i++) {
                $entry = $zip->getNameIndex($i);

                // Skip directories (entries ending with /)
                if (str_ends_with($entry, '/')) {
                    continue;
                }

                // Normalize backslash to forward slash (cross-platform ZIP entries)
                $normalized = str_replace('\\', '/', $entry);

                // Database dump — ekstrak ke file terpisah untuk restore via mysql
                if (str_starts_with($normalized, 'db-dumps/')) {
                    // Fix #2: Validasi path traversal dan subdirektori sebelum extractTo()
                    $relativeToDbDumps = substr($normalized, strlen('db-dumps/'));
                    if (str_contains($relativeToDbDumps, '..') || str_contains($relativeToDbDumps, '/')) {
                        Log::warning('Skipping db-dumps entry with traversal or subdirectory: ' . $entry);
                        continue;
                    }

                    $zip->extractTo($tempBase, $entry);
                    $extractedPath = $tempBase . '/' . $entry;
                    if (file_exists($extractedPath)) {
                        rename($extractedPath, $tempSqlPath);
                        $sqlDumpPath = $tempSqlPath;
                    }
                    $dbDumpsDir = $tempBase . '/db-dumps';
                    if (is_dir($dbDumpsDir)) {
                        $this->deleteTemporaryDirectory($dbDumpsDir);
                    }
                    continue;
                }

                // File storage — map ke path relatif di dalam storage/app/
                $relativePath = $this->mapZipEntryToStoragePath($normalized);
                if ($relativePath === null) {
                    continue;
                }

                // Build target path absolut
                $targetPath = $storageBase . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);
                $targetDir = dirname($targetPath);

                // Validasi: target harus berada di dalam storage/app/
                $normalizedTargetDir = str_replace('\\', '/', $targetDir);
                $normalizedStorageBase = str_replace('\\', '/', $storageBase);
                if (! str_starts_with($normalizedTargetDir, $normalizedStorageBase)) {
                    Log::warning('Skipping entry outside storage: ' . $entry);
                    continue;
                }

                // Buat direktori jika belum ada
                if (! is_dir($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }

                // Ekstrak file individual
                $content = $zip->getFromIndex($i);
                if ($content !== false) {
                    file_put_contents($targetPath, $content);
                    $filesRestored++;
                } else {
                    Log::warning('Failed to extract from ZIP: ' . $entry);
                }
            }

            // Restore database dari SQL dump yang ditemukan di ZIP
            if ($sqlDumpPath && file_exists($sqlDumpPath)) {
                Log::info('Restoring database from ZIP dump: ' . $sqlDumpPath);
                $this->runRestoreDatabase($sqlDumpPath);
            } else {
                Log::warning('No database dump found in ZIP — only file assets restored');
            }

            Log::info('Restore from ZIP completed: ' . $filesRestored . ' files restored');

            return ['files_restored' => $filesRestored];
        } finally {
            $zip->close();
            if (file_exists($tempSqlPath)) {
                unlink($tempSqlPath);
            }
        }
    }

    /**
     * Map ZIP entry ke path relatif di dalam storage/app/.
     * Menangani baik path absolute (backup lama, relative_path=null) maupun
     * path relative (backup baru, relative_path=base_path()).
     */
    private function mapZipEntryToStoragePath($entry)
    {
        $skipDirs = ['backup-storage/', 'backup-temp/', 'framework/', 'logs/', 'debugbar/'];

        $marker = 'storage/app/';
        $pos = strpos($entry, $marker);

        if ($pos === false) {
            return null;
        }

        $relativePath = substr($entry, $pos + strlen($marker));

        // Security: reject paths containing directory traversal
        if (str_contains($relativePath, '..')) {
            return null;
        }

        // Skip direktori yang dikecualikan
        foreach ($skipDirs as $dir) {
            if (str_starts_with($relativePath, $dir)) {
                return null;
            }
        }

        if (empty($relativePath)) {
            return null;
        }

        return $relativePath;
    }

    private function deleteTemporaryDirectory($path)
    {
        // Fix #7: Tolak symlink dan path di luar storage/ untuk mencegah penghapusan file penting
        if (is_link($path)) {
            Log::warning('deleteTemporaryDirectory: path adalah symlink, dibatalkan: ' . $path);
            return false;
        }

        $realPath = realpath($path);
        $allowedBase = realpath(storage_path());

        if ($realPath === false || $allowedBase === false) {
            Log::warning('deleteTemporaryDirectory: path tidak dapat diresolve: ' . $path);
            return false;
        }

        // Pastikan path berada di dalam storage/
        $normalizedReal = str_replace('\\', '/', $realPath);
        $normalizedBase = str_replace('\\', '/', $allowedBase);
        if (! str_starts_with($normalizedReal, $normalizedBase)) {
            Log::error('deleteTemporaryDirectory: path di luar storage/, dibatalkan: ' . $realPath);
            return false;
        }

        if (is_dir($path)) {
            $files = array_diff(scandir($path), ['.', '..']);
            foreach ($files as $file) {
                $filePath = $path . DIRECTORY_SEPARATOR . $file;
                is_dir($filePath) ? $this->deleteTemporaryDirectory($filePath) : unlink($filePath);
            }
            return rmdir($path);
        }
    }
}
