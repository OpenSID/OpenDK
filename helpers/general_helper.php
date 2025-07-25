<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright  Hak Cipta 2017 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

use App\Models\DataDesa;
use App\Models\Menu;
use App\Models\Role;
use App\Models\Themes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use voku\helper\AntiXSS;
use willvincent\Feeds\Facades\FeedsFacade;

/**
 * Parsing url image dari rss feed description
 *
 * @param  string  $content
 * @return string
 */
if (! function_exists('get_tag_image')) {
    function get_tag_image(string $content)
    {
        if (preg_match('/<img.+?src="(.+?)"/', $content, $match)) {
            return $match[1];
        }

        return asset('img/no-image.png');
    }
}

/**
 * { function_description }
 *
 * @param      <type>  $parent_id  The parent identifier
 * @return     <type>  ( description_of_the_return_value )
 */
function define_child($parent_id)
{
    $child = Menu::Where('parent_id', $parent_id)->where('is_active', true)->get();

    return $child;
}

/**
 * { function_description }
 *
 * @param      <type>  $id          The identifier
 * @param      <type>  $permission  The permission
 * @return     <type>  ( description_of_the_return_value )
 */
function permission_val($id, $permission)
{
    $role = Role::findOrFail($id);
    $format = json_decode(json_encode($role), true);
    $result = (isset($format['permissions'][$permission]) && $format['permissions'][$permission] != '' ? 1 : 0);

    return $result;
}

/**
 * Generate Password
 *
 * @param  int  $length Length Character
 * @return     string   voucher
 */
