<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table = 'das_articles';

    protected $fillable = ['name_article','slug','image','description'];
}
