<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package    OpenDK
 * @author     Tim Pengembang OpenDesa
 * @copyright  Hak Cipta 2017 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

namespace App\Http\Controllers;

use App\Http\Requests\EmailSmtpRequest;
use App\Mail\SmtpTestEmail;
use App\Models\EmailSmtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use RachidLaasri\LaravelInstaller\Helpers\RequirementsChecker;
use Rap2hpoutre\LaravelLogViewer\LaravelLogViewer;
use Spatie\Activitylog\Models\Activity;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\DataTables;

/**
 * Class LogViewerController
 */
class LogViewerController extends Controller
{
    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    private $log_viewer;

    protected $view_log = 'laravel-log-viewer::index';

    protected $profil;

    protected $requirements;

    /**
     * LogViewerController constructor.
     */
    public function __construct(Controller $profil, RequirementsChecker $checker)
    {
        $this->log_viewer = new LaravelLogViewer();
        $this->request = app('request');
        $this->profil = $profil;
        $this->requirements = $checker;
    }

    /**
     * @return array|mixed
     *
     * @throws \Exception
     */
    public function index()
    {
        $folderFiles = [];
        if ($this->request->input('f')) {
            $this->log_viewer->setFolder(Crypt::decrypt($this->request->input('f')));
            $folderFiles = $this->log_viewer->getFolderFiles(true);
        }
        if ($this->request->input('l')) {
            $this->log_viewer->setFile(Crypt::decrypt($this->request->input('l')));
        }

        if ($early_return = $this->earlyReturn()) {
            return $early_return;
        }

        $data = [
            'tab' => session('tab', 'log_viewer'),
            'logs' => $this->log_viewer->all(),
            'folders' => $this->log_viewer->getFolders(),
            'current_folder' => $this->log_viewer->getFolderName(),
            'folder_files' => $folderFiles,
            'files' => $this->log_viewer->getFiles(true),
            'current_file' => $this->log_viewer->getFileName(),
            'standardFormat' => true,
            'structure' => $this->log_viewer->foldersAndFiles(),
            'storage_path' => $this->log_viewer->getStoragePath(),

        ];

        if ($this->request->wantsJson()) {
            return $data;
        }

        if (is_array($data['logs']) && count($data['logs']) > 0) {
            $firstLog = reset($data['logs']);
            if (! $firstLog['context'] && ! $firstLog['level']) {
                $data['standardFormat'] = false;
            }
        }

        $phpSupportInfo = $this->requirements->checkPHPversion(
            config('installer.core.minPhpVersion')
        );
        $requirements = $this->requirements->check(
            config('installer.requirements')
        );

        $page_title = 'Info Sistem';

        //mengambil data smtp terakhir
        $email_smtp = EmailSmtp::getLatestEmailSmtp() ?? new EmailSmtp();

        return app('view')->make($this->view_log, $data)
        ->with('requirements', $requirements)
        ->with('page_title', $page_title)
        ->with('email_smtp', $email_smtp)
        ->with('phpSupportInfo', $phpSupportInfo);
    }

    /**
     * @return bool|mixed
     *
     * @throws \Exception
     */
    private function earlyReturn()
    {
        if ($this->request->input('f')) {
            $this->log_viewer->setFolder(Crypt::decrypt($this->request->input('f')));
        }

        if ($this->request->input('dl')) {
            return $this->download($this->pathFromInput('dl'));
        } elseif ($this->request->has('clean')) {
            app('files')->put($this->pathFromInput('clean'), '');

            return $this->redirect(url()->previous());
        } elseif ($this->request->has('del')) {
            app('files')->delete($this->pathFromInput('del'));

            return $this->redirect($this->request->url());
        } elseif ($this->request->has('delall')) {
            $files = ($this->log_viewer->getFolderName())
                        ? $this->log_viewer->getFolderFiles(true)
                        : $this->log_viewer->getFiles(true);
            foreach ($files as $file) {
                app('files')->delete($this->log_viewer->pathToLogFile($file));
            }

            return $this->redirect($this->request->url());
        }

        return false;
    }

    /**
     * @param  string  $input_string
     * @return string
     *
     * @throws \Exception
     */
    private function pathFromInput($input_string)
    {
        return $this->log_viewer->pathToLogFile(Crypt::decrypt($this->request->input($input_string)));
    }

    /**
     * @return mixed
     */
    private function redirect($to)
    {
        if (function_exists('redirect')) {
            return redirect($to);
        }

        return app('redirect')->to($to);
    }

