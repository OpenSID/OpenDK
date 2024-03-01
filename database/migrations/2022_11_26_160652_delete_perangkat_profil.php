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

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class DeletePerangkatProfil extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('das_profil', function (Blueprint $table) {
            $table->dropColumn('nama_camat');
            $table->dropColumn('sekretaris_camat');
            $table->dropColumn('kepsek_pemerintahan_umum');
            $table->dropColumn('kepsek_kesejahteraan_masyarakat');
            $table->dropColumn('kepsek_pemberdayaan_masyarakat');
            $table->dropColumn('kepsek_pelayanan_umum');
            $table->dropColumn('kepsek_trantib');
            $table->dropColumn('foto_kepala_wilayah');
        });

        Storage::deleteDirectory('public/profil/pegawai');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('das_profil', function (Blueprint $table) {
            $table->string('nama_camat', 150)->nullable()->after('dasar_pembentukan');
            $table->string('sekretaris_camat', 150)->nullable()->after('nama_camat');
            $table->string('kepsek_pemerintahan_umum', 150)->nullable()->after('sekretaris_camat');
            $table->string('kepsek_kesejahteraan_masyarakat', 150)->nullable()->after('kepsek_pemerintahan_umum');
            $table->string('kepsek_pemberdayaan_masyarakat', 150)->nullable()->after('kepsek_kesejahteraan_masyarakat');
            $table->string('kepsek_pelayanan_umum', 150)->nullable()->after('kepsek_pemberdayaan_masyarakat');
            $table->string('kepsek_trantib', 150)->nullable()->after('kepsek_pelayanan_umum');
            $table->longText('foto_kepala_wilayah')->nullable()->after('misi');
        });
    }
}
