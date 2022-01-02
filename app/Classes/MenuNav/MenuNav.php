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
 * @package	    OpenDK
 * @author	    Tim Pengembang OpenDesa
 * @copyright	Hak Cipta 2017 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    	http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link	    https://github.com/OpenSID/opendk
 */

namespace App\Classes\MenuNav;

use App\Models\Menu;
use function array_push;

use function count;
use Sentinel;

class MenuNav
{
    public function menu()
    {
        $menus = Menu::where('is_active', 1)->where('parent_id', 0)->get();
        $data  = [];
        foreach ($menus as $i => $menu) {
            $childs = Menu::where('parent_id', $menu->id)->orderBy('id', 'ASC')->get()->toArray();

            $result = [
                'id'        => $menu->id,
                'name'      => $menu->name,
                'url'       => $menu->url,
                'slug'      => $menu->slug,
                'icon'      => $menu->icon,
                'parent_id' => $menu->parent_id,
                'child'     => $childs,
            ];

            array_push($data, $result);
        }

        return $data;
    }

    public function mainMenu($url)
    {
        $menus = $this->menu();

        $html = '<ul class="sidebar-menu">';
        $user = Sentinel::check();
        foreach ($menus as $i => $menu) {
            if ($menu['url'] == '#' || empty($menu['url'])) {
                $url_menu = 'javascript:void(0)';
            } else {
                $url_menu = $url . '/' . $menu['url'];
            }

            if ($user->hasAnyAccess([$menu['slug'], 'admin']) || $menu['slug'] == 'dashboard') {
                $down = '';
                if (count($menu['child']) > 0) {
                    $down = '<i class="fa fa-angle-left pull-right"></i>';
                }
                $html .= '<li class="treeview">';
                if ($menu['slug'] == 'speed-test') {
                    $html .= '<a href=" ' . $url_menu . ' " data-dropdown-toggle="false" target="_blank">';
                } else {
                    $html .= '<a href=" ' . $url_menu . ' " data-dropdown-toggle="false">';
                }
                $html .= '<i class="fa ' . $menu['icon'] . '" aria-hidden="true"></i>
                    ' . $menu['name'] . $down . '</a>';
                if (count($menu['child']) > 0) {
                    $html .= '<ul class="treeview-menu">';
                    foreach ($menu['child'] as $j => $child) {
                        if ($user->hasAnyAccess([$child['slug'], 'admin']) || $menu['slug'] == 'dashboard') {
                            $html .= '<li>
                                <a href=" ' . $url . '/' . $child['url'] . ' ">
                                    ' . $child['name'] . '
                                </a>
                            </li>';
                        }
                    }
                    $html .= '</ul>';
                }
                $html .= '</li>';
            }
        }

        return $html . '</ul>';
    }
}
