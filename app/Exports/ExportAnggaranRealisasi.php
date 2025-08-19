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

use App\Models\AnggaranRealisasi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportAnggaranRealisasi implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /**
     * Ambil koleksi data anggaran realisasi.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Ambil semua data anggaran realisasi
        return AnggaranRealisasi::all();
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
            'Total Anggaran',
            'Total Belanja',
            'Belanja Pegawai',
            'Belanja Barang & Jasa',
            'Belanja Modal',
            'Belanja Tidak Langsung',
            'Bulan',
            'Tahun',
            'Tanggal Dibuat',
            'Tanggal Diperbarui',
        ];
    }

    /**
     * Mapping data untuk setiap baris.
     *
     * @param $anggaranRealisasi
     * @return array
     */
    public function map($anggaranRealisasi): array
    {
        return [
            $anggaranRealisasi->id,
            number_format($anggaranRealisasi->total_anggaran, 0, ',', '.'),
            number_format($anggaranRealisasi->total_belanja, 0, ',', '.'),
            number_format($anggaranRealisasi->belanja_pegawai, 0, ',', '.'),
            number_format($anggaranRealisasi->belanja_barang_jasa, 0, ',', '.'),
            number_format($anggaranRealisasi->belanja_modal, 0, ',', '.'),
            number_format($anggaranRealisasi->belanja_tidak_langsung, 0, ',', '.'),
            months_list()[$anggaranRealisasi->bulan] ?? $anggaranRealisasi->bulan,
            $anggaranRealisasi->tahun,
            $anggaranRealisasi->created_at ? $anggaranRealisasi->created_at->format('d/m/Y H:i:s') : '',
            $anggaranRealisasi->updated_at ? $anggaranRealisasi->updated_at->format('d/m/Y H:i:s') : '',
        ];
    }

    /**
     * Styling untuk Excel worksheet.
     *
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style untuk header
            1 => ['font' => ['bold' => true]],

            // Auto-size untuk semua kolom
            'A:L' => ['font' => ['size' => 10]],
        ];
    }
}
