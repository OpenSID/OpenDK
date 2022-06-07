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

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataDesa extends Model
{
    protected $table = 'das_data_desa';

    protected $fillable = [
        'desa_id',
        'nama',
        'website',
        'luas_wilayah',
        'path'
    ];

    /**
     * Getter untuk menambahkan url ke /feed.
     *
     * @return string
     */
    public function getWebsiteUrlFeedAttribute()
    {
        $desa = [
            'desa_id' => $this->desa_id,
            'nama'    => ucwords($this->sebutan_desa . ' ' . $this->nama),
            'website' => $this->website . 'index.php/feed'
        ];
        return $desa;
    }

    public function scopeNama($query, $value)
    {
        return $query->where('nama', str_replace('-', ' ', $value));
    }

    /**
     * Scope query untuk website desa.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWebsiteUrl($query)
    {
        return $query->whereNotNull('website');
    }

    public function imunisasi()
    {
        return $this->hasMany(Imunisasi::class, 'desa_id', 'desa_id');
    }

    public function akiakb()
    {
        return $this->hasMany(AkiAkb::class, 'desa_id', 'desa_id');
    }

    public function anggarandesa()
    {
        return $this->hasMany(AnggaranDesa::class, 'desa_id', 'desa_id');
    }

    public function epidemipenyakit()
    {
        return $this->hasMany(EpidemiPenyakit::class, 'desa_id', 'desa_id');
    }

    public function fasilitasPAUD()
    {
        return $this->hasMany(FasilitasPAUD::class, 'desa_id', 'desa_id');
    }

    public function laporanapbdes()
    {
        return $this->hasMany(LaporanApbdes::class, 'desa_id', 'desa_id');
    }

    public function laporanpenduduk()
    {
        return $this->hasMany(LaporanPenduduk::class, 'desa_id', 'desa_id');
    }

    public function putussekolah()
    {
        return $this->hasMany(PutusSekolah::class, 'desa_id', 'desa_id');
    }

    public function tingkatpendidikan()
    {
        return $this->hasMany(TingkatPendidikan::class, 'desa_id', 'desa_id');
    }

    public function toiletsanitasi()
    {
        return $this->hasMany(ToiletSanitasi::class, 'desa_id', 'desa_id');
    }

    public function keluarga()
    {
        return $this->hasMany(Keluarga::class, 'desa_id', 'desa_id');
    }
}
