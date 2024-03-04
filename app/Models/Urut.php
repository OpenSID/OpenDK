<?php
/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright  Hak Cipta 2017 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class Urut extends Model
{
    private $tabel;
    private $kolomId;

    public function __construct($tabel, $kolomId = 'id')
    {
        parent::__construct();
        $this->tabel = $tabel;
        $this->kolomId = $kolomId;
    }

    public function urutMax($subset = [])
    {
        return DB::table($this->tabel)
            ->where($subset)
            ->max('urut');
    }

    private function urutSemua($subset = [])
    {
        $urutDuplikat = DB::table($this->tabel)
            ->select('urut', DB::raw('COUNT(*) as c'))
            ->where($subset)
            ->groupBy('urut')
            ->havingRaw('c > 1')
            ->get();

        $belumDiurut = DB::table($this->tabel)
            ->where($subset)
            ->whereNull('urut')
            ->first();

        $daftar = [];
        if ($urutDuplikat->isNotEmpty() || $belumDiurut) {
            $daftar = DB::table($this->tabel)
                ->select($this->kolomId)
                ->where($subset)
                ->orderBy('urut')
                ->get();
        }

        foreach ($daftar as $index => $item) {
            DB::table($this->tabel)
                ->where($this->kolomId, $item->{$this->kolomId})
                ->update(['urut' => $index + 1]);
        }
    }

    public function urut($id, $arah, $subset = [])
    {
        $this->urutSemua($subset);
        $unsur1 = DB::table($this->tabel)
            ->where($this->kolomId, $id)
            ->first();

        $daftar = DB::table($this->tabel)
            ->select($this->kolomId, 'urut')
            ->where($subset)
            ->orderBy('urut')
            ->get()
            ->toArray();

        return $this->urutDaftar($id, $arah, $daftar, $unsur1);
    }

    private function urutDaftar($id, $arah, $daftar, $unsur1)
    {
        $currentIndex = 0;
        foreach ($daftar as $index => $item) {
            if ($item->{$this->kolomId} == $id) {
                $currentIndex = $index;
                break;
            }
        }

        $totalItems = count($daftar);
        if ($arah == 1) {
            if ($currentIndex >= $totalItems - 1) {
                return;
            }
            $unsur2 = $daftar[$currentIndex + 1];
        }
        if ($arah == 2) {
            if ($currentIndex <= 0) {
                return;
            }
            $unsur2 = $daftar[$currentIndex - 1];
        }

        // Tukar urutan
        DB::table($this->tabel)
            ->where($this->kolomId, $unsur2->{$this->kolomId})
            ->update(['urut' => $unsur1->urut]);

        DB::table($this->tabel)
            ->where($this->kolomId, $unsur1->{$this->kolomId})
            ->update(['urut' => $unsur2->urut]);

        return (int) $unsur2->urut;
    }
}
