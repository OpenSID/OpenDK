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

namespace App\Http\Controllers\FrontEnd;

use App\Enums\SurveiEnum;
use App\Models\Event;
use App\Models\Artikel;
use App\Facades\Counter;
use App\Models\DataDesa;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Catch_;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use willvincent\Feeds\Facades\FeedsFacade;
use App\Http\Controllers\FrontEndController;
use App\Http\Requests\SurveiRequest;
use App\Models\Kategori;
use App\Models\Survei;
use App\Services\DesaService;
use Jenssegers\Agent\Agent;

class PageController extends FrontEndController
{
    protected $data = [];

    public function index()
    {
        Counter::count('beranda');

        return view('pages.index', [
            'page_title' => 'Beranda',
            'cari' => null,
            'artikel' => Artikel::with('kategori')->latest()->status()->paginate(config('setting.artikel_kecamatan_perhalaman') ?? 10),
        ]);
    }

    public function beritaDesa()
    {
        $this->data = $this->getFeeds();

        $feeds = collect($this->data)->sortByDesc('date')->take(config('setting.jumlah_artikel_desa') ?? 30)->paginate(config('setting.artikel_desa_perhalaman') ?? 10);

        return view('pages.berita.desa', [
            'page_title' => 'Berita Desa',
            'cari' => null,
            'cari_desa' => null,
            'list_desa' => DataDesa::orderBy('desa_id')->get(),
            'feeds' => $feeds,
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
            $getFeeds = FeedsFacade::make($desa['website'], 5, true);
            foreach ($getFeeds->get_items() as $item) {
                $feeds[] = [
                    'desa_id' => $desa['desa_id'],
                    'nama_desa' => $desa['nama'],
                    'feed_link' => $item->get_feed()->get_permalink(),
                    'feed_title' => $item->get_feed()->get_title(),
                    'link' => $item->get_link(),
                    'date' => \Carbon\Carbon::parse($item->get_date('U')),
                    'author' => $item->get_author()->get_name() ?? 'Administrator',
                    'title' => $item->get_title(),
                    'image' => get_tag_image($item->get_description()),
                    'description' => strip_tags(substr(str_replace(['&amp;', 'nbsp;', '[...]'], '', $item->get_description()), 0, 250) . '[...]'),
                    'content' => $item->get_content(),
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
                return stripos($value['title'], $req) || stripos($value['description'], $req);
            });
        }

        $feeds = $feeds->sortByDesc('date')->take(config('setting.jumlah_artikel_desa') ?? 30)->paginate(config('setting.artikel_desa_perhalaman') ?? 10);

        // Tanggal
        $tanggal = $request->tanggal;
        if ($tanggal != 'Terlama') {
            $feeds = $feeds->sortBy('date')->take(config('setting.jumlah_artikel_desa') ?? 30)->paginate(config('setting.artikel_desa_perhalaman') ?? 10);
        }

        $feeds->all();

        $html = view('pages.berita.feeds', [
            'page_title' => 'Beranda',
            'cari_desa' => null,
            'list_desa' => DataDesa::orderBy('desa_id')->get(),
            'feeds' => $feeds,
        ])->render();

        return response()->json(compact('html'));
    }

    public function PotensiByKategory($slug)
    {
        $kategoriPotensi = DB::table('das_tipe_potensi')->where('slug', $slug)->first();
        $page_title = 'Potensi';
        $page_description = 'Potensi-Potensi';

        $potensis = DB::table('das_potensi')->where('kategori_id', $kategoriPotensi->id)->simplePaginate(10);

        return view('pages.potensi.index', compact(['page_title', 'page_description', 'potensis', 'kategoriPotensi']));
    }

    public function PotensiShow($kategori, $slug)
    {
        $kategoriPotensi = DB::table('das_tipe_potensi')->where('slug', $slug)->first();
        $page_title = 'Potensi';
        $page_description = 'Potensi-Potensi Kecamatan';
        $potensi = DB::table('das_potensi')->where('nama_potensi', str_replace('-', ' ', $slug))->first();

        return view('pages.potensi.show', compact(['page_title', 'page_description', 'potensi', 'kategoriPotensi']));
    }

    public function DesaShow($slug)
    {
        $desa = (new DesaService)->dataDesa($slug);        
        $page_title = 'Desa ' . $desa->nama;
        $page_description = 'Data Desa';

        return view('pages.desa.desa_show', compact('page_title', 'page_description', 'desa'));
    }

    public function refresh_captcha()
    {
        return response()->json(['captcha' => captcha_img('mini')]);
    }

    public function kategoriBerita($slug)
    {
        $kategori = Kategori::where('slug', $slug)->firstOrFail();
        $artikel = Artikel::whereRelation('kategori', 'slug', $slug)->paginate(9);
        return view('pages.berita.kategori', compact('artikel', 'kategori'));
    }

    public function detailBerita($slug, Request $request)
    {
        // Temukan artikel berdasarkan slug
        $artikel = Artikel::with(['kategori', 'comments' => function ($query) use ($request) {
            // Ambil komentar yang di-approve atau yang milik user dari session
            $userCommentIds = $request->session()->get('session_user_comments', []);

            // Ambil komentar utama (tanpa parent) yang di-approve atau yang dimiliki oleh user
            $query->whereNull('comment_id')
                ->where(function ($query) use ($userCommentIds) {
                    $query->where('status', 'enable')
                        ->orWhereIn('id', $userCommentIds);
                })
                ->with(['replies' => function ($query) use ($userCommentIds) {
                    // Ambil balasan yang di-approve atau yang milik user
                    $query->where(function ($query) use ($userCommentIds) {
                        $query->where('status', 'enable')
                            ->orWhereIn('id', $userCommentIds);
                    });
                }]);
        }])
            ->where('slug', $slug)
            ->when(!auth()->check(), fn($query) => $query->status())
            ->firstOrFail();


        $page_title = $artikel->judul;
        $page_description = substr($artikel->isi, 0, 300) . ' ...';
        $page_image = $artikel->gambar;

        // Ambil komentar utama yang terkait dengan artikel ini
        $comments = $artikel->comments;

        return view('pages.berita.detail', compact('page_title', 'page_description', 'page_image', 'artikel', 'comments'));
    }


    public function kirimKomentar(Request $request)
    {

        // Validasi input
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'body' => 'required|string',
            'das_artikel_id' => 'required|exists:das_artikel,id',
            'captcha_main' => 'required|captcha',
        ]);

