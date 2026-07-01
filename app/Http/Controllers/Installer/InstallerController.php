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
        // Validasi input
        $request->validate([
            'app_name'            => 'required|string|max:255',
            'app_environment'     => 'required|string',
            'app_debug'           => 'required',
            'app_url'             => 'required|url',
            'database_connection' => 'required|string',
            'database_hostname'   => 'required|string',
            'database_port'       => 'required|numeric',
            'database_name'       => 'required|string',
            'database_username'   => 'required|string',
            'database_password'   => 'nullable|string',
        ]);

        // Cek writability file .env
        $envPath = base_path('.env');
        if (file_exists($envPath) && ! is_writable($envPath)) {
            return back()->withErrors(['envConfig' => 'File .env tidak dapat ditulis. Periksa permission file.']);
        }

        // Bangun konten .env
        // Catatan: key-key di bawah sesuai dengan Laravel 11+
        //   - BROADCAST_CONNECTION  (bukan BROADCAST_DRIVER yang lama)
        //   - CACHE_STORE           (bukan CACHE_DRIVER yang lama)
        //   - QUEUE_CONNECTION      (tidak berubah)
        $envConfig = [
            'APP_NAME'  => '"' . $request->app_name . '"',
            'APP_ENV'   => $request->app_environment,
            'APP_KEY'   => 'base64:' . base64_encode(random_bytes(32)),
            'APP_DEBUG' => $request->app_debug ? 'true' : 'false',
            'APP_URL'   => $request->app_url,

            'LOG_CHANNEL'             => 'stack',
            'LOG_DEPRECATIONS_CHANNEL'=> 'null',
            'LOG_LEVEL'               => 'debug',

            'DB_CONNECTION' => $request->database_connection,
            'DB_HOST'       => $request->database_hostname,
            'DB_PORT'       => $request->database_port,
            'DB_DATABASE'   => $request->database_name,
            'DB_USERNAME'   => $request->database_username,
            'DB_PASSWORD'   => $request->database_password ?: '',

            // Laravel 11+ menggunakan BROADCAST_CONNECTION (bukan BROADCAST_DRIVER)
            'BROADCAST_CONNECTION' => $request->broadcast_connection ?? 'log',
            // Laravel 11+ menggunakan CACHE_STORE (bukan CACHE_DRIVER)
            'CACHE_STORE'          => $request->cache_store ?? 'file',
            'FILESYSTEM_DISK'      => 'local',
            'QUEUE_CONNECTION'     => $request->queue_connection ?? 'sync',
            'SESSION_DRIVER'       => $request->session_driver ?? 'file',
            'SESSION_LIFETIME'     => '120',

            'MEMCACHED_HOST' => '127.0.0.1',

            'REDIS_HOST'     => $request->redis_hostname ?? '127.0.0.1',
            'REDIS_PASSWORD' => $request->redis_password ?? 'null',
            'REDIS_PORT'     => $request->redis_port ?? '6379',

            'MAIL_MAILER'       => $request->mail_driver ?? 'smtp',
            'MAIL_HOST'         => $request->mail_host ?? 'mailpit',
            'MAIL_PORT'         => $request->mail_port ?? '1025',
            'MAIL_USERNAME'     => $request->mail_username ?? 'null',
            'MAIL_PASSWORD'     => $request->mail_password ?? 'null',
            'MAIL_ENCRYPTION'   => $request->mail_encryption ?? 'null',
            'MAIL_FROM_ADDRESS' => '"hello@example.com"',
            'MAIL_FROM_NAME'    => '"${APP_NAME}"',

            'AWS_ACCESS_KEY_ID'          => '',
            'AWS_SECRET_ACCESS_KEY'      => '',
            'AWS_DEFAULT_REGION'         => 'us-east-1',
            'AWS_BUCKET'                 => '',
            'AWS_USE_PATH_STYLE_ENDPOINT'=> 'false',

            'PUSHER_APP_ID'      => $request->pusher_app_id ?? '',
            'PUSHER_APP_KEY'     => $request->pusher_app_key ?? '',
            'PUSHER_APP_SECRET'  => $request->pusher_app_secret ?? '',
            'PUSHER_HOST'        => '',
            'PUSHER_PORT'        => '443',
            'PUSHER_SCHEME'      => 'https',
            'PUSHER_APP_CLUSTER' => 'mt1',

            'VITE_PUSHER_APP_KEY'     => '"${PUSHER_APP_KEY}"',
            'VITE_PUSHER_HOST'        => '"${PUSHER_HOST}"',
            'VITE_PUSHER_PORT'        => '"${PUSHER_PORT}"',
            'VITE_PUSHER_SCHEME'      => '"${PUSHER_SCHEME}"',
            'VITE_PUSHER_APP_CLUSTER' => '"${PUSHER_APP_CLUSTER}"',
        ];

        // Convert array ke format .env
        $envContent = '';
        foreach ($envConfig as $key => $value) {
            $envContent .= $key . '=' . $value . "\n";
        }

        // Simpan ke file .env
        file_put_contents($envPath, $envContent);

        // Reload config cache agar konfigurasi baru (terutama DB) langsung terbaca
        try {
            \Artisan::call('config:clear');
            \Artisan::call('cache:clear');
        } catch (\Exception $e) {
            // Tidak fatal — lanjutkan meski cache:clear gagal
        }

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

        // Cek writability file .env
        $envPath = base_path('.env');
        if (file_exists($envPath) && ! is_writable($envPath)) {
            return back()->withErrors(['envConfig' => 'File .env tidak dapat ditulis. Periksa permission file.']);
        }

        // Simpan content langsung dari textarea ke file .env
        file_put_contents($envPath, $request->envConfig);

        // Reload config cache agar konfigurasi baru langsung terbaca
        try {
            \Artisan::call('config:clear');
            \Artisan::call('cache:clear');
        } catch (\Exception $e) {
            // Tidak fatal — lanjutkan meski cache:clear gagal
        }

        return redirect()->route('installer.database')
            ->with('success', 'Konfigurasi environment berhasil disimpan');
    }

    /**
     * Display the database page.
     * Test koneksi DB menggunakan config yang sudah di-reload.
     */
    public function database()
    {
        if (sudahInstal()) {
            return redirect('/');
        }

        // Test database connection
        $canConnect = false;
        $message    = '';

        try {
            // Paksa Laravel membaca ulang config DB dari .env yang baru disimpan
            \DB::purge();
            \DB::reconnect();
            \DB::connection()->getPdo();
            $canConnect = true;
            $message    = 'Koneksi database berhasil!';
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
            // Generate APP_KEY jika belum ada atau masih kosong
            if (empty(config('app.key')) || config('app.key') === 'base64:') {
                \Artisan::call('key:generate', ['--force' => true]);
            }

            // Run migrations
            \Artisan::call('migrate', ['--force' => true]);
            $migrationOutput = \Artisan::output();

            // Run seeders
            \Artisan::call('db:seed', ['--force' => true]);
            $migrationOutput .= \Artisan::output();

            // Buat symlink storage (biarkan jika sudah ada — tidak fatal)
            try {
                \Artisan::call('storage:link', ['--force' => true]);
            } catch (\Exception $e) {
                // Symlink mungkin sudah ada — tidak fatal, lanjutkan
            }

            // Buat file tanda instalasi selesai
            file_put_contents(
                storage_path('installed'),
                sprintf('%s berhasil DIPASANG pada %s', config('app.name', 'OpenDK'), now())
            );

        } catch (\Exception $e) {
            return view('vendor.installer.finished', [
                'error' => $e->getMessage(),
            ]);
        }

        return view('vendor.installer.finished', [
            'success'         => true,
            'migrationOutput' => $migrationOutput,
        ]);
    }
}
