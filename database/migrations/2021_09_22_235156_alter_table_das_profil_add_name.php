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
 * @package	    OpenDK
 * @author	    Tim Pengembang OpenDesa
 * @copyright	Hak Cipta 2017 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    	http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link	    https://github.com/OpenSID/opendk
 */

use App\Models\Profil;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterTableDasProfilAddName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('das_profil', function (Blueprint $table) {
            $table->string('nama_provinsi', 255)->after('provinsi_id')->nullable();
            $table->string('nama_kabupaten', 255)->after('kabupaten_id')->nullable();
            $table->string('nama_kecamatan', 255)->after('kecamatan_id')->nullable();
            $table->string('kecamatan_id', 8)->nullable()->change();
        });

        // Isi data dari pengguna versi sebelumnya
        if ($profil = Profil::first()) {
            $profil->nama_provinsi = DB::table('ref_wilayah')->where('kode', $profil->provinsi_id)->first()->nama;
            $profil->nama_kabupaten = DB::table('ref_wilayah')->where('kode', $profil->kabupaten_id)->first()->nama;
            $profil->nama_kecamatan = DB::table('ref_wilayah')->where('kode', $profil->kecamatan_id)->first()->nama;
            $profil->update();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function ($table) {
            $table->dropColumn('nama_provinsi');
            $table->dropColumn('nama_kabupaten');
            $table->dropColumn('nama_kecamatan');
        });
    }
}
