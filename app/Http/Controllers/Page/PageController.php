<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @package	    OpenDK
 * @author	    Tim Pengembang OpenDesa
 * @copyright	Hak Cipta 2017 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    	http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link	    https://github.com/OpenSID/opendk
 */

namespace App\Http\Controllers\Page;

use App\Facades\Counter;
use App\Http\Controllers\Controller;
use App\Models\Artikel;
use App\Models\DataDesa;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use willvincent\Feeds\Facades\FeedsFacade;

class PageController extends Controller
{
    protected $data = [];

    public function index()
    {
        Counter::count('beranda');

        $this->data = $this->GetFeeds();

        $feeds = collect($this->data)->sortByDesc('date')->take(30)->paginate(10, 'pageDesa');
        $feeds->all();
        return view('pages.index', [
            'page_title'       => 'Beranda',
            'cari'             => null,
            'cari_desa'        => null,
            'list_desa'        => DataDesa::get(),
            'feeds'            => $feeds,
            'artikel'          => Artikel::latest()->status()->paginate(10, ['*'], 'pageArtikel'),
        ]);
    }

    private function GetFeeds()
    {
        $all_desa = DataDesa::websiteUrl()->get()
        ->map(function ($desa) {
            return $desa->website_url_feed;
        })->all();

        foreach ($all_desa as $desa) {
            $getFeeds = FeedsFacade::make($desa['website']);
            foreach ($getFeeds->get_items() as $item) {
                $feeds[] = [
                    'desa_id'     => $desa['desa_id'],
                    'nama_desa'   => $desa['nama'],
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

        return $feeds ?? null;
    }

    public function FilterFeeds(Request $request)
    {
        $this->data = $this->GetFeeds();
        $feeds = collect($this->data);

        $req = $request->cari;
        $cari_desa = $request->desa != 'Semua' ? $request->desa : null;
        if ($req || $cari_desa) {
            $feeds = $feeds->filter(function ($value, $key) use ($req, $cari_desa) {
                $hasil = $req ? stripos($value['title'], $req) !== false : true;
                $hasil = $hasil && ($cari_desa ? $cari_desa == $value['desa_id'] : true);
                return $hasil;
            });
        }

        $feeds = $feeds->sortByDesc('date')->take(30)->paginate(10, 'pageDesa');
        $feeds->all();
        $html =  view('pages.berita.feeds', [
            'page_title'       => 'Beranda',
            'cari'             => null,
            'cari_desa'        => null,
            'list_desa'        => DataDesa::get(),
            'feeds'            => $feeds,
            'artikel'          => Artikel::latest()->status()->paginate(10, ['*'], 'pageArtikel'),
        ])->render();

        return response()->json(compact('html'));
    }

    public function PotensiByKategory($slug)
    {
        $kategoriPotensi  = DB::table('das_tipe_potensi')->where('slug', $slug)->first();
        $page_title       = 'Potensi';
        $page_description = 'Potensi-Potensi';

        $potensis = DB::table('das_potensi')->where('kategori_id', $kategoriPotensi->id)->simplePaginate(10);

        return view('pages.potensi.index', compact(['page_title', 'page_description', 'potensis', 'kategoriPotensi']));
    }

    public function PotensiShow($kategori, $slug)
    {
        $kategoriPotensi  = DB::table('das_tipe_potensi')->where('slug', $slug)->first();
        $page_title       = 'Potensi';
        $page_description = 'Potensi-Potensi Kecamatan';
        $potensi          = DB::table('das_potensi')->where('nama_potensi', str_replace('-', ' ', $slug))->first();

        return view('pages.potensi.show', compact(['page_title', 'page_description', 'potensi', 'kategoriPotensi']));
    }

    public function DesaShow($slug)
    {
        // Counter::count('desa.show');

        $desa             = DataDesa::nama($slug)->firstOrFail();
        $page_title       = 'Desa ' . $desa->nama;
        $page_description = 'Data Desa';

        return view('pages.desa.desa_show', compact('page_title', 'page_description', 'desa'));
    }

    public function refresh_captcha()
    {
        return response()->json(['captcha' => captcha_img('mini')]);
    }

    public function detailBerita($slug)
    {
        $artikel = Artikel::where('slug', $slug)->status()->firstOrFail();
        $page_title       = $artikel->judul;
        $page_description = substr($artikel->isi, 0, 300) . ' ...';
        $page_image = $artikel->gambar;

        return view('pages.berita.detail', compact('page_title', 'page_description', 'page_image', 'artikel'));
    }

    public function eventDetail($slug)
    {
        $event            = Event::slug($slug)->firstOrFail();
        $page_title       = $event->event_name;
        $page_description = $event->description;

        return view('pages.event.event_detail', compact('page_title', 'page_description', 'event'));
    }
}