        try {
            // Mendeteksi IP address
            $ipAddress = $request->ip();

            // Mendeteksi device menggunakan jenssegers/agent
            $agent = new Agent();
            $device = $agent->device() ?: 'Desktop';
            $platform = $agent->platform() ?: 'Unknown Platform';
            $browser = $agent->browser() ?: 'Unknown Browser';

            // Format informasi device
            $deviceInfo = "{$device} on {$platform} using {$browser}";

            // Simpan komentar baru
            $comment = \App\Models\Comment::create([
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'body' => $validated['body'],
                'status' => 'disable', // Set status default ke 'disable' untuk moderasi
                'das_artikel_id' => $validated['das_artikel_id'],
                'comment_id' => $request->input('comment_id', null), // Jika ini adalah balasan
                'ip_address' => $ipAddress,
                'device' => $deviceInfo,
            ]);

            // Simpan comment_id ke dalam session agar user bisa melihat komentarnya sendiri
            $request->session()->push('session_user_comments', $comment->id);

            // Redirect dengan pesan sukses
            return redirect()->back()->with('success', 'Komentar Anda telah ditambahkan.');
        } catch (\Throwable $th) {
            // Penanganan kesalahan
            return redirect()->back()->withInput($request->all())->withErrors([$th->getMessage()]);
        }
    }

    public function modalKirimBalasan(Request $request)
    {
        $commentId = $request->input('comment_id');
        $artikelId = $request->input('artikel_id');
        return view('pages.berita.comment', compact('commentId', 'artikelId'));
    }

    public function kirimBalasan(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'body' => 'required|string',
            'captcha_main' => 'required|captcha',
            'das_artikel_id' => 'required|exists:das_artikel,id', // Pastikan artikel terkait ada
            'comment_id' => 'required|exists:das_artikel_comment,id', // Pastikan comment_id ada
        ]);

        try {
            // Mendeteksi IP address
            $ipAddress = $request->ip();

            // Mendeteksi device menggunakan jenssegers/agent
            $agent = new Agent();
            $device = $agent->device() ?: 'Desktop';
            $platform = $agent->platform() ?: 'Unknown Platform';
            $browser = $agent->browser() ?: 'Unknown Browser';

            // Format informasi device
            $deviceInfo = "{$device} on {$platform} using {$browser}";

            $comment = \App\Models\Comment::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'body' => $request->body,
                'status' => 'disable',
                'das_artikel_id' => $request->das_artikel_id,
                'comment_id' => $request->comment_id,
                'ip_address' => $ipAddress,
                'device' => $deviceInfo
            ]);

            // Simpan comment_id ke dalam session agar user bisa melihat komentarnya sendiri
            $request->session()->push('session_user_comments', $comment->id);

            return redirect()->back()->with('success', 'Balasan berhasil dikirim.');
        } catch (\Throwable $th) {
            return redirect()->back()->withInput($request->all())->withErrors([$th->getMessage()]);
        }
    }


    public function eventDetail($slug)
    {
        $event = Event::slug($slug)->firstOrFail();
        $page_title = $event->event_name;
        $page_description = $event->description;

        return view('pages.event.event_detail', compact('page_title', 'page_description', 'event'));
    }

    public function kategori($slug)
    {
        // Temukan kategori berdasarkan slug
        $kategori = \App\Models\ArtikelKategori::where('slug', $slug)->firstOrFail();

        // Ambil semua artikel yang termasuk dalam kategori tersebut
        $berita_kategori = Artikel::with('kategori')
            ->where('id_kategori', $kategori->id_kategori)
            ->where('status', '1')
            ->latest()
            ->paginate(10);

        return view('pages.berita.kategori', [
            'artikel' => $berita_kategori,
            'kategori' => $kategori,
        ]);
    }

    // fitur survey indeks kepuasan pengguna terhadap penyajian informasi yang tersedia di website
    public function survei()
    {
        $page_title = 'Index Kepuasan Masyarakat';

        // Ambil hasil survei untuk ditampilkan
        $results = \App\Models\Survei::selectRaw('response, count(*) as count')
            ->groupBy('response')
            ->pluck('count', 'response')
            ->toArray();

        // Cek apakah pengguna sudah mengisi survei
        if (Session::has('survey_submitted')) {
            return view('pages.ikm.index', compact('page_title', 'results'))
                ->with('message', 'Anda sudah mengisi survei.');
        }

        return view('pages.ikm.index', compact('page_title', 'results'));
    }
    
    public function surveiSubmit(SurveiRequest $request)
    {
        // Cek ulang session untuk keamanan
        if (Session::has('survey_submitted')) {
            return redirect()->back()->with('message', 'Anda sudah mengisi survei.');
        }

        // Simpan data survei
        $response = SurveiEnum::getDescription($request->optionsRadios);

        Survei::create([
            'session_id' => Session::getId(), // Opsional
            'response' => $response,
            'consent' => true,
        ]);

        // Tandai session bahwa survei sudah diisi
        Session::put('survey_submitted', true);

        return redirect()->back()->with('success', 'Terima kasih atas tanggapan Anda!');
    }
}
