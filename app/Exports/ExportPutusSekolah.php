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

use App\Models\PutusSekolah;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportPutusSekolah implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    /**
     * Ambil koleksi data putus sekolah.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Ambil semua data putus sekolah dengan relasi desa
        return PutusSekolah::with('desa')->get();
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
            'Siswa PAUD',
            'Anak Usia PAUD',
            'Siswa SD',
            'Anak Usia SD',
            'Siswa SMP',
            'Anak Usia SMP',
            'Siswa SMA',
            'Anak Usia SMA',
            'Semester',
            'Tahun',
            'Tanggal Dibuat',
            'Tanggal Diperbarui',
        ];
    }

    /**
     * Mapping data untuk setiap baris.
     *
     * @param $putusSekolah
     * @return array
     */
    public function map($putusSekolah): array
    {
        return [
            $putusSekolah->id,
            $putusSekolah->desa->nama ?? '',
            $putusSekolah->siswa_paud,
            $putusSekolah->anak_usia_paud,
            $putusSekolah->siswa_sd,
            $putusSekolah->anak_usia_sd,
            $putusSekolah->siswa_smp,
            $putusSekolah->anak_usia_smp,
            $putusSekolah->siswa_sma,
            $putusSekolah->anak_usia_sma,
            $putusSekolah->semester,
            $putusSekolah->tahun,
            $putusSekolah->created_at ? $putusSekolah->created_at->format('d/m/Y H:i:s') : '',
            $putusSekolah->updated_at ? $putusSekolah->updated_at->format('d/m/Y H:i:s') : '',
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
            'A:N' => ['font' => ['size' => 10]],
        ];
    }
}