    /**
     * @param  string  $data
     * @return mixed
     */
    private function download($data)
    {
        if (function_exists('response')) {
            return response()->download($data);
        }

        // For laravel 4.2
        return app('\Illuminate\Support\Facades\Response')->download($data);
    }

    public function linkStorage()
    {
        Artisan::call('storage:link'); // this will do the command line job
        sleep(2);

        return back()->with('tab', 'ekstensi')->with('success', 'Berhasil menjalankan php artisan storage:link');
    }

    public function queueListen()
    {
        try {
            Artisan::call('queue:work', ['--stop-when-empty' => null]); // this will do the command line job
        } catch (\Exception $e) {
            report($e);

            return response()->json([
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'success' => true,
        ], Response::HTTP_OK);
    }

    public function migrasi()
    {
        Artisan::call('migrate', ['--force' => true]); // this will do the command line job
        sleep(2);

        return back()->with('tab', 'ekstensi')->with('success', 'Berhasil menjalankan migrasi');
    }

    /*
    fungsi untuk menambahkan akun email smtp,
    fungsi akan terus bertambah dan data yang diambil ialah data yang terakhir
    */
    public function storeEmailSmtp(EmailSmtpRequest $request)
    {
        try {
            EmailSmtp::create($request->all());
        } catch (\Exception $e) {
            report($e);

            return back()->withInput()->with('tab', 'email_smtp')->with('error', 'SMTP gagal diubah!');
        }

        return back()->with('tab', 'email_smtp')->with('success', 'Berhasil memperbaruhi SMTP');
    }

    //function for testing email smtp
    public function sendTestEmailSmtp($email)
    {
        try {
            Mail::to($email)->send(new SmtpTestEmail());
        } catch (\Exception $e) {
            report($e);

            return response()->json([
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'success' => true,
        ], Response::HTTP_OK);
    }

    /**
     * Menampilkan data activity logs untuk DataTables
     */
    public function getActivityLogs(Request $request)
    {
        $query = Activity::with('causer')->latest();

        // Filter berdasarkan event/description
        if ($request->filled('action')) {
            $query->where('event', $request->action);
        }

        // Filter berdasarkan user
        if ($request->filled('user_id')) {
            $query->where('causer_id', $request->user_id);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('created_at', [
                $request->date_from . ' 00:00:00',
                $request->date_to . ' 23:59:59'
            ]);
        }

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('user_display', function ($log) {
                return $log->causer ? $log->causer->name : 'System';
            })
            ->addColumn('action_badge', function ($log) {
                $badges = [
                    'login' => 'success',
                    'logout' => 'info',
                    'created' => 'primary',
                    'updated' => 'warning',
                    'deleted' => 'danger',
                    'retrieved' => 'secondary'
                ];
                $badge = $badges[$log->event] ?? 'secondary';
                return '<span class="badge badge-' . $badge . '">' . ucfirst($log->event ?? 'N/A') . '</span>';
            })
            ->addColumn('subject_display', function ($log) {
                $subjectType = $log->subject_type ? class_basename($log->subject_type) : 'N/A';
                return $subjectType . ($log->subject_id ? " (ID: {$log->subject_id})" : '');
            })
            ->addColumn('formatted_date', function ($log) {
                return $log->created_at->format('d/m/Y H:i:s');
            })
            ->addColumn('aksi', function ($log) {
                return '<button class="btn btn-sm btn-info view-detail" data-id="' . $log->id . '">
                    <i class="fa fa-eye"></i> Detail
                </button>';
            })
            ->rawColumns(['action_badge', 'aksi'])
            ->make(true);
    }

    /**
     * Menampilkan detail activity log
     */
    public function getActivityLogDetail($id)
    {
        $log = Activity::with('causer')->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $log->id,
                'user' => $log->causer ? $log->causer->name : 'System',
                'event' => $log->event,
                'description' => $log->description,
                'subject_type' => $log->subject_type,
                'subject_id' => $log->subject_id,
                'properties' => $log->properties,
                'created_at' => $log->created_at->format('d/m/Y H:i:s'),
            ]
        ]);
    }

    /**
     * Menghapus activity logs lama (cleanup)
     */
    public function cleanupActivityLogs(Request $request)
    {
        try {
            $days = $request->input('days', 30); // Default 30 hari
            $cutoffDate = now()->subDays($days);
            
            $deletedCount = Activity::where('created_at', '<', $cutoffDate)->delete();
            
            return response()->json([
                'success' => true,
                'message' => "Berhasil menghapus {$deletedCount} log aktivitas yang lebih dari {$days} hari."
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus log aktivitas: ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
