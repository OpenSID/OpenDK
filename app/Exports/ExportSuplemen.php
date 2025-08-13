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

use App\Models\Suplemen;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportSuplemen implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Suplemen::withCount('terdata');

        // Apply filters if provided
        if (!empty($this->filters['nama'])) {
            $query->where('nama', 'like', '%' . $this->filters['nama'] . '%');
        }

        if (!empty($this->filters['sasaran'])) {
            $query->where('sasaran', $this->filters['sasaran']);
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
            'Slug',
            'Sasaran',
            'Keterangan',
            'Jumlah Terdata',
            'Tanggal Dibuat',
            'Tanggal Diperbarui',
        ];
    }

    /**
     * @param mixed $suplemen
     * @return array
     */
    public function map($suplemen): array
    {
        $sasaran = ['1' => 'Penduduk', '2' => 'Keluarga/KK'];

        return [
            $suplemen->id,
            $suplemen->nama,
            $suplemen->slug,
            $sasaran[$suplemen->sasaran] ?? 'Tidak Diketahui',
            $suplemen->keterangan,
            $suplemen->terdata_count,
            $suplemen->created_at ? $suplemen->created_at->format('Y-m-d H:i:s') : '',
            $suplemen->updated_at ? $suplemen->updated_at->format('Y-m-d H:i:s') : '',
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
            'A:H' => ['alignment' => ['wrapText' => true]],
        ];
    }
}
