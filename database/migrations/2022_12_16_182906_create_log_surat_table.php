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

use App\Enums\LogVerifikasiSurat;
use App\Enums\Status;
use App\Enums\StatusSurat;
use App\Enums\StatusVerifikasiSurat;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogSuratTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('das_log_surat', function (Blueprint $table) {
            $table->id();
            $table->char('desa_id', 13);
            $table->char('nik', 16);
            $table->unsignedInteger('pengurus_id');
            $table->date('tanggal');
            $table->string('nomor', 255)->unique();
            $table->string('nama', 100);
            $table->string('file', 255);
            $table->text('keterangan')->nullable()->default(null);
            $table->tinyInteger('log_verifikasi')->default(LogVerifikasiSurat::Operator);
            $table->tinyInteger('verifikasi_operator')->default(StatusVerifikasiSurat::MenungguVerifikasi);
            $table->tinyInteger('verifikasi_sekretaris')->default(StatusVerifikasiSurat::TidakAktif);
            $table->tinyInteger('verifikasi_camat')->default(StatusVerifikasiSurat::MenungguVerifikasi);
            $table->boolean('status_tte')->default(Status::Aktif);
            $table->tinyInteger('status')->default(StatusSurat::Permohonan);
            $table->timestamps();
            $table->softDeletes('deleted_at', 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('das_log_surat');
    }
}
