<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingAplikasi extends Model
{
    const KEY_BROWSER_TITLE = 'browser_title';

    protected $table = 'das_setting';

    protected $fillable = ['key','value','type','description','option'];

    public $timestamps = false;

    public function isBrowserTitle()
    {
        return $this->key == self::KEY_BROWSER_TITLE;
    }

    public function getRouteKeyName()
    {
        return 'id';
    }

}
