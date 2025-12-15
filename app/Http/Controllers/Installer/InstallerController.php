<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright  Hak Cipta 2017 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

namespace App\Http\Controllers\Installer;

use App\Http\Controllers\Controller;
use App\Helpers\SystemRequirementsChecker;
use Illuminate\Http\Request;

/**
 * Pengganti untuk installer controller dari package yang sudah abandoned
 */
class InstallerController extends Controller
{
    protected $requirements;

    public function __construct(SystemRequirementsChecker $requirements)
    {
        $this->requirements = $requirements;
    }

    /**
     * Display the installer welcome page.
     */
    public function welcome()
    {
        if (sudahInstal()) {
            return redirect('/');
        }

        return view('vendor.installer.welcome');
    }

    /**
     * Display the requirements page.
     */
    public function requirements()
    {
        if (sudahInstal()) {
            return redirect('/');
        }

        $phpSupportInfo = $this->requirements->checkPHPversion(
            config('installer.core.minPhpVersion')
        );

        $requirements = $this->requirements->check(
            config('installer.requirements')
        );

        return view('vendor.installer.requirements', compact('requirements', 'phpSupportInfo'));
    }

    /**
     * Display the permissions page.
     */
    public function permissions()
    {
        if (sudahInstal()) {
            return redirect('/');
        }

        $permissions = $this->requirements->checkPermissions(
            config('installer.permissions')
        );

        return view('vendor.installer.permissions', compact('permissions'));
    }

    /**
     * Display the environment page.
     */
    public function environment()
    {
        if (sudahInstal()) {
            return redirect('/');
        }

        return view('vendor.installer.environment');
    }

    /**
     * Display the environment wizard page.
     */
    public function environmentWizard()
    {
        if (sudahInstal()) {
            return redirect('/');
        }

        return view('vendor.installer.environment-wizard');
    }

    /**
     * Display the environment classic editor page.
     */
    public function environmentClassic()
    {
        if (sudahInstal()) {
            return redirect('/');
        }

        $envConfig = file_exists(base_path('.env')) ? file_get_contents(base_path('.env')) : '';

        return view('vendor.installer.environment-classic', compact('envConfig'));
    }

