<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright  Hak Cipta 2017 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

namespace App\Http\Middleware;

use function array_diff_key;
use Closure;
use Form;

use Illuminate\Http\Request;
use function session;
use function sprintf;

class CustomForms
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Get the errors from session...
        $errors = session('errors');

        // Extends Form::model() to create a new form opening
        // with configured default options...
        Form::macro('modelHorizontal', function ($model, $options = []) {
            $options = array_diff_key([
                'class'        => 'form-horizontal',
                'autocomplete' => 'off',
            ], $options) + $options;

            return Form::model($model, $options);
        });

        // If the field has errors, then add 'has-error' css class to the given field...
        Form::macro('hasError', function ($field) use ($errors) {
            if ($errors && $errors->has($field)) {
                return ' has-error';
            }

            return;
        });

        // Generate error message if the given field has errors...
        Form::macro('errorMsg', function ($field) use ($errors) {
            if ($errors && $errors->has($field)) {
                return sprintf('<p class="help-block text-danger">%s</p>', $errors->first($field));
            }

            return;
        });

        return $next($request);
    }
}
