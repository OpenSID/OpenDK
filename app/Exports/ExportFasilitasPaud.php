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

use App\Models\FasilitasPAUD;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportFasilitasPaud implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /**
     * Ambil koleksi data fasilitas PAUD.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Ambil semua data fasilitas PAUD dengan relasi desa
        return FasilitasPAUD::with('desa')->get();
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
            'Nama Desa',
            'Jumlah PAUD/RA',
            'Jumlah Guru PAUD/RA',
            'Jumlah Siswa PAUD/RA',
            'Tahun',
            'Semester',
            'Tanggal Dibuat',
            'Tanggal Diperbarui',
        ];
    }

    /**
     * Mapping data untuk setiap baris.
     *
     * @param $fasilitasPaud
     * @return array
     */
    public function map($fasilitasPaud): array
    {
        return [
            $fasilitasPaud->id,
            $fasilitasPaud->desa->nama ?? '',
            $fasilitasPaud->jumlah_paud,
            $fasilitasPaud->jumlah_guru_paud,
            $fasilitasPaud->jumlah_siswa_paud,
            $fasilitasPaud->tahun,
            $fasilitasPaud->semester,
            $fasilitasPaud->created_at ? $fasilitasPaud->created_at->format('d/m/Y H:i:s') : '',
            $fasilitasPaud->updated_at ? $fasilitasPaud->updated_at->format('d/m/Y H:i:s') : '',
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
            'A:I' => ['font' => ['size' => 10]],
        ];
    }
}
