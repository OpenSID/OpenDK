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

namespace App\Http\Controllers\Counter;

use App\Enums\VisitorFilterEnum;
use App\Models\Visitor;
use App\Models\CounterPage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Profil;

class CounterController extends Controller
{
    public function index()
    {
        $page_title = 'Statistik Pengunjung';
        $page_description = 'Jumlah Statistik Pengunjung Website';

        // hitung statistik
        $visitors = [
            'todayVisitors'     => Visitor::stats(VisitorFilterEnum::TODAY),
            'yesterdayVisitors' => Visitor::stats(VisitorFilterEnum::YESTERDAY),
            'weeklyVisitors'    => Visitor::stats(VisitorFilterEnum::THIS_WEEK),
            'monthlyVisitors'   => Visitor::stats(VisitorFilterEnum::THIS_MONTH),
            'yearlyVisitors'    => Visitor::stats(VisitorFilterEnum::THIS_YEAR),
            'totalVisitors'     => Visitor::stats(VisitorFilterEnum::ALL),
        ];

        // request url query parameter 'filter'
        $filter = request()->get('filter', VisitorFilterEnum::TODAY);
        $dailyStats = Visitor::groupedStats($filter);

        $chartData = [
            'labels' => $this->formatLabels($dailyStats, $filter),
            'page_views' => $dailyStats->pluck('page_views')->map(fn ($pv) => (int)$pv)->toArray(),
            'unique_visitors' => $dailyStats->pluck('unique_visitors')->toArray(),
        ];

        // Halaman populer
        $top_pages_visited = Visitor::getTopPagesVisited();

        return view('counter.index', compact(
            'page_title',
            'page_description',
            'visitors',
            'chartData',
            'dailyStats',
            'top_pages_visited'
        ));
    }

    private function formatLabels($dailyStats, $filter)
    {
        if ($filter === VisitorFilterEnum::THIS_YEAR) {
            return $dailyStats->pluck('date')->map(function ($date) {
                return Carbon::createFromFormat('Y-m', $date)->translatedFormat('F Y');
            })->toArray();
        } elseif ($filter === VisitorFilterEnum::ALL) {
            return $dailyStats->pluck('date')->map(function ($date) {
                return Carbon::createFromFormat('Y', $date)->translatedFormat('Y');
            })->toArray();
        } else {
            return $dailyStats->pluck('date')->map(function ($date) {
                return Carbon::createFromFormat('Y-m-d', $date)->translatedFormat('d F Y');
            })->toArray();
        }
    }

    public function cetak()
    {
        $yearlyVisitors = Visitor::groupedStats(VisitorFilterEnum::ALL);

        // Halaman populer
        $top_pages_visited = Visitor::getTopPagesVisited();

        return view('counter.cetak', compact(
            'yearlyVisitors',
            'top_pages_visited'
        ));
    }

    public function exportExcel()
    {
        $yearlyVisitors = Visitor::groupedStats(VisitorFilterEnum::ALL);
        $top_pages_visited = Visitor::getTopPagesVisited();

        $profile = Profil::first();
        $profileArray = [
            'nama_kabupaten' => $profile->nama_kabupaten,
            'nama_kecamatan' => $profile->nama_kecamatan,
        ];

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\CounterVisitorExport($yearlyVisitors, $top_pages_visited, $profileArray), 'Cetak Statistik Pengunjung.xlsx');
    }

    protected function geTopPage()
    {
        $sql = DB::table('das_counter_page_visitor')
            ->selectRaw('page_id, COUNT(*) AS total')
            ->groupBy('page_id')
            ->orderBy('total', 'desc')
            ->get();

        $data = [];
        foreach ($sql as $item) {
            $page = CounterPage::findOrFail($item->page_id);
            $data[] = [
                'id' => $item->page_id,
                'url' => route($page->page),
                'total' => $item->total,
            ];
        }

        return $data;
    }
}
