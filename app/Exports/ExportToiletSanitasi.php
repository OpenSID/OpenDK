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

use App\Models\ToiletSanitasi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportToiletSanitasi implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /**
     * Mengambil koleksi data Toilet Sanitasi untuk ekspor
     * 
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Mengambil semua data Toilet Sanitasi tanpa filter atau limit
        return ToiletSanitasi::with('desa')->get();
    }

    /**
     * Header kolom untuk file Excel
     * 
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Nama Desa',
            'Kode Desa',
            'Jumlah Toilet',
            'Jumlah Sanitasi',
            'Bulan',
            'Tahun',
            'Tanggal Dibuat',
            'Tanggal Diperbarui',
        ];
    }

    /**
     * Memetakan data untuk setiap baris Excel
     * 
     * @param mixed $toiletSanitasi
     * @return array
     */
    public function map($toiletSanitasi): array
    {
        $bulanList = months_list();

        return [
            $toiletSanitasi->id,
            $toiletSanitasi->desa->nama ?? 'Tidak Diketahui',
            $toiletSanitasi->desa_id,
            $toiletSanitasi->toilet,
            $toiletSanitasi->sanitasi,
            $bulanList[$toiletSanitasi->bulan] ?? 'Tidak Diketahui',
            $toiletSanitasi->tahun,
            $toiletSanitasi->created_at ? $toiletSanitasi->created_at->format('Y-m-d H:i:s') : '',
            $toiletSanitasi->updated_at ? $toiletSanitasi->updated_at->format('Y-m-d H:i:s') : '',
        ];
    }

    /**
     * Styling untuk file Excel
     * 
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style baris pertama sebagai header dengan teks tebal
            1 => ['font' => ['bold' => true]],

            // Set auto width untuk semua kolom
            'A:I' => ['alignment' => ['wrapText' => true]],
        ];
    }
}
