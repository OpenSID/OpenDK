<?php

namespace App\Http\Controllers\Page;

use App\Facades\Counter;
use App\Http\Controllers\Controller;
use App\Models\DataDesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use willvincent\Feeds\Facades\FeedsFacade;
use Barryvdh\Debugbar\Facade as Debugbar;
use function compact;
use function config;
use function intval;
use function kuartal_bulan;
use function request;
use function rtrim;
use function semester;
use function str_replace;
use function view;
use function years_list;
use function array_column;

class PageController extends Controller
{
    protected $data = [];

    public function index()
    {
        Counter::count('beranda');
        
        $this->data = $this->GetFeeds();

        $feeds = collect($this->data)->take(30)->paginate(10);
        $feeds->all();
        return view('pages.index', [
            'page_title'       => 'Beranda',
            'page_description' => 'Berita Desa ' . $this->sebutan_wilayah,
            'cari'             => null,
            'cari_desa'        => null,
            'list_desa'        => DataDesa::get(),
            'feeds'            => $feeds,
        ]);
    }

    private function GetFeeds()
    {
        $all_desa = DataDesa::websiteUrl()->get()
        ->map(function ($desa) {
            return $desa->website_url_feed;
        })->all();

        foreach ($all_desa as $desa)
        {
            $getFeeds = FeedsFacade::make($desa['website']);
            foreach ($getFeeds->get_items() as $item) {
                $feeds[] = [
                    'desa_id'     => $desa['desa_id'],
                    'feed_link'   => $item->get_feed()->get_permalink(),
                    'feed_title'  => $item->get_feed()->get_title(),
                    'link'        => $item->get_link(),
                    'date'        => $item->get_date('U'),
                    'author'      => $item->get_author()->get_name(),
                    'title'       => $item->get_title(),
                    'description' => $item->get_description(),
                    'content'     => $item->get_content(),
                ];
            }
        }

        return $feeds ?? NULL;
    }

    public function FilterFeeds(Request $request)
    {
        $this->data = $this->GetFeeds();

        $req = $request->cari;
        $cari_desa = $request->desa != 'ALL' ? $request->desa : null;
        if ($req || $cari_desa) {
            $feeds = collect($this->data)->filter(function ($value, $key) use ($req, $cari_desa) {
                $hasil = $req ? stripos($value['title'], $req) !== false : true;
                $hasil = $hasil && ($cari_desa ? $cari_desa == $value['desa_id'] : true);
                return $hasil;
            })->take(30)->paginate(10);
        } else {
            $feeds = collect($this->data)->take(30)->paginate(10);
        }

        $feeds->all();
        $html =  view('pages._feeds', [
            'feeds' => $feeds,
        ])->render();

        return response()->json(compact('html'));
    }

    public function PotensiByKategory($slug)
    {
        $kategoriPotensi = DB::table('das_tipe_potensi')->where('slug', $slug)->first();
        $page_title       = 'Potensi';
        $page_description = 'Potensi-Potensi ' .$this->sebutan_wilayah;

        $potensis = DB::table('das_potensi')->where('kategori_id', $kategoriPotensi->id)->simplePaginate(10);

        return view('pages.potensi.index', compact(['page_title', 'page_description', 'potensis', 'kategoriPotensi']));
    }

    public function PotensiShow($kategori, $slug)
    {
        $kategoriPotensi = DB::table('das_tipe_potensi')->where('slug', $slug)->first();
        // dd($kategori_id);
        $page_title       = 'Potensi';
        $page_description = 'Potensi-Potensi Kecamatan';
        $potensi          = DB::table('das_potensi')->where('nama_potensi', str_replace('-', ' ', $slug))->first();
        // dd($potensis);
        return view('pages.potensi.show', compact(['page_title', 'page_description', 'potensi', 'kategoriPotensi']));
    }

    public function DesaShow($slug)
    {
        // Counter::count('desa.show');
        $page_title       = 'Desa';
        $page_description = 'Data Desa';
        $desa             = DB::table('das_data_desa')->where('nama', str_replace('-', ' ', $slug))->first();

        // dd($potensis);
        return view('pages.desa.desa_show', compact(['page_title', 'page_description', 'desa']));
    }

    public function refresh_captcha()
    {
        return response()->json(['captcha' => captcha_img('mini')]);
    }
}
