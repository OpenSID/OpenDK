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

namespace App\Models;

use function array_push;
use Cartalyst\Sentinel\Roles\EloquentRole as Model;
use Cviebrock\EloquentSluggable\Sluggable;

use Illuminate\Database\Eloquent\Builder;

class Role extends Model
{
    use Sluggable;

    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'name',
        'slug',
        'permissions',
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    /**
     * Return user's query for Datatables.
     *
     * @return Builder
     */
    public static function datatables()
    {
        return static::select('name', 'slug', 'id')->where('slug', '!=', 'super-admin');
    }

    /**
     * Role User Belong To Many User Table
     */
    public function UserRoles()
    {
        return $this->belongsToMany(User::class, 'role_users', 'role_id');
    }

    /**
     * Dropdown list for role.
     *
     * @return array
     */
    public static function getListPermission()
    {
        $menus = Menu::where('is_active', true)->get();

        $response = [];

        foreach ($menus as $menu) {
            $result = [
                'name'      => $menu->name,
                'slug'      => $menu->slug,
                'parent_id' => $menu->parent_id,
                'id'        => $menu->id,
                'url'       => $menu->url,
            ];

            array_push($response, $result);
        }

        return $response;
    }

    /**
     * Get the permission based on role ID.
     *
     * @param  int   $id
     * @return array
     */
    public function getPermissionsKey($id)
    {
        $permissions = [];
        foreach (static::findOrFail($id)->permissions as $key => $value) {
            $permissions[] = $key;
        }
        return $permissions;
    }
}
