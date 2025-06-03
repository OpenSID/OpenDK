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
use App\Http\Controllers\Setting\ZipArchive;


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
    
            Artisan::call('backup:run');
    
            Log::info('Backup command output: ' . Artisan::output());
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

        // Validasi tipe file
        $allowedExtensions = ['sql'];
        $extension = $request->file('backupFile')->getClientOriginalExtension();
        if (!in_array($extension, $allowedExtensions)) {
            return response()->json(['message' => 'File harus berupa .sql'], 422);
        }

        $filename = $request->file('backupFile')->getClientOriginalName();
        $allowedChars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789._-';

        if (!preg_match("/^[" . $allowedChars . "]+$/", $filename)) {
            return response()->json(['message' => 'Nama file tidak valid, Hanya boleh mengandung huruf (a-z/A-Z), angka (0-9), titik (.), dan garis bawah (_)'], 422);
        }

        // Simpan file ke direktori sementara
        $file = $request->file('backupFile');
        $setDir = 'backup-temp';
        $path = $file->storeAs($setDir, $file->getClientOriginalName());
        
        try {
            Log::info('Starting restore process.');

            $finalPath = Storage::path($path);
            Log::info("Normalized SQL file path from upload: $finalPath");

            // Jalankan proses restore
            $this->runRestoreDatabase($finalPath);

            return response()->json(['message' => 'Database berhasil direstore.'], 200);
        } catch (\Exception $e) {
            Log::error('Restore error: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        } finally {
            // Hapus file sementara
            $this->deleteTemporaryDirectory(Storage::path($setDir));
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

        // Buat command untuk restore database
        $command = sprintf(
            'mysql -h%s -P%s -u%s %s %s < "%s"',
            escapeshellarg($dbHost),
            escapeshellarg($dbPort),
            escapeshellarg($dbUser),
            $dbPass !== '' ? '-p' . escapeshellarg($dbPass) : '',
            escapeshellarg($dbName),
            $sqlFilePath
        );

        // $this->info("Executing command: $command");

        exec($command, $output, $returnVar);

        // cek hasil
        if ($returnVar !== 0) {
            Log::error('Database restore failed. Command output: ' . implode("\n", $output));
            throw new \Exception('Gagal melakukan restore database. Periksa file SQL atau konfigurasi database.');
        }

        Log::info('Database restored successfully.');
    }


    private function deleteTemporaryDirectory($path)
    {
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
