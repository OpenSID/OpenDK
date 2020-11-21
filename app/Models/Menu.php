<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use Sluggable;

    /**
     * {@inheritDoc}
     */
    protected $table = 'das_menu';

    /**
     * {@inheritDoc}
     */
    protected $fillable = ['name', 'slug', 'parent_id', 'url', 'icon'];

    /**
     * Return user's query for Datatables.
     *
     * @return Builder
     */
    public static function datatables()
    {
        return static::select('name', 'slug', 'parent_id', 'id')->get();
    }

    /**
     * slug for menu name
     *
     * @return     array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    /**
     * Show Menus
     *
     * @return void
     */
    public static function getAccess()
    {
        return ['vm.create' => true, 'vm.update' => true, 'vm.delete' => true, 'vm.show' => true];
    }
}
