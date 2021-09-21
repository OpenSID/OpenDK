<?php

/*
 * File ini bagian dari:
 *
 * PBB Desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2016 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright	Hak Cipta 2016 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    	http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link	    https://github.com/OpenSID/opendk
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataUmum extends Model
{
    // Attributes
    protected $table = 'das_data_umum';

    protected $fillable = [
        'kecamatan_id',
        'tipologi',
        'ketinggian',
        'sumber_luas_wilayah',
        'luas_wilayah',
        'bts_wil_utara',
        'bts_wil_timur',
        'bts_wil_selatan',
        'bts_wil_barat',
        'jml_puskesmas',
        'jml_puskesmas_pembantu',
        'jml_posyandu',
        'jml_pondok_bersalin',
        'jml_paud',
        'jml_sd',
        'jml_smp',
        'jml_sma',
        'jml_masjid_besar',
        'jml_mushola',
        'jml_gereja',
        'jml_pasar',
        'jml_balai_pertemuan',
        'embed_peta',
    ];

    protected $appends = ['luas_wilayah_dari_data_desa'];

    public function kecamatan()
    {
        return $this->belongsTo(Wilayah::class, 'kecamatan_id', 'kode');
    }

    public function getLuasWilayahValueAttribute()
    {
        return $this->sumber_luas_wilayah==1 ? $this->luas_wilayah : \DB::table('das_data_desa')->sum('luas_wilayah');
    }

    public function getLuasWilayahDariDataDesaAttribute()
    {
        return \DB::table('das_data_desa')->sum('luas_wilayah');
    }
}
