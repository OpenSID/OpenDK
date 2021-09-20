<?php

/* -----------------------------------------------------
 | MenuNav
 | -----------------------------------------------------
 |
 | Create basic function to easier developing
 | Yoga <yoga.h@smooets.com>
 | www.smooets.com
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
