<?php

namespace App\Http\Controllers\Counter;

use App\Http\Controllers\Controller;
use App\Models\CounterPage;
use function compact;

use Illuminate\Support\Facades\DB;
use function route;
use function view;

class CounterController extends Controller
{
    public function index()
    {
        $page_title       = 'Statistik Pengunjung';
        $page_description = 'Jumlah Statistik Pengunjung Website';
        $top_pages        = $this->geTopPage();
        return view('counter.index', compact('page_title', 'page_description', 'top_pages'));
    }

    protected function geTopPage()
    {
        $data = null;
        $sql  = DB::table('das_counter_page_visitor')
            ->selectRaw('page_id, COUNT(*) AS total')
            ->groupBy('page_id')
            ->orderBy('total', 'desc')
            ->get();

        foreach ($sql as $item) {
            $page = CounterPage::find($item->page_id);
            //$uri = explode($page->page, '|');
            $data[] = [
                'id'    => $item->page_id,
                'url'   => route($page->page),
                'total' => $item->total,
            ];
        }
        return $data;
    }
}
