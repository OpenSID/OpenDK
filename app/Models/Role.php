<?php

namespace App\Models;

use Cartalyst\Sentinel\Roles\EloquentRole as Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Role extends Model
{
    use Sluggable;
    /**
     * {@inheritDoc}
     */
    protected $fillable = [
        'name', 'slug', 'permissions'
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    /**
     * Return user's query for Datatables.
     *
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function datatables()
    {
        return static::select( 'name', 'slug', 'id' )->where('slug','!=','super-admin');
    }

    /**
     * Role User Belong To Many User Table
     */
    public function UserRoles()
    {
        return $this->belongsToMany('App\Models\User', 'role_users', 'role_id');
    }


    /**
     * Dropdown list for role.
     * @author Yoga <thetaramolor@gmail.com>
     * @return array
     */
    public static function getListPermission()
    {
        $menus = Menu::where( 'is_active', true )->get();
        
        $response = [];
        
        foreach ($menus as $menu) {
            $result = [
              'name' => $menu->name,
              'slug' => $menu->slug,
              'parent_id' => $menu->parent_id,
              'id' => $menu->id,
              'url' => $menu->url
            ];
    
            array_push( $response, $result );
        }

        return $response;
    }

    /**
     * Get the permission based on role ID.
     * @author Yoga <thetaramolor@gmail.com>
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

    /**
    * The directories belongs to broadcasts.
    * @author  Yoga <thetaramolor@gmail.com>
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function users()
    {
        return $this->belongsToMany( User::class, 'role_users' );
    }
}
