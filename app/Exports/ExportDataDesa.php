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

use App\Models\DataDesa;
use App\Services\DesaService;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportDataDesa implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected bool $gabungan;
    protected array $params;
    protected DesaService $desaService;

    public function __construct($gabungan, $params = [])
    {
        $this->gabungan = $gabungan;
        $this->params = $params;
        $this->desaService = new DesaService();
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        if ($this->gabungan) {
            return $this->desaService->listDesa(true);
        } else {
            return DataDesa::all();
        }
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Kode Desa',
            'Nama Desa',
            'Sebutan Desa',
            'Website',
            'Luas Wilayah (km²)',
            'Tanggal Dibuat',
            'Tanggal Diperbarui',
        ];
    }

    /**
     * @param mixed $desa
     * @return array
     */
    public function map($desa): array
    {
        if ($this->gabungan) {
            return [
                $desa->id ?? '',
                $desa->desa_id ?? '',
                $desa->nama ?? '',
                $desa->sebutan_desa ?? 'Desa',
                $desa->website ?? '',
                $desa->luas_wilayah ?? 0,
                '', // created_at tidak tersedia di API gabungan
                '', // updated_at tidak tersedia di API gabungan
            ];
        } else {
            return [
                $desa->id,
                $desa->desa_id,
                $desa->nama,
                $desa->sebutan_desa ?? 'Desa',
                $desa->website,
                $desa->luas_wilayah ?? 0,
                $desa->created_at ? $desa->created_at->format('d/m/Y H:i:s') : '',
                $desa->updated_at ? $desa->updated_at->format('d/m/Y H:i:s') : '',
            ];
        }
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style header row
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4'],
                ],
            ],
        ];
    }
}
