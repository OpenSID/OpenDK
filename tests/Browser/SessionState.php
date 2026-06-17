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

namespace Tests\Browser;

use App\Models\User;

final class SessionState
{
    private const STORAGE_PATH = __DIR__ . '/.session_state.json';

    /**
     * Login user default untuk testing.
     */
    public static function loginAdminUser(): User
    {
        $user = User::first();
        if (!$user) {
            $user = User::factory()->create();
        }
        
        return $user;
    }

    /**
     * Kunjungi URL di browser dengan membawa session cookie
     * sehingga bypass form login dan request login.
     *
     * @param User $user
     * @param string $url
     * @param array $options
     * @return \Pest\Browser\Api\Webpage
     */
    public static function loginAndNavigate(
        User $user,
        string $url,
        array $options = []
    ) {
        $options['headers'] = array_merge(
            $options['headers'] ?? [],
            ['Cookie' => self::getSessionCookie($user)]
        );

        return visit($url, $options);
    }

    /**
     * Dapatkan cookie session secara internal dari test application
     * lalu simpan ke storage agar tidak perlu fetch berulang-ulang.
     *
     * @param User $user
     * @return string
     */
    public static function getSessionCookie(User $user): string
    {
        if (file_exists(self::STORAGE_PATH)) {
            $data = json_decode(file_get_contents(self::STORAGE_PATH), true);
            if (isset($data['user_id']) && $data['user_id'] === $user->id) {
                return $data['cookie'];
            }
        }

        // Panggil endpoint login quick bypass milik test runner
        // Ini akan mengembalikan session cookie di response header.
        $response = test()->get('/_pest/login/' . $user->id);
        
        $cookieName = config('session.cookie');
        $cookieValue = '';
        
        foreach ($response->headers->getCookies() as $cookie) {
            if ($cookie->getName() === $cookieName) {
                $cookieValue = $cookie->getValue();
                break;
            }
        }

        $cookieString = "{$cookieName}={$cookieValue}";

        file_put_contents(self::STORAGE_PATH, json_encode([
            'user_id' => $user->id,
            'cookie'  => $cookieString,
        ], JSON_PRETTY_PRINT));

        return $cookieString;
    }

    /**
     * Bersihkan session state
     */
    public static function clear(): void
    {
        if (file_exists(self::STORAGE_PATH)) {
            unlink(self::STORAGE_PATH);
        }
    }
}
