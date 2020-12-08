<?php

/* -----------------------------------------------------
 | Function Helpers.
 | -----------------------------------------------------
 |
 | Create basic function to easier developing
 | Yoga <thetaramolor@gmail.com>
 */

use App\Models\Menu;
use App\Models\Role;
use Illuminate\Support\Carbon;

/**
 * { function_description }
 *
 * @param      <type>  $parent_id  The parent identifier
 *
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
 *
 * @return     <type>  ( description_of_the_return_value )
 */
function permission_val($id, $permission)
{
    $role = Role::find($id);
    $format = json_decode(json_encode($role), true);
    $result = (isset($format['permissions'][$permission]) && $format['permissions'][$permission] != '' ? 1 : 0);
    return $result;
}

/**
 * Uploads an image.
 *
 * @param      <type>  $image  The image
 * @param      string $file The file
 *
 * @return     string  ( description_of_the_return_value )
 */
function upload_image($image, $file)
{
    $extension = $image->getClientOriginalExtension();
    $path = public_path('uploads/' . $file . '/');
    if (!file_exists($path)) {
        File::makeDirectory($path, 0777, true);
    }

    $name = time() . uniqid();
    $img = Image::make($image->getRealPath());
    $img->save($path . $name . '.' . $extension);
    return $name . '.' . $extension;
}

/**
 * Generate Password
 *
 * @param      integer $length Length Character
 *
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
    $meta = array(
        'code' => $code,
        'message' => $message
    );
    return $meta;
}

function convert_xml_to_array($filename)
{
    try {
        $xml = file_get_contents($filename);
        $convert = simplexml_load_string($xml, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($convert);
        $array = json_decode($json, true);
        return $array;
    } catch (\Exception $e) {
        \Log::info([
            "ERROR MESSAGE" => $e->getMessage(),
            "LINE" => $e->getLine(),
            "FILE" => $e->getFile()
        ]);
        return false;
    // throw new \UnexpectedValueException(trans('message.news.import-error'), 1);
    }
}


function convert_born_date_to_age($date)
{
    $from = new DateTime($date);
    $to   = new DateTime('today');
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
    return array(
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
    );
}

function get_words($sentence, $count = 10)
{

    preg_match("/(?:\w+(?:\W+|$)){0,$count}/", $sentence, $matches);
    return $matches[0];
}

function diff_for_humans($date)
{
    Carbon::setLocale('id');
    return  Carbon::parse($date)->diffForHumans();
}

function format_date($date)
{
    Carbon::setLocale('id');
    return  Carbon::parse($date)->toDayDateTimeString();
}

function kuartal_bulan()
{
    return array(
        'q1' => array(
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
        ),
        'q2' => array(
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
        ),
        'q3' => array(
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
        ),
        'q4' => array(
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        )
    );
}

function semester()
{
    return array(
        1 => array(
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
        ),
        2 => array(
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        )
    );
}

function status_rekam()
{
    return array(
        1 => 'BELUM WAJIB',
        2 => 'BELUM REKAM',
        3 => 'SUDAH REKAM',
        4 => 'CARD PRINTED',
        5 => 'PRINT READY RECORD',
        6 => 'CARD SHIPPED',
        7 => 'SENT FOR CARD PRINTING',
        8 => 'CARD ISSUED'
    );
}

function is_wajib_ktp($umur, $status_kawin)
{
    // Wajib KTP = sudah umur 17 atau pernah kawin
    if ($umur === null) {
        return null;
    }
    $wajib_ktp = (($umur > 16) or (!empty($status_kawin) and $status_kawin != 1));
    return $wajib_ktp;
}

function is_img($img)
{
    if ($img == '' || ! is_file($img)) {
        $img = '/img/no-image.png';
    }
    
    return $img;
}

