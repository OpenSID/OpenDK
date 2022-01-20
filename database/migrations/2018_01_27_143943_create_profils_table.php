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

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('das_profil', function (Blueprint $table) {
            $table->increments('id');
            $table->char('provinsi_id', 2)->nullable(true);
            $table->char('kabupaten_id', 4)->nullable(true);
            $table->char('kecamatan_id', 7);
            $table->string('alamat', 200);
            $table->char('kode_pos', 12);
            $table->char('telepon', 15)->nullable(true);
            $table->string('email', 255)->nullable(true);
            $table->integer('tahun_pembentukan')->nullable(true);
            $table->string('dasar_pembentukan', 20)->nullable(true);
            $table->string('nama_camat', 150)->nullable(true);
            $table->string('sekretaris_camat', 150)->nullable(true);
            $table->string('kepsek_pemerintahan_umum', 150)->nullable(true);
            $table->string('kepsek_kesejahteraan_masyarakat', 150)->nullable(true);
            $table->string('kepsek_pemberdayaan_masyarakat', 150)->nullable(true);
            $table->string('kepsek_pelayanan_umum', 150)->nullable(true);
            $table->string('kepsek_trantib', 150)->nullable(true);
            $table->string('file_struktur_organisasi', 255)->nullable(true);
            $table->string('file_logo', 255)->nullable();
            $table->longText('visi')->nullable(true);
            $table->longText('misi')->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('das_profil');
    }
}
