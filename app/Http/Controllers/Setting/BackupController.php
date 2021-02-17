<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use BackupManager\Filesystems\Destination;
use BackupManager\Manager;
use Illuminate\Http\Request;
use League\Flysystem\FileExistsException;
use League\Flysystem\FileNotFoundException;

class BackupController extends Controller
{
    /**
     * List of backup files.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        if (!file_exists(storage_path('app/backup/database'))) {
            $backups = [];
        } else {
            $backups = \File::allFiles(storage_path('app/backup/database'));

            // Sort files by modified time DESC
            usort($backups, function ($a, $b) {
                return -1 * strcmp($a->getMTime(), $b->getMTime());
            });
        }
        $page_title       = 'Daftar Backup';
        $page_description = 'Daftar Backup Database';

        return view('setting.backup.index', compact('backups','page_title','page_description'));
    }

    /**
     * Create new backup file.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'file_name' => 'nullable|max:30|regex:/^[\w._-]+$/',
        ]);

        try {
            $manager = app()->make(Manager::class);
            $fileName = $request->get('file_name') ?: date('Y-m-d_Hi');

            $manager->makeBackup()->run('mysql', [
                new Destination('local', 'backup/database/'.$fileName),
            ], 'gzip');

            flash(trans('Database Berhasil dibackup', ['filename' => $fileName.'.gz']), 'success');

            return redirect()->route('backups.index');
        } catch (FileExistsException $e) {
            flash(trans('Database Gagal dibackup', ['filename' => $fileName.'.gz']), 'danger');

            return redirect()->route('setting.backup.index');
        }
    }

    /**
     * Delete a backup file from storage.
     *
     * @param  string  $fileName
     * @return \Illuminate\Routing\Redirector
     */
    public function destroy($fileName)
    {
        if (file_exists(storage_path('app/backup/database/').$fileName)) {
            unlink(storage_path('app/backup/database/').$fileName);
        }

        flash(trans('Backup Database Berhasil Dihapus', ['filename' => $fileName]), 'warning');

        return redirect()->route('setting.backup.index');
    }

    /**
     * Download a backup file.
     *
     * @param  string $fileName
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download($fileName)
    {
        return response()->download(storage_path('app/backup/database/').$fileName);
    }

    /**
     * Restore database from a backup file.
     *
     * @param  string  $fileName
     * @return \Illuminate\Routing\Redirector
     */
    public function restore($fileName)
    {
        try {
            $manager = app()->make(Manager::class);
            $manager->makeRestore()->run('local', 'backup/database/'.$fileName, 'mysql', 'gzip');
        } catch (FileNotFoundException $e) {
        }

        flash(trans('Database Berhasil Restore', ['filename' => $fileName]), 'success');

        return redirect()->route('setting.backup.index');
    }

    /**
     * Upload a backup file to the storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Routing\Redirector
     */
    public function upload(Request $request)
    {
        $data = $request->validate([
            'backup_file' => 'required|mimetypes:application/x-gzip',
        ], [
            'backup_file.mimetypes' => 'Invalid file type, must be <strong>.gz</strong> file',
        ]);

        $file = $data['backup_file'];
        $fileName = $file->getClientOriginalName();

        if (file_exists(storage_path('app/backup/database/').$fileName) == false) {
            $file->storeAs('backup/db', $fileName);
        }

        flash(trans('Backup Database Berhasil Diupload', ['filename' => $fileName]), 'success');

        return redirect()->route('setting.backup.index');
    }
}
