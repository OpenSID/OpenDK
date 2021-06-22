<?php

namespace App\Http\Controllers\Page;

use App\Facades\Counter;
use App\Http\Controllers\Controller;
use App\Models\DataDesa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use willvincent\Feeds\Facades\FeedsFacade;
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

class PageController extends Controller
{

    protected $data = [];

    public function index(Request $request)
    {
        Counter::count('beranda');
        
        $website = DataDesa::websiteUrl()->get()
        ->map(function ($website) {
            return $website->website_url_feed;
        })->all();
        
            $req = $request->cari;
            $getFeeds = FeedsFacade::make($website);
            foreach ($getFeeds->get_items() as $item) {
                $this->data[] = [
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
                if($req){
                    $feeds =  collect($this->data)->where('title',  $req)->take(5)->paginate(5);
                }else{
                        $feeds =  collect($this->data)->take(30)->paginate(10);
                    }
                
                $feeds->all();
        return view('pages.index', [
            'page_title'       => 'Beranda | ' . $this->browser_title,
            'page_description' => 'Berita Desa ' . $this->sebutan_wilayah, 
            'feeds'            => $feeds,
        ]);
    }

    public function PotensiByKategory($slug)
    {
        $kategoriPotensi = DB::table('das_tipe_potensi')->where('slug', $slug)->first();
        // dd($kategori_id);
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
}
