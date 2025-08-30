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

namespace App\Exports;

use App\Models\SuplemenTerdata;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportSuplemenTerdata implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $suplemenId;
    protected $filters;

    public function __construct($suplemenId = null, array $filters = [])
    {
        $this->suplemenId = $suplemenId;
        $this->filters = $filters;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = SuplemenTerdata::with(['suplemen', 'penduduk.desa']);

        // Filter by suplemen if provided
        if ($this->suplemenId) {
            $query->where('suplemen_id', $this->suplemenId);
        }

        // Apply additional filters if provided
        if (!empty($this->filters['desa'])) {
            $query->whereHas('penduduk', function ($q) {
                $q->where('desa_id', $this->filters['desa']);
            });
        }

        if (!empty($this->filters['nama_penduduk'])) {
            $query->whereHas('penduduk', function ($q) {
                $q->where('nama', 'like', '%' . $this->filters['nama_penduduk'] . '%');
            });
        }

        return $query->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Nama Suplemen',
            'NIK',
            'Nama Penduduk',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Umur',
            'Alamat',
            'Desa',
            'Keterangan Suplemen',
            'Tanggal Dibuat',
            'Tanggal Diperbarui',
        ];
    }

    /**
     * @param mixed $suplemenTerdata
     * @return array
     */
    public function map($suplemenTerdata): array
    {
        $penduduk = $suplemenTerdata->penduduk;
        $jenisKelamin = ['1' => 'Laki-laki', '2' => 'Perempuan'];

        return [
            $suplemenTerdata->id,
            $suplemenTerdata->suplemen->nama ?? 'Tidak Ada',
            $penduduk->nik ?? 'Tidak Ada',
            $penduduk->nama ?? 'Tidak Ada',
            $jenisKelamin[$penduduk->sex ?? 1] ?? 'Tidak Diketahui',
            $penduduk->tempat_lahir ?? 'Tidak Ada',
            $penduduk->tanggal_lahir ?? 'Tidak Ada',
            $penduduk->umur ?? 'Tidak Ada',
            $penduduk->alamat ?? 'Tidak Ada',
            $penduduk->desa->nama ?? 'Tidak Ada',
            $suplemenTerdata->keterangan ?? 'Tidak Ada',
            $suplemenTerdata->created_at ? $suplemenTerdata->created_at->format('Y-m-d H:i:s') : '',
            $suplemenTerdata->updated_at ? $suplemenTerdata->updated_at->format('Y-m-d H:i:s') : '',
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => true]],

            // Set auto width for all columns
            'A:M' => ['alignment' => ['wrapText' => true]],
        ];
    }
}
