<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright  Hak Cipta 2017 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

namespace App\Exports;

use App\Models\DataDesa;
use App\Models\Penduduk;
use App\Models\SuplemenTerdata;
use App\Services\PendudukService;
use Illuminate\Support\Collection;

class ExportSuplemenTerdataGabungan extends ExportSuplemenTerdata
{
    /**
     * @param  array<int, int|string>  $pendudukGabunganIds
     */
    public function __construct($suplemenId = null, array $filters = [], array $pendudukGabunganIds = [])
    {
        parent::__construct($suplemenId, $filters);
        $this->pendudukGabunganIds = array_map('intval', $pendudukGabunganIds);
    }

    public function collection(): Collection
    {
        $collection = parent::collection();

        if ($collection->isEmpty() || empty($this->pendudukGabunganIds)) {
            return $collection;
        }

        $missingIds = $this->pendudukGabunganIds;

        foreach ($collection as $row) {
            if ($row->penduduk_id_gabungan) {
                $key = (int) $row->penduduk_id_gabungan;

                if (isset($missingIds[$key])) {
                    unset($missingIds[$key]);
                }
            }
        }

        if (empty($missingIds)) {
            return $collection;
        }

        $resolved = (new PendudukService())->pendudukGabunganByIds(array_values($missingIds));

        if ($resolved->isEmpty()) {
            return $collection;
        }

        $resolvedMap = [];
        foreach ($resolved as $item) {
            if (! is_array($item)) {
                continue;
            }

            $penduduk = $this->mapGabunganPenduduk($item);
            $resolvedMap[(int) ($item['id'] ?? 0)] = $penduduk;
        }

        return $collection->map(function (SuplemenTerdata $row) use ($resolvedMap) {
            if ($row->penduduk_id_gabungan && ! $row->penduduk) {
                $penduduk = $resolvedMap[(int) $row->penduduk_id_gabungan] ?? null;

                if ($penduduk) {
                    $row->setRelation('penduduk', $penduduk);
                }
            }

            return $row;
        });
    }

    private function mapGabunganPenduduk(array $item): Penduduk
    {
        $attributes = $item['attributes'] ?? [];

        $penduduk = new Penduduk();
        $penduduk->forceFill([
            'id' => $item['id'] ?? null,
            'no_kk' => data_get($attributes, 'keluarga.no_kk') ?? $attributes['no_kk'] ?? null,
            'nik' => $attributes['nik'] ?? null,
            'nama' => $attributes['nama'] ?? null,
            'tempat_lahir' => $attributes['tempatlahir'] ?? $attributes['tempat_lahir'] ?? null,
            'tanggal_lahir' => $attributes['tanggallahir'] ?? $attributes['tanggal_lahir'] ?? null,
            'umur' => $attributes['umur'] ?? null,
            'sex' => $attributes['sex'] ?? null,
            'alamat' => $attributes['alamat_sekarang'] ?? $attributes['alamat'] ?? null,
        ]);

        $penduduk->setRelation('desa', new DataDesa([
            'nama' => data_get($attributes, 'config.nama_desa') ?? $attributes['nama_desa'] ?? null,
            'desa_id' => data_get($attributes, 'config.kode_desa') ?? $attributes['kode_desa'] ?? null,
        ]));

        return $penduduk;
    }
}