    /**
     * Save environment configuration from wizard.
     */
    public function environmentSaveWizard(Request $request)
    {
        // dd($request->all());
        // Validasi input
        $request->validate([
            'app_name' => 'required|string|max:255',
            'app_environment' => 'required|string',
            'app_debug' => 'required',
            'app_url' => 'required|url',
            'database_connection' => 'required|string',
            'database_hostname' => 'required|string',
            'database_port' => 'required|numeric',
            'database_name' => 'required|string',
            'database_username' => 'required|string',
            'database_password' => 'nullable|string',
        ]);
        // dd($request->all());
        // Ambil data dari form
        $envConfig = [
            'APP_NAME' => '"' . $request->app_name . '"',
            'APP_ENV' => $request->app_environment,
            'APP_KEY' => 'base64:' . base64_encode(random_bytes(32)),
            'APP_DEBUG' => $request->app_debug ? 'true' : 'false',
            'APP_URL' => $request->app_url,
            'LOG_CHANNEL' => 'stack',
            'LOG_DEPRECATIONS_CHANNEL' => 'null',
            'LOG_LEVEL' => 'debug',

            'DB_CONNECTION' => $request->database_connection,
            'DB_HOST' => $request->database_hostname,
            'DB_PORT' => $request->database_port,
            'DB_DATABASE' => $request->database_name,
            'DB_USERNAME' => $request->database_username,
            'DB_PASSWORD' => $request->database_password ?: '',

            'BROADCAST_DRIVER' => 'log',
            'CACHE_DRIVER' => 'file',
            'FILESYSTEM_DISK' => 'local',
            'QUEUE_CONNECTION' => 'sync',
            'SESSION_DRIVER' => 'file',
            'SESSION_LIFETIME' => '120',

            'MEMCACHED_HOST' => '127.0.0.1',

            'REDIS_HOST' => '127.0.0.1',
            'REDIS_PASSWORD' => 'null',
            'REDIS_PORT' => '6379',

            'MAIL_MAILER' => 'smtp',
            'MAIL_HOST' => 'mailpit',
            'MAIL_PORT' => '1025',
            'MAIL_USERNAME' => 'null',
            'MAIL_PASSWORD' => 'null',
            'MAIL_ENCRYPTION' => 'null',
            'MAIL_FROM_ADDRESS' => '"hello@example.com"',
            'MAIL_FROM_NAME' => '"${APP_NAME}"',

            'AWS_ACCESS_KEY_ID' => '',
            'AWS_SECRET_ACCESS_KEY' => '',
            'AWS_DEFAULT_REGION' => 'us-east-1',
            'AWS_BUCKET' => '',
            'AWS_USE_PATH_STYLE_ENDPOINT' => 'false',

            'PUSHER_APP_ID' => '',
            'PUSHER_APP_KEY' => '',
            'PUSHER_APP_SECRET' => '',
            'PUSHER_HOST' => '',
            'PUSHER_PORT' => '443',
            'PUSHER_SCHEME' => 'https',
            'PUSHER_APP_CLUSTER' => 'mt1',

            'VITE_PUSHER_APP_KEY' => '"${PUSHER_APP_KEY}"',
            'VITE_PUSHER_HOST' => '"${PUSHER_HOST}"',
            'VITE_PUSHER_PORT' => '"${PUSHER_PORT}"',
            'VITE_PUSHER_SCHEME' => '"${PUSHER_SCHEME}"',
            'VITE_PUSHER_APP_CLUSTER' => '"${PUSHER_APP_CLUSTER}"',
        ];

        // Convert array ke format .env
        $envContent = '';
        foreach ($envConfig as $key => $value) {
            $envContent .= $key . '=' . $value . "\n";
        }

        // Simpan ke file .env
        file_put_contents(base_path('.env'), $envContent);

        return redirect()->route('installer.database')
            ->with('success', 'Konfigurasi environment berhasil disimpan');
    }

    /**
     * Save environment configuration from classic editor.
     */
    public function environmentSaveClassic(Request $request)
    {
        // Validasi input
        $request->validate([
            'envConfig' => 'required|string',
        ]);

        // Simpan content langsung dari textarea ke file .env
        file_put_contents(base_path('.env'), $request->envConfig);
        
        return redirect()->route('installer.database')
            ->with('success', 'Konfigurasi environment berhasil disimpan');
    }

    /**
     * Display the database page.
     */
    public function database()
    {
        if (sudahInstal()) {
            return redirect('/');
        }

        // Test database connection
        $canConnect = false;
        $message = '';

        try {
            \DB::connection()->getPdo();
            $canConnect = true;
            $message = 'Koneksi database berhasil!';
        } catch (\Exception $e) {
            $message = 'Koneksi database gagal: ' . $e->getMessage();
        }

        return view('vendor.installer.database', compact('canConnect', 'message'));
    }

    /**
     * Display the final page.
     */
    public function final()
    {
        if (sudahInstal()) {
            return redirect('/');
        }

        return view('vendor.installer.finished');
    }

    /**
     * Perform the actual installation.
     */
    public function performInstallation(Request $request)
    {
        if (sudahInstal()) {
            return redirect('/');
        }

        try {
            // Run migrations
            \Artisan::call('migrate', ['--force' => true]);

            // Run seeders if needed
            \Artisan::call('db:seed', ['--force' => true]);

            // Create installed file
            file_put_contents(
                storage_path('installed'),
                sprintf('%s berhasil DIPASANG pada %s', config('app.name', 'OpenDK'), now())
            );
            
            $migrationOutput = \Artisan::output();

        } catch (\Exception $e) {
            return view('vendor.installer.finished', [
                'error' => $e->getMessage()
            ]);
        }

        return view('vendor.installer.finished', [
            'success' => true,
            'migrationOutput' => $migrationOutput
        ]);
    }
}