function generate_password($length = 6)
{
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $number = '0123456789';
    $charactersLength = strlen($characters);
    $numberLength = strlen($number);
    $randomString = '';
    for ($i = 0; $i < 3; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    for ($i = 0; $i < 3; $i++) {
        $randomString .= $number[rand(0, $numberLength - 1)];
    }
    $randomString = str_shuffle($randomString);

    return $randomString;
}

/**
 * Respon Meta
 *
 * @param      <type>  $message  The message
 */
function respon_meta($code, $message)
{
    $meta = [
        'code' => $code,
        'message' => $message,
    ];

    return $meta;
}

function convert_xml_to_array($filename)
{
    try {
        $xml = file_get_contents($filename);
        $convert = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        $json = json_encode($convert);
        $array = json_decode($json, true);

        return $array;
    } catch (\Exception $e) {
        \Log::info([
            'ERROR MESSAGE' => $e->getMessage(),
            'LINE' => $e->getLine(),
            'FILE' => $e->getFile(),
        ]);

        return false;
        // throw new \UnexpectedValueException(trans('message.news.import-error'), 1);
    }
}

function convert_born_date_to_age($date)
{
    $from = new DateTime($date);
    $to = new DateTime('today');

    return $from->diff($to)->y;
}

function random_color_part()
{
    return str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
}

function random_color()
{
    return random_color_part() . random_color_part() . random_color_part();
}

function years_list()
{
    // Create Year List for 4 years ago
    $this_year = date('Y');
    $year_list = [];

    for ($i = 1; $i <= 3; $i++) {
        $year_list[] = (int) $this_year--;
    }

    return $year_list;
}

function months_list()
{
    return [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];
}

function get_words($sentence, $count = 10)
{
    preg_match("/(?:\w+(?:\W+|$)){0,$count}/", $sentence, $matches);

    return $matches[0];
}

function diff_for_humans($date)
{
    return  Carbon::parse($date)->diffForHumans();
}

function format_datetime($date)
{
    if (empty($date) || $date == '-' || $date == '0000-00-00' || $date == '0000-00-00 00:00:00') {
        return '-'; // Atau bisa dikembalikan string lain seperti "Tanggal tidak tersedia"
    }

    return Carbon::parse($date)->translatedFormat('d F Y H:i:s');
}

function format_date($date)
{
    if (empty($date) || $date == '-' || $date == '0000-00-00' || $date == '0000-00-00 00:00:00') {
        return '-'; // Atau bisa dikembalikan string lain seperti "Tanggal tidak tersedia"
    }

    return Carbon::parse($date)->translatedFormat('d F Y');
}

function kuartal_bulan()
{
    return [
        'q1' => [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
        ],
        'q2' => [
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
        ],
        'q3' => [
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
        ],
        'q4' => [
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ],
    ];
}

function semester()
{
    return [
        1 => [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
        ],
        2 => [
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ],
    ];
}

function status_rekam()
{
    return [
        1 => 'BELUM WAJIB',
        2 => 'BELUM REKAM',
        3 => 'SUDAH REKAM',
        4 => 'CARD PRINTED',
        5 => 'PRINT READY RECORD',
        6 => 'CARD SHIPPED',
        7 => 'SENT FOR CARD PRINTING',
        8 => 'CARD ISSUED',
    ];
}

function is_wajib_ktp($umur, $status_kawin)
{
    // Wajib KTP = sudah umur 17 atau pernah kawin
    if ($umur === null) {
        return null;
    }
    $wajib_ktp = (($umur > 16) or (! empty($status_kawin) and $status_kawin != 1));

    return $wajib_ktp;
}

function isThumbnail($url = null, $img = '/img/no-image.png')
{
    return !empty($url) && Storage::disk('public')->exists($url) ? Storage::disk('public')->url($url) : asset($img);
}

function is_img($url = null, $img = '/img/no-image.png')
{
    return asset($url != null && file_exists(public_path($url)) ? $url : $img);
}

function is_logo($url = '', $file = '/img/logo.png')
{
    return is_img($url, $file);
}

function is_user($url = null, $sex = 1, $pengurus = null)
{
    if ($url && ! $pengurus) {
        $url = 'storage/penduduk/foto/' . $url;
    }

    $default = 'img/pengguna/' . (($sex == 2) ? 'wuser.png' : 'kuser.png');

    return is_img($url, $default);
}

function avatar($foto)
{
    if ($foto) {
        $foto = 'storage/user/' . $foto;
    }

    $default = 'bower_components/admin-lte/dist/img/user2-160x160.jpg';

    return is_img($foto, $default);
}

if (! function_exists('divnum')) {
    function divnum($numerator, $denominator)
    {
        return $denominator == 0 ? 0 : ($numerator / $denominator);
    }
}

if (! function_exists('format_number_id')) {
    function format_number_id($inp = 0)
    {
        return number_format($inp, 2, ',', '.');
    }
}

function terbilang($angka)
{
    $angka = abs($angka);
    $baca = ['', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas'];

    $terbilang = '';
    if ($angka < 12) {
        $terbilang = ' ' . $baca[$angka];
    } elseif ($angka < 20) {
        $terbilang = terbilang($angka - 10) . ' Belas';
    } elseif ($angka < 100) {
        $terbilang = terbilang($angka / 10) . ' Puluh' . terbilang($angka % 10);
    } elseif ($angka < 200) {
        $terbilang = ' seratus' . terbilang($angka - 100);
    } elseif ($angka < 1000) {
        $terbilang = terbilang($angka / 100) . ' Ratus' . terbilang($angka % 100);
    } elseif ($angka < 2000) {
        $terbilang = ' seribu' . terbilang($angka - 1000);
    } elseif ($angka < 1000000) {
        $terbilang = terbilang($angka / 1000) . ' Ribu' . terbilang($angka % 1000);
    } elseif ($angka < 1000000000) {
        $terbilang = terbilang($angka / 1000000) . ' Juta' . terbilang($angka % 1000000);
    }

    return $terbilang;
}

if (! function_exists('sudahInstal')) {
    /**
     * Cek apakah sudah install OpenDK atau belum
     *
     * @return bool True jika sudah install, False jika belum install
     */
    function sudahInstal(): bool
    {
        if (! file_exists(storage_path('installed'))) {
            return false;
        }

        return true;
    }
}

/**
 * Cek akses website.
 *
 * @param  string  $url
 * @return bool
 */
if (! function_exists('checkWebsiteAccessibility')) {
    function checkWebsiteAccessibility($url)
    {
        $options = [
            'http' => [
                'method' => 'GET',
                'timeout' => 3,
            ],
        ];
        $context = stream_context_create($options);
        $headers = @get_headers($url, 0, $context);

        if ($headers) {
            $status = substr($headers[0], 9, 3);
            if ($status == '200') {
                return true;
            }

            $status = "(Status: {$status})";
        }

        Log::debug('Website tidak dapat diakses');

        return false;
    }
}

if (! function_exists('bersihkan_xss')) {
    function bersihkan_xss($str)
    {
        $antiXSS = new AntiXSS();
        $antiXSS->removeEvilHtmlTags(['iframe']);
        $antiXSS->addEvilAttributes(['http-equiv', 'content']);

        return $antiXSS->xss_clean($str);
    }
}

if (! function_exists('parsedown')) {
    function parsedown($params = null)
    {
        $parsedown = new \App\Http\Controllers\Helpers\Parsedown();

        if (null !== $params) {
            return $parsedown->text(file_get_contents($params));
        }

        return $parsedown;
    }
}

if (! function_exists('theme_new')) {
    /**
     * Ambil model tema
     *
     * @return Theme
     */
    function theme_new()
    {
        if (Schema::hasTable('das_themes')) {
            return new Themes();
        }

        return null;
    }
}

if (! function_exists('scan_themes')) {
    function scan_themes()
    {
        $themes = \Hexadog\ThemesManager\Facades\ThemesManager::all();
        foreach ($themes as $theme) {
            // akse agar symlink dibuat
            \Hexadog\ThemesManager\Facades\ThemesManager::set($theme->getVendor() . '/' . $theme->getName());
            \App\Models\Themes::UpdateOrCreate([
                'vendor' => $theme->getVendor(),
                'name' => $theme->getName(),
            ], [
                'version' => $theme->getVersion(),
                'description' => $theme->getDescription(),
                'path' => $theme->getPath(),
                'screenshot' => $theme->getScreenshotImageUrl(),
                'system' => true,
                'options' => null,
            ]);
        }
    }
}

if (! function_exists('theme_active')) {
    function theme_active()
    {
        $themeActive = \App\Models\Themes::where('active', 1)->first();
        if (! $themeActive) {
            $themeActive = \App\Models\Themes::where('system', 1)->first();
            $themeActive->active = 1;
            $themeActive->save();
        }

        return \Hexadog\ThemesManager\Facades\ThemesManager::set($themeActive->slug);
    }
}

if (! function_exists('getFeeds')) {
    function getFeeds()
    {
        return cache()->remember('feeds_desa', 60 * 60, function () {
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
        });
    }
}

// Email hanya boleh berisi karakter alpha, numeric, titik, strip dan Tanda et,
function email($str): ?string
{
    return preg_replace('/[^a-zA-Z0-9@\\.\\-]/', '', htmlentities($str));
}

function bilangan_titik($str): ?string
{
    return preg_replace('/[^0-9\.]/', '', strip_tags($str));
}

// website hanya boleh berisi karakter alpha, numeric, titik, titik dua dan garis miring
function alamat_web($str): ?string
{
    return preg_replace('/[^a-zA-Z0-9:\\/\\.\\-]/', '', htmlentities($str));
}

function bilangan($str)
{
    if ($str == null) {
        return null;
    }

    return preg_replace('/[^0-9]/', '', strip_tags($str));
}

// Nama hanya boleh berisi karakter alpha, spasi, titik, koma, tanda petik dan strip
function nama($str): ?string
{
    return preg_replace("/[^a-zA-Z '\\.,\\-]/", '', strip_tags($str));
}

// Kode Wilayah Dengan Titik
// Dari 5201142005 --> 52.01.14.2005
function kode_wilayah($kode_wilayah): string
{
    $kode_prov_kab_kec = str_split(substr($kode_wilayah, 0, 6), 2);
    $kode_desa         = (strlen($kode_wilayah) > 6) ? '.' . substr($kode_wilayah, 6) : '';

    return implode('.', $kode_prov_kab_kec) . $kode_desa;
}

function hari($tgl): string
{
    $hari = [
        0 => 'Minggu',
        1 => 'Senin',
        2 => 'Selasa',
        3 => 'Rabu',
        4 => 'Kamis',
        5 => 'Jumat',
        6 => 'Sabtu',
    ];
    $dayofweek = date('w', strtotime($tgl));

    return $hari[$dayofweek];
}

/*
 * =======================================
 * Rupiah terbilang
 */
function number_to_words($number, $nol_sen = true): string
{
    $before_comma = trim(to_word($number));
    $after_comma  = trim(comma($number));
    $result       = $before_comma . ($nol_sen ? '' : ' koma ' . $after_comma);

    return ucwords($result . ' Rupiah');
}

function to_word($number): string
{
    $words      = '';
    $arr_number = [
        '',
        'satu',
        'dua',
        'tiga',
        'empat',
        'lima',
        'enam',
        'tujuh',
        'delapan',
        'sembilan',
        'sepuluh',
        'sebelas',
    ];

    $unit = ['', 'ribu', 'juta', 'milyar', 'triliun', 'kuadriliun', 'kuintiliun', 'sekstiliun', 'septiliun', 'oktiliun', 'noniliun', 'desiliun', 'undesiliun', 'duodesiliun', 'tredesiliun', 'kuatuordesiliun'];

    if (strpos($number, ',') === true) {
        $parts       = explode(',', $number);
        $intPart     = (int) $parts[0];
        $decimalPart = (int) $parts[1];

        $words = to_word($intPart) . ' koma ' . to_word($decimalPart);
    } else {
        $number = (int) str_replace('.', '', $number); // Ubah menjadi integer untuk memastikan hanya bilangan bulat yang diproses

        if ($number < 12) {
            $words = ' ' . $arr_number[$number];
        } elseif ($number < 20) {
            $words = to_word($number - 10) . ' belas';
        } elseif ($number < 100) {
            $words = to_word(intdiv($number, 10)) . ' puluh' . to_word($number % 10);
        } elseif ($number < 200) {
            $words = ' seratus' . to_word($number - 100);
        } elseif ($number < 1000) {
            $words = to_word(intdiv($number, 100)) . ' ratus' . to_word($number % 100);
        } elseif ($number >= 1000 && $number < 2000) {
            $words = ' seribu' . to_word($number - 1000);
        } else {
            for ($i = count($unit) - 1; $i >= 0; $i--) {
                $divider = 10 ** (3 * $i);
                if ($number < $divider) {
                    continue;
                }

                $div = intdiv($number, $divider);
                $mod = $number % $divider;

                $words = to_word($div) . ' ' . $unit[$i] . to_word($mod);
                break;
            }
        }
    }

    return $words;
}

function comma($number): string
{
    $after_comma = stristr($number, ',');
    $arr_number  = [
        'nol',
        'satu',
        'dua',
        'tiga',
        'empat',
        'lima',
        'enam',
        'tujuh',
        'delapan',
        'sembilan',
    ];

    $results = '';
    $length  = strlen($after_comma);
    $i       = 1;

    while ($i < $length) {
        $get = substr($after_comma, $i, 1);
        $results .= ' ' . $arr_number[$get];
        $i++;
    }

    return $results;
}

function bulan()
{
    return [1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'];
}

function getBulan(int $bln)
{
    $bulan = bulan();

    return $bulan[$bln];
}

function kembalikanSlug($str): ?string
{
    // Ganti '-' dengan spasi dan hilangkan titik '.'
    return  str_replace(['-','.'], [' ',''], $str);
}
