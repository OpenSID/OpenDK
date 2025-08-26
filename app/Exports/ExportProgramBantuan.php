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

use App\Models\Program;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportProgramBantuan implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected array $params;

    public function __construct($params = [])
    {
        $this->params = $params;
    }

    /**
     * Ambil koleksi data program bantuan.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Ambil data dari database lokal dengan filter desa jika ada
        $desa_id = $this->params['desa_id'] ?? null;

        $query = Program::with(['desa', 'pesertas']);

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
            'Nama Program',
            'Desa',
            'Masa Berlaku',
            'Sasaran',
            'Tanggal Dibuat',
            'Tanggal Diperbarui',
        ];
    }

    /**
     * Mapping data untuk setiap baris.
     *
     * @param $program
     * @return array
     */
    public function map($program): array
    {
        $masaBerlaku = $program->start_date && $program->end_date ?
            Carbon::parse($program->start_date)->format('Y-m-d') . ' - ' . Carbon::parse($program->end_date)->format('Y-m-d') : '-';
        $sasaran = [1 => 'Penduduk/Perorangan', 2 => 'Keluarga-KK'];

        return [
            $program->id,
            $program->nama,
            $program->desa->nama ?? '',
            $masaBerlaku,
            $sasaran[$program->sasaran] ?? '',
            $program->created_at ? $program->created_at->format('d/m/Y H:i:s') : '',
            $program->updated_at ? $program->updated_at->format('d/m/Y H:i:s') : '',
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
            'A:K' => ['font' => ['size' => 10]],
        ];
    }
}
