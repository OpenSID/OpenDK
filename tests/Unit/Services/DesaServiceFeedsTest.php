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

use App\Models\DataDesa;
use App\Services\DesaService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

function rssFeed(string $itemXml): string
{
    return '<?xml version="1.0" encoding="UTF-8"?>'
        .'<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/"><channel>'
        .'<title>Feed Desa</title><link>https://desa.test/</link><description>Berita</description>'
        .$itemXml
        .'</channel></rss>';
}

/**
 * DesaService tanpa akses jaringan: daftar desa dan isi feed ditentukan oleh test.
 *
 * @param  array<string, string|Throwable>  $feeds  website desa => XML RSS atau exception
 */
function desaServiceDenganFeed(array $feeds): DesaService
{
    return new class($feeds) extends DesaService
    {
        public function __construct(private array $feeds)
        {
            parent::__construct();
        }

        public function listDesa($all = false)
        {
            return collect(array_keys($this->feeds))->values()->map(fn ($website, $i) => new DataDesa([
                'desa_id' => 'DESA'.$i,
                'nama' => 'Desa '.$i,
                'sebutan_desa' => 'Desa',
                'website' => $website,
            ]));
        }

        protected function makeFeedReader(string $url): SimplePie
        {
            $website = str_replace('index.php/feed', '', $url);
            $feed = $this->feeds[$website];

            if ($feed instanceof Throwable) {
                throw $feed;
            }

            $reader = new SimplePie();
            $reader->enable_cache(false);
            $reader->set_raw_data($feed);
            $reader->init();

            return $reader;
        }
    };
}

beforeEach(function () {
    Cache::forget('desa_feeds');
});

afterEach(function () {
    Cache::forget('desa_feeds');
});

it('memakai author Administrator bila item feed tidak punya author', function () {
    $feeds = desaServiceDenganFeed([
        'https://desa-a.test/' => rssFeed('<item><title>Berita Tanpa Author</title><link>https://desa-a.test/artikel/1</link><description>Isi</description></item>'),
    ])->getFeeds();

    expect($feeds)->toHaveCount(1)
        ->and($feeds[0]['title'])->toBe('Berita Tanpa Author')
        ->and($feeds[0]['author'])->toBe('Administrator');
});

it('tetap memakai nama author dari feed bila tersedia', function () {
    $feeds = desaServiceDenganFeed([
        'https://desa-a.test/' => rssFeed('<item><title>Berita</title><link>https://desa-a.test/artikel/1</link><description>Isi</description><dc:creator>Operator Desa</dc:creator></item>'),
    ])->getFeeds();

    expect($feeds)->toHaveCount(1)
        ->and($feeds[0]['author'])->toBe('Operator Desa');
});

it('melewati feed desa yang gagal dan tetap mengambil feed desa lain', function () {
    Log::spy();

    $feeds = desaServiceDenganFeed([
        'https://desa-rusak.test/' => new RuntimeException('Koneksi gagal'),
        'https://desa-b.test/' => rssFeed('<item><title>Berita Desa B</title><link>https://desa-b.test/artikel/1</link><description>Isi</description></item>'),
    ])->getFeeds();

    expect($feeds)->toHaveCount(1)
        ->and($feeds[0]['title'])->toBe('Berita Desa B')
        ->and($feeds[0]['desa_id'])->toBe('DESA1');

    Log::shouldHaveReceived('warning')
        ->withArgs(fn ($message, $context) => $message === 'Gagal mengambil feed desa' && $context['desa_id'] === 'DESA0')
        ->once();
});

it('helper getFeeds mendelegasikan ke DesaService', function () {
    $hasil = [['title' => 'Berita', 'author' => 'Administrator']];

    $this->mock(DesaService::class)
        ->shouldReceive('getFeeds')
        ->once()
        ->andReturn($hasil);

    expect(getFeeds())->toBe($hasil);
});
