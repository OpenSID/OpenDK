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

use App\Models\AnggaranDesa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportAnggaranDesa implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected array $params;

    public function __construct($params = [])
    {
        $this->params = $params;
    }

    /**
     * Ambil koleksi data anggaran desa.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Ambil data dari database lokal dengan filter desa jika ada
        $desa_id = $this->params['desa_id'] ?? null;

        $query = AnggaranDesa::with(['desa']);

        // Hanya tambahkan filter jika ada parameter desa dan bukan 'Semua'
        if ($desa_id && $desa_id !== 'Semua') {
            $query->where('desa_id', $desa_id);
        }

        return $query->get();
    }

    /**
     * Definisi header kolom untuk export Excel.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Desa',
            'No Akun',
            'Nama Akun',
            'Jumlah',
            'Bulan',
            'Tahun',
            'Tanggal Dibuat',
            'Tanggal Diperbarui',
        ];
    }

    /**
     * Mapping data untuk setiap baris.
     *
     * @param $row
     * @return array
     */
    public function map($row): array
    {
        return [
            $row->id,
            $row->desa->nama ?? '',
            $row->no_akun,
            $row->nama_akun,
            number_format($row->jumlah, 2),
            isset(months_list()[$row->bulan]) ? months_list()[$row->bulan] : $row->bulan,
            $row->tahun,
            $row->created_at ? $row->created_at->format('Y-m-d H:i:s') : '',
            $row->updated_at ? $row->updated_at->format('Y-m-d H:i:s') : '',
        ];
    }

    /**
     * Styling untuk worksheet Excel.
     *
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1 => ['font' => ['bold' => true]],
            // Set auto width for all columns
            'A:I' => ['font' => ['size' => 10]],
        ];
    }
}
