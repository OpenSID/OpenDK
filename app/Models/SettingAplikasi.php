<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingAplikasi extends Model
{
    const KEY_BROWSER_TITLE = 'judul_aplikasi';

    protected $table = 'das_setting';

    protected $fillable = ['key','value','type','description','option', 'category'];

    public $timestamps = false;

    public function isBrowserTitle()
    {
        return $this->key == self::KEY_BROWSER_TITLE;
    }
}
