<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SettingAplikasi extends Model
{
    protected $table = 'das_setting';

    protected $fillable = ['key','value','type','description','option'];

    public $timestamps = false;
}
