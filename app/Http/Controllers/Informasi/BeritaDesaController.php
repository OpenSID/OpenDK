<?php

namespace App\Http\Controllers\Informasi;

use App\Facades\Counter;
use App\Http\Controllers\Controller;
use App\Models\DataDesa;
use Illuminate\Http\Request;
use willvincent\Feeds\Facades\FeedsFacade;

class BeritaDesaController extends Controller
{
    /** @var array */
    protected $data = [];

    public function index(Request $request)
    {
        Counter::count('informasi.berita-desa.index');

        $website = DataDesa::websiteUrl()->get()
            ->map(function ($website) {
                return $website->website_url_feed;
            })->all();

        $feeds = FeedsFacade::make($website);

        foreach ($feeds->get_items() as $item) {
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

        return view('informasi.berita_desa.index', [
            'page_title'       => 'Berita Desa',
            'page_description' => 'Berita Desa ' . $this->sebutan_wilayah, 
            'feeds'            => collect($this->data)->paginate(4),
        ]);
    }
}
