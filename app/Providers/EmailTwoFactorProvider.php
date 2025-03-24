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

namespace App\Providers;

use Carbon\Carbon;
use MichaelDzjap\TwoFactorAuth\Contracts\TwoFactorProvider;
use MichaelDzjap\TwoFactorAuth\Exceptions\TokenExpiredException;
use MichaelDzjap\TwoFactorAuth\Providers\BaseProvider;

class EmailTwoFactorProvider extends BaseProvider implements TwoFactorProvider
{
    /**
     * {@inheritdoc}
     */
    public function register($user): void
    {
        //
    }

    /**
     * {@inheritdoc}
     */
    public function unregister($user)
    {
        //
    }

    /**
     * {@inheritdoc}
     */
    public function verify($user, string $token)
    {
        $savedToken = $user->twoFactorAuth;
        if (Carbon::createFromFormat('Y-m-d H:i:s',$savedToken->expired_at)->isPast()) {
            throw new TokenExpiredException();
        }
        
        return $savedToken->id == $token;
    }
}
