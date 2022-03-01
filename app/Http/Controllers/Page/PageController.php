<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright  Hak Cipta 2017 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
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

        return view('pages.index', [
            'page_title'       => 'Beranda',
            'cari'             => null,
            'artikel'          => Artikel::latest()->status()->paginate(config('setting.artikel_kecamatan_perhalaman') ?? 10),
        ]);
    }

    public function beritaDesa()
    {
        $this->data = $this->getFeeds();

        $feeds = collect($this->data)->sortByDesc('date')->take(config('setting.jumlah_artikel_desa') ?? 30)->paginate(config('setting.artikel_desa_perhalaman') ?? 10);
        $feeds->all();

        return view('pages.berita.desa', [
            'page_title'       => 'Berita Desa',
            'cari'             => null,
            'cari_desa'        => null,
            'list_desa'        => DataDesa::orderBy('desa_id')->get(),
            'feeds'            => $feeds,
        ]);
    }

    private function getFeeds()
    {
        $all_desa = DataDesa::websiteUrl()->get()
        ->map(function ($desa) {
            return $desa->website_url_feed;
        })->all();

        $feeds = [];
        foreach ($all_desa as $desa) {
            $getFeeds = FeedsFacade::make($desa['website']);
            foreach ($getFeeds->get_items() as $item) {
                $feeds[] = [
                    'desa_id'     => $desa['desa_id'],
                    'nama_desa'   => $desa['nama'],
                    'feed_link'   => $item->get_feed()->get_permalink(),
                    'feed_title'  => $item->get_feed()->get_title(),
                    'link'        => $item->get_link(),
                    'date'        => \Carbon\Carbon::parse($item->get_date('U'))->translatedFormat('d F Y'),
                    'author'      => $item->get_author()->get_name() ?? 'Administrator',
                    'title'       => $item->get_title(),
                    'image'       => get_tag_image($item->get_description()),
                    'description' => strip_tags(substr(str_replace(['&amp;', 'nbsp;', '[...]'], '', $item->get_description()), 0, 250) . '[...]'),
                    'content'     => $item->get_content(),
                ];
            }
        }

        return $feeds ?? null;
    }

    public function filterFeeds(Request $request)
    {
        $this->data = $this->getFeeds();
        $feeds = collect($this->data);

        // Filter
        $cari_desa = $request->desa;
        if ($cari_desa != 'Semua') {
            $feeds = $feeds->filter(function ($value, $key) use ($cari_desa) {
                return $cari_desa == $value['desa_id'];
            });
        }

        // Search
        $req = $request->cari;
        if ($req != '') {
            $feeds = $feeds->filter(function ($value, $key) use ($req) {
                return (stripos($value['title'], $req) || stripos($value['description'], $req));
            });
        }

        $feeds = $feeds->sortByDesc('date')->take(config('setting.jumlah_artikel_desa') ?? 30)->paginate(config('setting.artikel_desa_perhalaman') ?? 10);
        $feeds->all();

        $html =  view('pages.berita.feeds', [
            'page_title'       => 'Beranda',
            'cari_desa'        => null,
            'list_desa'        => DataDesa::orderBy('desa_id')->get(),
            'feeds'            => $feeds,
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
